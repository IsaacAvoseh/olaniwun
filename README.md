# CODING ASSESSMENT

This is a Laravel-based application.

## Quick Setup (Using Provided Files)

### Prerequisites

- PHP 8.2 or higher
- MySQL
- Web server (Apache/Nginx) or PHP's built-in server

### Installation Steps

1. **Extract the ZIP file** to your desired location

2. **Create a MySQL database**
   - Create a new database named `olaniwun`
   - Import the provided SQL dump file into this database

3. **Configure environment (if needed)**
   - The `.env` file should already be configured, but verify the database settings match your environment:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=olaniwun
     DB_USERNAME=your_username
     DB_PASSWORD=your_password
     ```

4. **Start the application**
   - Open a terminal/command prompt
   - Navigate to the project directory: `cd path/to/olaniwun`
   - Run: `php artisan serve`
   - Access the application at: `http://localhost:8000`

### Admin Login

- **Email**: admin@admin.com
- **Password**: password

## Alternative Setup (If Quick Setup Doesn't Work)

If you encounter issues with the provided vendor files, follow these steps:

1. **Delete the vendor folder**
   - Remove the entire `vendor` directory from the project

2. **Install Composer** (if not already installed)
   - Download and install from [getcomposer.org](https://getcomposer.org/download/)

3. **Install dependencies**
   - Open a terminal/command prompt
   - Navigate to the project directory: `cd path/to/olaniwun`
   - Run: `composer install`

4. **Generate application key**
   - Run: `php artisan key:generate`

5. **Run database migrations** (if needed)
   - Run: `php artisan migrate`
   - If you want to seed the database with sample data: `php artisan db:seed`

6. **Start the application**
   - Run: `php artisan serve`
   - Access the application at: `http://localhost:8000`

- **Blank page or 500 error**: Check storage/logs/laravel.log for details