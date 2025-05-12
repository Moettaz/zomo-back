# Zomo Backend

This is the backend API for the Zomo project, built with Laravel.

## Requirements

- PHP >= 8.1
- Composer
- WampServer (includes MySQL and Apache)
- Git

## Installation Steps

### 1. Install WampServer

1. Download WampServer from [wampserver.com](https://www.wampserver.com/en/)
2. Run the installer and follow the installation wizard
3. During installation, you'll be prompted to install:
   - Visual C++ Redistributable packages
   - PHP
   - MySQL
   - Apache
4. After installation, launch WampServer
5. Make sure the WampServer icon in the system tray is green (indicating all services are running)

### 2. Configure PHP

1. Open WampServer's PHP settings
2. Enable the following PHP extensions:
   - php_openssl
   - php_pdo_mysql
   - php_mbstring
   - php_fileinfo
   - php_xml

### 3. Project Setup

1. Clone the repository:
```bash
git clone [your-repository-url]
cd zomo-back
```

2. Install PHP dependencies:
```bash
composer install
```

3. Create environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

## Database Setup

1. Open phpMyAdmin:
   - Click on the WampServer icon in the system tray
   - Go to phpMyAdmin
   - Or visit `http://localhost/phpmyadmin`

2. Create a new database:
   - Click "New" in the left sidebar
   - Enter your database name (e.g., `zomo`)
   - Click "Create"

3. Configure your database connection in the `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zomo
DB_USERNAME=root
DB_PASSWORD=
```

4. Run the migrations:
```bash
php artisan migrate
```

5. (Optional) Seed the database with sample data:
```bash
php artisan db:seed
```

## Running the Application

1. Start the development server:
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Common Commands

- Run migrations: `php artisan migrate`
- Rollback migrations: `php artisan migrate:rollback`
- Create a new migration: `php artisan make:migration migration_name`
- Create a new model: `php artisan make:model ModelName`
- Create a new controller: `php artisan make:controller ControllerName`
- Clear cache: `php artisan cache:clear`
- Clear config: `php artisan config:clear`
- Clear route cache: `php artisan route:clear`
- Run tests: `php artisan test`

## Troubleshooting

1. If you get a "Class not found" error:
```bash
composer dump-autoload
```

2. If you get permission errors:
   - Make sure WampServer is running with administrator privileges
   - Check folder permissions in your project directory

3. If the database connection fails:
   - Verify MySQL is running in WampServer
   - Check your database credentials in `.env`
   - Make sure the database exists in phpMyAdmin

## Contributing

1. Create a new branch for your feature
2. Make your changes
3. Submit a pull request

## License

This project is licensed under the MIT License.
