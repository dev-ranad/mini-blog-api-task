# Mini Blog System API

This is a mini-blog RESTful API built with Laravel 12.

## Features
- User Authentication (Sanctum)
- Role and Permission System (Spatie)
- Posts CRUD with Image Upload
- Category Management (Many-to-Many)
- Comments System (One-to-Many)
- Full API with JSON Responses

## Requirements
- PHP 8.2+
- Composer
- MySQL 8+

## Backend Setup
1. Clone the repository:
    ```bash
    git clone https://github.com/your-username/mini-blog-api-task.git
    cd mini-blog-api-task
    ```

2. Install dependencies:
    ```bash
    composer install
    ```

3. Set up environment variables:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Run database migrations and seeders:
    ```bash
    php artisan migrate --seed
    ```

5. Set up storage link for images:
    ```bash
    php artisan storage:link
    ```

6. Run the application:
    ```bash
    php artisan serve
    ```

## Demo Users
- **Super Admin**: `superadmin@example.com`, Password: `password`
- **Admin**: `admin@example.com`, Password: `password`
- **Moderator**: `moderator@example.com`, Password: `password`
- **User**: `user@example.com`, Password: `password`

## Requirements
  - Node.js 18+
  - npm 9+

## Frontend Setup

1. Go into frontend directory:
    ```bash
    cd miniblog-frontend
    ```

2. Install dependencies:
    ```bash
    npm install
    ```

3. Install dependencies:
    ```bash
    cp .env.example .env
    ```

4. Install dependencies:
    ```bash
    npm run dev
    ```
