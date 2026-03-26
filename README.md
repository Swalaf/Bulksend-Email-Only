# BulkSend Email - Laravel SaaS Project

A clean, scalable Laravel email marketing platform with Blade + Tailwind CSS.

## Project Structure

```
├── app/
│   ├── Models/          # Eloquent models
│   ├── Repositories/    # Data access layer
│   ├── Services/        # Business logic layer
│   └── Http/
│       └── Controllers/
├── database/
│   └── migrations/      # Database migrations
├── routes/
│   ├── web.php         # Web routes
│   ├── api.php         # API routes
│   └── auth.php        # Authentication routes
├── resources/
│   ├── views/          # Blade templates
│   │   ├── layouts/    # Base layouts
│   │   └── components/ # Blade components
│   ├── js/             # JavaScript
│   └── css/             # CSS
└── config/              # Configuration files
```

## Features

- **Authentication**: Laravel Breeze (login, register, password reset)
- **Role-based Access**: Admin, User, Vendor
- **Modules**:
  - Users & Roles (with Spatie for permissions)
  - SMTP Accounts (multiple SMTP configuration)
  - Campaigns (email campaigns management)
  - Subscribers (subscriber lists)
  - Analytics (tracking opens, clicks, bounces)

## Getting Started

1. Install dependencies:
   ```bash
   composer install
   npm install
   ```

2. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Setup database:
   ```bash
   php artisan migrate
   ```

4. Run development server:
   ```bash
   php artisan serve
   npm run dev
   ```

## Tech Stack

- Laravel 11
- Blade Templates
- Tailwind CSS
- MySQL
- Alpine.js (for interactivity)

## Architecture

Following **SOLID principles**:
- **S**ingle Responsibility: Repositories handle data, Services handle business logic
- **O**pen/Closed: Easy to extend without modifying existing code
- **L**iskov Substitution: Base classes can be replaced with implementations
- **I**nterface Segregation: Small, focused interfaces
- **D**ependency Inversion: Depend on abstractions, not concretions
