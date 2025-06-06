# Laravel E-Commerce Platform


## Project Overview

A full-stack online retail system built with Laravel featuring:
- 🛍️ Product catalogue with filtering functionality
- 👥 User account authentication system
- 🛒 Shopping cart
- 📦 Transaction and order handling
- 👔 Admin dashboard for catalogue management


## Project Setup Guide

1. Install and setup XAMPP. Make sure all environment variables are set. Make sure all permissions have been accepted.
2. Install and setup composer. Make sure all environment variables are set and the composer command work on the command line.
3. Clone the project inside the htdocs folder within the XAMPP folder.
4. Run `composer install` to download all Laravel dependencies.
5. Start the Apache server and SQL service using XAMPP.
6. Using phpMyAdmin, create the swe30003 database.
7. Run laravel migrations to create the database tables.
8. Run the setup/db_init.php script to populate the DB with data.
9. Run `php artisan serve` to run the app.


## Project Structure
```
SWE30003-Project/
├── app/                  # Core application logic
│   ├── Core              # Core app classes  
│   ├── Http/Controllers/ # Stores app controllers
│   └── Models/           # Stores app models
├── config/               # Application configuration files
├── database/
│   └── migrations/       # Database schema definitions
├── public/
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files 
│   └── img/              # Images
├── resources/views/      # Stores all app views
│   ├── admin/           
│   ├── cart/           
│   ├── catalogue/       
│   └── ...              
└── routes/
    └── web.php           # Application routes
```


## Features

### Product Catalogue
- Structured display of all products 
- Product sorting
- Filter by brand, category, or price
- Add to cart functionality
- Product detail pages

### Cart Management
- Cart dashboard
- Modify item quantity
- Remove item
- Clear all
- Check item subtotal and total price

### Admin Panel
- Path: `/admin`
- Manage products/categories
- View order statistics
- Manage user roles

### Authentication Flow
- Login/Registration: `/login` & `/register`
- Password reset functionality
- Role-based access control


## Technologies Used
- HTML, CSS, JS, PHP
- Laravel 
- MariaDB
- Bootstrap
- XAMPP
