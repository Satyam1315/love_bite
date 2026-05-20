# Love Bite - Full Stack Food Ordering Website

Love Bite is a Laravel + Blade + Tailwind food ordering platform with:
- Customer and admin roles
- Authentication via Laravel Breeze
- Category and food management
- Session-based cart
- Checkout with COD
- Order tracking with Delivery In Progress simulation

## Tech Stack

- Backend: Laravel 12 (latest compatible in this environment)
- Frontend: Blade + Tailwind CSS + Vite
- Database: MySQL (recommended) or SQLite (supported)
- Auth: Laravel Breeze

## Features Implemented

- Authentication and role system (`admin`, `customer`)
- Route protection using `auth` and custom `admin` middleware
- Database schema for users, categories, foods, orders, order_items
- Menu listing with:
  - Category filter
  - Veg/Non-Veg filter
  - Search
  - Half/full portion pricing
- Cart system:
  - Add to cart
  - Update quantity
  - Remove items
  - Session storage
- Order workflow:
  - Checkout (address + COD)
  - Order + order items persistence
  - Unique order number generation (`LB-...`)
  - Confirmation screen
- Order statuses:
  - Pending
  - Accepted
  - Preparing
  - Ready
  - Delivery In Progress
  - Delivered
- Admin panel:
  - Dashboard (total orders, revenue)
  - Category CRUD
  - Food CRUD
  - Order list
  - Manual status updates including Delivery In Progress
- User dashboard:
  - Quick actions
  - Delivery system in progress message

## Step-by-Step Setup

1. Clone or open the project directory.
2. Install PHP dependencies:

```bash
composer install
```

3. Install Node dependencies:

```bash
npm install
```

4. Create environment file:

```bash
cp .env.example .env
```

5. Generate app key:

```bash
php artisan key:generate
```

6. Configure database in `.env`:

For MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=love_bite
DB_USERNAME=root
DB_PASSWORD=
```

7. Run migrations and seeders:

```bash
php artisan migrate --seed
```

8. Create storage symlink for uploaded food images:

```bash
php artisan storage:link
```

9. Run frontend build/dev:

```bash
npm run dev
```

10. Start Laravel server:

```bash
php artisan serve
```

Open the app at `http://127.0.0.1:8000`.

## Default Seeded Accounts

- Admin:
  - Email: `admin@lovebite.com`
  - Password: `password123`
- Customer:
  - Email: `customer@lovebite.com`
  - Password: `password123`

## Main Folder Structure

- `app/Http/Controllers`
  - `FoodController.php`: homepage/menu/search/filter
  - `CartController.php`: session cart operations
  - `OrderController.php`: checkout, order placement, user order history
  - `AdminController.php`: admin dashboard, category/food/order management
  - `AuthController.php`: role-aware post-login redirection
- `app/Http/Middleware/AdminMiddleware.php`: admin route guard
- `app/Models`
  - `User.php`, `Category.php`, `Food.php`, `Order.php`, `OrderItem.php`
- `database/migrations`: all table schemas
- `database/seeders`
  - `MenuSeeder.php`: category + menu items
  - `DatabaseSeeder.php`: users + menu seed
- `resources/views`
  - `welcome.blade.php`: homepage with hero, categories, popular items, full menu
  - `cart/*`, `orders/*`: customer order journey
  - `admin/*`: admin panel pages
- `routes/web.php`: frontend + auth + admin routes

## Useful Commands

```bash
php artisan migrate:fresh --seed
php artisan route:list
npm run build
php artisan serve
```

## Delivery System Note

This project intentionally does not implement real delivery-agent logic. Instead, it simulates delivery progression through order statuses and includes the required `Delivery In Progress` status.
