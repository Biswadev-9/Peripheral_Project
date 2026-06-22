CREATE DATABASE IF NOT EXISTS peripheral_inventory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE peripheral_inventory;

DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS wishlist;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS carts;
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(40) NULL,
    address TEXT NULL,
    role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
    status ENUM('active', 'blocked') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(140) NOT NULL UNIQUE,
    icon VARCHAR(60) NOT NULL DEFAULT 'bi-grid',
    image_url VARCHAR(500) NULL,
    description TEXT NULL,
    device_type ENUM('input', 'output', 'network', 'accessory') NOT NULL DEFAULT 'accessory',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(180) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    brand VARCHAR(120) NOT NULL,
    model VARCHAR(120) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    interface_type VARCHAR(120) NOT NULL,
    status ENUM('Available', 'Out of Stock', 'Under Maintenance') NOT NULL DEFAULT 'Available',
    stock_quantity INT NOT NULL DEFAULT 0,
    description TEXT NOT NULL,
    specifications JSON NULL,
    image_url VARCHAR(500) NULL,
    gallery JSON NULL,
    rating DECIMAL(2,1) NOT NULL DEFAULT 4.5,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    is_best_seller TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    session_id VARCHAR(128) NULL,
    status ENUM('active', 'converted', 'abandoned') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_carts_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    saved_for_later TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_cart_product (cart_id, product_id),
    CONSTRAINT fk_cart_items_cart FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    CONSTRAINT fk_cart_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_wishlist_product (user_id, product_id),
    CONSTRAINT fk_wishlist_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_wishlist_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(140) NOT NULL,
    email VARCHAR(160) NOT NULL,
    subject VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('Unread', 'Read') NOT NULL DEFAULT 'Unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    invoice_no VARCHAR(40) NOT NULL UNIQUE,
    full_name VARCHAR(140) NOT NULL,
    email VARCHAR(160) NOT NULL,
    phone VARCHAR(40) NOT NULL,
    address TEXT NOT NULL,
    payment_method ENUM('Cash on Delivery', 'Credit Card', 'Debit Card', 'Mobile Banking') NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    tax DECIMAL(10,2) NOT NULL,
    discount DECIMAL(10,2) NOT NULL DEFAULT 0,
    shipping DECIMAL(10,2) NOT NULL DEFAULT 0,
    grand_total DECIMAL(10,2) NOT NULL,
    status ENUM('Pending', 'Processing', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(180) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    title VARCHAR(160) NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reviews_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method VARCHAR(80) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('Pending', 'Paid', 'Failed') NOT NULL DEFAULT 'Pending',
    transaction_ref VARCHAR(120) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password, role, phone, address) VALUES
('Admin User', 'admin@example.com', '$2y$10$m/S0A2kAAV0y5CDYFsUjzuAwFQgXheW.eM4YkNvBeBoiA7FL8oJQO', 'admin', '+8801000000000', 'Computer Lab Office'),
('Demo Customer', 'user@example.com', '$2y$10$pmfWBzkfwh86cmldybKQze6zKhRS/vLHYR9nH7IBzXPfWAQnlIQe6', 'customer', '+8801777777777', 'Dhaka, Bangladesh');

INSERT INTO categories (name, slug, icon, image_url, description, device_type) VALUES
('Keyboard', 'keyboard', 'bi-keyboard', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=900&q=80', 'Mechanical, membrane, wireless, and lab-grade keyboards.', 'input'),
('Mouse', 'mouse', 'bi-mouse2', 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?auto=format&fit=crop&w=900&q=80', 'Precision pointing devices for productivity and gaming.', 'input'),
('Microphone', 'microphone', 'bi-mic', 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=900&q=80', 'USB and studio microphones for classes and recording.', 'input'),
('Speaker', 'speaker', 'bi-speaker', 'https://images.unsplash.com/photo-1545454675-3531b543be5d?auto=format&fit=crop&w=900&q=80', 'Desktop and lab audio speaker systems.', 'output'),
('Scanner', 'scanner', 'bi-upc-scan', 'https://images.unsplash.com/photo-1581092335397-9fa341108e1d?auto=format&fit=crop&w=900&q=80', 'Flatbed and document scanning peripherals.', 'input'),
('Monitor', 'monitor', 'bi-display', 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=900&q=80', 'Full HD, 2K, and high-refresh displays.', 'output'),
('Printer', 'printer', 'bi-printer', 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?auto=format&fit=crop&w=900&q=80', 'Inkjet, laser, and network printer devices.', 'output'),
('Projector', 'projector', 'bi-projector', 'https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?auto=format&fit=crop&w=900&q=80', 'Classroom and presentation projection systems.', 'output'),
('Camera', 'camera', 'bi-camera-video', 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=900&q=80', 'Webcams and imaging peripherals.', 'input'),
('Joystick', 'joystick', 'bi-joystick', 'https://images.unsplash.com/photo-1592840496694-26d035b52b48?auto=format&fit=crop&w=900&q=80', 'Game controllers and simulation input devices.', 'input'),
('Network Adapter', 'network-adapter', 'bi-router', 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?auto=format&fit=crop&w=900&q=80', 'USB Wi-Fi, Ethernet, and Bluetooth adapters.', 'network'),
('Accessories', 'accessories', 'bi-usb-plug', 'https://images.unsplash.com/photo-1601524909162-ae8725290836?auto=format&fit=crop&w=900&q=80', 'Cables, hubs, stands, and peripheral accessories.', 'accessory');

INSERT INTO products (category_id, name, slug, brand, model, price, interface_type, status, stock_quantity, description, specifications, image_url, gallery, rating, is_featured, is_best_seller) VALUES
((SELECT id FROM categories WHERE slug='mouse'), 'Wireless Mouse', 'wireless-mouse', 'Logitech', 'M185', 24.99, 'USB Receiver', 'Available', 42, 'Reliable wireless mouse with long battery life and comfortable daily use.', JSON_OBJECT('DPI','1000','Battery','12 months','Warranty','1 year'), 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?auto=format&fit=crop&w=900&q=80', JSON_ARRAY('https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?auto=format&fit=crop&w=900&q=80','https://images.unsplash.com/photo-1613141412501-9012977f1969?auto=format&fit=crop&w=900&q=80'), 4.6, 1, 1),
((SELECT id FROM categories WHERE slug='mouse'), 'Wired Mouse', 'wired-mouse', 'Dell', 'MS116', 12.50, 'USB-A', 'Available', 70, 'Simple optical wired mouse for labs and high-turnover classrooms.', JSON_OBJECT('DPI','1000','Cable','1.8m','Warranty','1 year'), 'https://images.unsplash.com/photo-1629429407756-3a3fb8b7a57f?auto=format&fit=crop&w=900&q=80', JSON_ARRAY('https://images.unsplash.com/photo-1629429407756-3a3fb8b7a57f?auto=format&fit=crop&w=900&q=80'), 4.3, 0, 1),
((SELECT id FROM categories WHERE slug='mouse'), 'Gaming Mouse', 'gaming-mouse', 'Razer', 'DeathAdder Essential', 39.99, 'USB-A', 'Available', 18, 'Ergonomic gaming mouse with programmable buttons and high precision sensor.', JSON_OBJECT('DPI','6400','Buttons','5','Lighting','Green'), 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?auto=format&fit=crop&w=900&q=80', JSON_ARRAY('https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?auto=format&fit=crop&w=900&q=80'), 4.8, 1, 1),
((SELECT id FROM categories WHERE slug='mouse'), 'Bluetooth Mouse', 'bluetooth-mouse', 'Microsoft', 'Modern Mobile', 29.99, 'Bluetooth 5.0', 'Available', 25, 'Slim Bluetooth mouse for portable lab kits and tablet stations.', JSON_OBJECT('DPI','1200','Battery','AA','Color','Matte black'), 'https://images.unsplash.com/photo-1527814050087-3793815479db?auto=format&fit=crop&w=900&q=80', JSON_ARRAY('https://images.unsplash.com/photo-1527814050087-3793815479db?auto=format&fit=crop&w=900&q=80'), 4.5, 0, 0),
((SELECT id FROM categories WHERE slug='mouse'), 'Ergonomic Mouse', 'ergonomic-mouse', 'Anker', 'AK-UBA', 31.00, 'USB Receiver', 'Available', 11, 'Comfort-focused wireless mouse designed to reduce wrist strain.', JSON_OBJECT('DPI','800/1200/1600','Buttons','6','Battery','AAA'), 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?auto=format&fit=crop&w=900&q=80', JSON_ARRAY('https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?auto=format&fit=crop&w=900&q=80'), 4.4, 0, 0),
((SELECT id FROM categories WHERE slug='mouse'), 'Vertical Mouse', 'vertical-mouse', 'Logitech', 'Lift', 69.99, 'Bluetooth / USB Receiver', 'Available', 7, 'Vertical ergonomic mouse for long sessions and accessibility-friendly workstations.', JSON_OBJECT('DPI','4000','Angle','57 degrees','Battery','24 months'), 'https://images.unsplash.com/photo-1613141412501-9012977f1969?auto=format&fit=crop&w=900&q=80', JSON_ARRAY('https://images.unsplash.com/photo-1613141412501-9012977f1969?auto=format&fit=crop&w=900&q=80'), 4.7, 1, 0),
((SELECT id FROM categories WHERE slug='keyboard'), 'Mechanical Keyboard', 'mechanical-keyboard', 'Keychron', 'K2 V2', 84.00, 'Bluetooth / USB-C', 'Available', 16, 'Compact mechanical keyboard with tactile switches and multi-device pairing.', JSON_OBJECT('Switch','Brown','Layout','75%','Backlight','White LED'), 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=900&q=80', JSON_ARRAY('https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=900&q=80'), 4.9, 1, 1),
((SELECT id FROM categories WHERE slug='monitor'), '24 Inch IPS Monitor', '24-inch-ips-monitor', 'ASUS', 'VA24EHE', 149.00, 'HDMI / VGA', 'Available', 14, 'Full HD IPS monitor with wide viewing angles for lab workstations.', JSON_OBJECT('Resolution','1920x1080','Refresh Rate','75Hz','Panel','IPS'), 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=900&q=80', JSON_ARRAY('https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=900&q=80'), 4.6, 1, 1),
((SELECT id FROM categories WHERE slug='printer'), 'Laser Printer', 'laser-printer', 'HP', 'LaserJet M111w', 119.00, 'USB / Wi-Fi', 'Available', 5, 'Compact laser printer with wireless support and fast monochrome output.', JSON_OBJECT('Speed','21 ppm','Connectivity','USB, Wi-Fi','Duty Cycle','8000 pages'), 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?auto=format&fit=crop&w=900&q=80', JSON_ARRAY('https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?auto=format&fit=crop&w=900&q=80'), 4.2, 0, 0),
((SELECT id FROM categories WHERE slug='microphone'), 'USB Condenser Microphone', 'usb-condenser-microphone', 'Blue', 'Snowball iCE', 49.99, 'USB', 'Available', 9, 'Clear plug-and-play USB microphone for classes, meetings, and recordings.', JSON_OBJECT('Pattern','Cardioid','Sample Rate','44.1kHz','Stand','Included'), 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=900&q=80', JSON_ARRAY('https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=900&q=80'), 4.5, 0, 0),
((SELECT id FROM categories WHERE slug='network-adapter'), 'USB Wi-Fi Adapter', 'usb-wifi-adapter', 'TP-Link', 'Archer T3U', 19.99, 'USB 3.0', 'Available', 36, 'Dual-band USB Wi-Fi adapter for desktops and lab recovery kits.', JSON_OBJECT('Speed','AC1300','Bands','2.4GHz/5GHz','USB','3.0'), 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?auto=format&fit=crop&w=900&q=80', JSON_ARRAY('https://images.unsplash.com/photo-1606904825846-647eb07f5be2?auto=format&fit=crop&w=900&q=80'), 4.4, 1, 0),
((SELECT id FROM categories WHERE slug='projector'), 'Classroom Projector', 'classroom-projector', 'Epson', 'EB-E01', 329.00, 'HDMI / VGA / USB', 'Under Maintenance', 2, 'Bright projector suitable for classrooms and presentation labs.', JSON_OBJECT('Brightness','3300 lumens','Resolution','XGA','Lamp Life','12000 hours'), 'https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?auto=format&fit=crop&w=900&q=80', JSON_ARRAY('https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?auto=format&fit=crop&w=900&q=80'), 4.1, 0, 0);

INSERT INTO reviews (product_id, user_id, rating, title, comment) VALUES
((SELECT id FROM products WHERE slug='wireless-mouse'), 2, 5, 'Great for labs', 'Battery life and tracking are excellent for daily student use.'),
((SELECT id FROM products WHERE slug='mechanical-keyboard'), 2, 5, 'Premium typing feel', 'A strong upgrade for programming lab stations.'),
((SELECT id FROM products WHERE slug='24-inch-ips-monitor'), 2, 4, 'Clear display', 'Good viewing angles and comfortable for long practical sessions.');
