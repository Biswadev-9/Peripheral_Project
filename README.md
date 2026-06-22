# Peripheral Inventory Management System

A complete PHP/MySQL full-stack application for managing computer laboratory peripheral inventory and selling devices through a shopping system.

## Stack

- HTML5, CSS3, JavaScript ES6
- Bootstrap 5 and Bootstrap Icons
- PHP with PDO
- MySQL / phpMyAdmin
- XAMPP local environment

## Setup

1. Copy or keep this folder at `C:\xampp\htdocs\exam`.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Open `http://localhost/exam/install.php`.
4. Click **Install Database**.
5. Open `http://localhost/exam/index.php`.

You can also import `database/schema.sql` manually through phpMyAdmin, then run `C:\xampp\php\php.exe database\seed_products.php` and `C:\xampp\php\php.exe database\product_image_assets.php` from this folder to load the full product catalog and local product images.

## Demo Accounts

- Admin: `admin@example.com` / `admin123`
- Customer: `user@example.com` / `user123`

## Main Routes

- Storefront: `/index.php`
- Categories: `/categories.php`
- Products: `/products.php`
- Product details: `/product.php?slug=wireless-mouse`
- Cart: `/cart/index.php`
- Checkout: `/checkout/index.php`
- Admin dashboard: `/admin/index.php`
- Inventory CRUD: `/admin/devices.php`

## API Routes

- `GET /api/products.php`
- `GET /api/products.php?category=mouse&search=wireless`
- `GET /api/categories.php`
- `GET /api/cart.php`
- `POST /api/cart.php` with JSON `{ "product_id": 1, "quantity": 2 }`

## Folder Structure

```text
admin/       Admin dashboard, inventory CRUD, order management
api/         JSON endpoints for products, categories, and cart
assets/      CSS and JavaScript
auth/        Login, registration, logout
cart/        Cart page and POST actions
checkout/    Checkout and invoice pages
config/      Database connection
database/    MySQL schema and seed data
includes/    Shared layout, helpers, product card, admin sidebar
orders/      Customer order history
uploads/     Uploaded device images
```

## Features

- Sticky responsive navbar with dark mode and cart counter
- Modern SaaS-style homepage, hero, category grid, product catalog, and cards
- Product detail page with image gallery, specs, ratings, reviews, wishlist, and comparison
- Cart with quantity updates, remove, save for later, coupon, tax, shipping, discount, and grand total
- Checkout with multiple payment methods and generated invoice
- Secure authentication with role-based access control
- Admin dashboard with total devices, categories, orders, revenue, low-stock items, and charts
- Inventory CRUD with search, status, stock, category, interface, description, specs, and images
- JSON API routes for integration

## Notes

- The coupon code `LAB10` applies a 10% discount.
- Uploaded images are stored in `uploads/`.
- External demo images are loaded from Unsplash URLs in the seed data.
