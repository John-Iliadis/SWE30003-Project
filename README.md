# Laravel E-Commerce Platform


## Project Overview

A full-stack online retail system built with Laravel featuring:
- 🛍️ Product catalogue with filtering functionality
- 👥 User account authentication system
- 🛒 Shopping cart
- 📦 Transaction and order handling
- 👔 Admin dashboard for catalogue management


## Project Setup Guide

1. Install and setup XAMPP
2. Install and setup composer
3. Clone this project inside the htdocs folder within XAMPP
4. Run `composer install` to install the project dependencies
5. Run laravel migrations
6. Run the setup/db_init.php script to populate the DB with data
7. Run `php artisan serve` to run the app


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
