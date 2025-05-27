# Laravel E-Commerce Platform

## Project Overview
A full-stack online retail system built with Laravel featuring:
- 🛍️ Product catalog with categories
- 👥 User authentication system
- 🛒 Shopping cart functionality
- 📦 Order management system
- 👔 Admin dashboard for inventory control

## Installation Guide
1. Clone repository
2. Install dependencies:
3. Configure environment
4. Generate application key
5. Run database setup:
```bash
php artisan migrate
```
## Project Structure
```bash
SWE30003-Project/
├── app/                  # Core application logic
│   ├── Http/Controllers/ # All application controllers
│   ├── Models/          # Database models (Product, Category, Order etc.)
│   └── Providers/       # Service providers
├── config/              # Application configuration files
├── database/
│   ├── migrations/      # Database schema definitions
│   └── seeders/         # Test data generators
├── public/
│   ├── css/             # Stylesheets
│   │   ├── admin.css    # Admin panel styles
│   │   ├── global.css   # Shared styles
│   │   └── ...         # Other style files
│   └── img/             # Product images
├── resources/views/
│   ├── admin/           # Admin dashboard templates
│   ├── auth/            # Authentication pages
│   ├── catalogue/       # Product listing pages
│   └── ...              # Other view templates
├── routes/
│   └── web.php          # Main application routes
├── storage/             # Uploaded files, logs, and framework cache
└── tests/               # PHPUnit test cases
```

## Key Features
### Admin Panel
- Path: `/admin`
- Manage products/categories
- View order statistics
- Manage user roles

### Authentication Flow
- Login/Registration: `/login` & `/register`
- Password reset functionality
- Role-based access control

### Product Catalog
- Browse by category
- Search functionality
- Product detail pages

## Technologies Used
- Laravel 
- MySQL
- Bootstrap 
- PHP 
- XAMPP