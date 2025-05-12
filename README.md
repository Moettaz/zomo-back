# Zomo Backend

This is the backend API for the Zomo project, built with Laravel.

## Requirements

- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Node.js & NPM (for frontend assets)

## Installation

1. Clone the repository:
```bash
git clone [your-repository-url]
cd zomo-back
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install NPM dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

## Database Setup

1. Create a new MySQL database for the project

2. Configure your database connection in the `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

3. Run the migrations:
```bash
php artisan migrate
```

4. (Optional) Seed the database with sample data:
```bash
php artisan db:seed
```

## Running the Application

1. Start the development server:
```bash
php artisan serve
```

2. For development with hot-reload (if using Vite):
```bash
npm run dev
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

## API Documentation

API documentation is available at `/api/documentation` when running the application.

## Contributing

1. Create a new branch for your feature
2. Make your changes
3. Submit a pull request

## License

This project is licensed under the MIT License.
