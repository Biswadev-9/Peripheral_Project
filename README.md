# Peripheral Inventory Management System

Peripheral Inventory Management System is a PHP and MySQL based web application for managing computer laboratory devices. It can be used to track peripherals such as keyboards, mice, monitors, printers, scanners, microphones, speakers, projectors, and other accessories.

The system also includes a simple shopping feature where users can browse products, add items to cart, place orders, and generate invoices.
Live Link : https://peirpheral.infinityfreeapp.com/

## Features

* User registration and login
* Admin and customer roles
* Admin dashboard
* Add, edit, delete, and manage devices
* Product category management
* Product search and filtering
* Product details page
* Wishlist and comparison option
* Shopping cart system
* Checkout system
* Invoice generation
* Order history
* Coupon discount system
* Dark mode option
* Responsive design for mobile, tablet, and desktop
* JSON API routes for products, categories, and cart

## Technology Used

* HTML5
* CSS3
* JavaScript
* Bootstrap 5
* Bootstrap Icons
* PHP
* MySQL
* PDO
* phpMyAdmin
* XAMPP for local development

## Local Setup

1. Download or clone this project.

```bash
git clone https://github.com/your-username/peripheral-inventory-management-system.git
```

2. Copy the project folder to:

```text
C:\xampp\htdocs\exam
```

3. Start Apache and MySQL from the XAMPP Control Panel.

4. Open the project installer in your browser:

```text
http://localhost/exam/install.php
```

5. Click **Install Database**.

6. Open the project:

```text
http://localhost/exam/index.php
```

## Manual Database Setup

You can also import the database manually.

1. Open phpMyAdmin.
2. Create a database.
3. Import:

```text
database/schema.sql
```

4. Then run the seed files if needed:

```bash
C:\xampp\php\php.exe database\seed_products.php
C:\xampp\php\php.exe database\product_image_assets.php
```

## Demo Accounts

### Admin Account

```text
Email: admin@example.com
Password: admin123
```

### Customer Account

```text
Email: user@example.com
Password: user123
```

## Main Pages

```text
Home:              /index.php
Categories:        /categories.php
Products:          /products.php
Product Details:   /product.php?slug=wireless-mouse
Cart:              /cart/index.php
Checkout:          /checkout/index.php
Order History:     /orders/index.php
Admin Dashboard:   /admin/index.php
Inventory CRUD:    /admin/devices.php
```

## API Routes

```text
GET  /api/products.php
GET  /api/products.php?category=mouse&search=wireless
GET  /api/categories.php
GET  /api/cart.php
POST /api/cart.php
```

Example POST request:

```json
{
  "product_id": 1,
  "quantity": 2
}
```

## Folder Structure

```text
Peripheral IMS/
├── admin/        # Admin dashboard, inventory management, orders, messages
├── api/          # JSON API endpoints for products, categories, cart
├── assets/       # CSS, JavaScript, images
├── auth/         # User login, registration, logout
├── cart/         # Cart page and cart actions
├── checkout/     # Checkout and invoice pages
├── config/       # Database connection and configuration
├── database/     # Database schema and seed files
├── includes/     # Shared functions, header, footer, product cards
├── orders/       # Customer order history
└── uploads/      # Uploaded product images
```

## Hosting Notes

When uploading the project to hosting:

1. Upload all files into the hosting root folder, usually `htdocs`.
2. Make sure the `assets` folder is uploaded correctly.
3. Make sure `assets/images/products` exists.
4. Upload `.htaccess`.
5. Import the database using phpMyAdmin.
6. Update database credentials inside the config file.
7. Clear browser cache after uploading.

If the project is hosted directly in the root folder, the website should open like this:

```text
https://yourdomain.com
```

If the project is hosted inside a folder, it will open like this:

```text
https://yourdomain.com/exam
```

## Coupon Code

The demo coupon code is:

```text
LAB10
```

It gives a 10% discount.

## Notes

* Uploaded images are stored in the `uploads/` folder.
* Demo product images may use Unsplash image links.
* Local product images are stored inside `assets/images/products`.
* The project is mainly designed for computer laboratory peripheral inventory management.

## Project Purpose

This project was created to make peripheral device management easier for computer labs. It helps admins track devices, manage stock, monitor orders, and keep records in one place.
