# Agape EDU CRM Laravel

A Customer Relationship Management (CRM) system built with Laravel for educational institutions.

<p align="center">
<a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel" alt="Laravel Version"></a>
<a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php" alt="PHP Version"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License"></a>
</p>

## About Agape EDU CRM

Agape EDU CRM is a comprehensive customer relationship management system designed specifically for educational institutions. Built on the Laravel framework, it provides tools to manage student information, track interactions, and streamline administrative processes.

## Features

- Student information management  
- User authentication and authorization  
- Role-based access control  
- Database migrations and seeders  
- Modern UI with Tailwind CSS  
- Excel import/export functionality (via FastExcel)

## Requirements

- PHP 8.2 or higher  
- Composer  
- Node.js and NPM  
- MySQL or PostgreSQL database

## Installation

1. Clone the repository:
```bash
git clone https://github.com/iamdulanga/agape-edu-crm-laravel.git
cd agape-edu-crm-laravel
````

2. Install PHP dependencies:

```bash
composer install
```

3. Install Node.js dependencies:

```bash
npm install
```

4. Copy the environment file and configure your database:

```bash
cp .env.example .env
```

5. Generate application key:

```bash
php artisan key:generate
```

6. Run database migrations:

```bash
php artisan migrate
```

7. Seed the database:

```bash
php artisan db:seed
```

8. Build frontend assets:

```bash
npm run build
```

> **⚠️ PowerShell Execution Policy Error**
> If you see this error when running the above command:
>
> ```bash
> PS C:\Users\dilan\Desktop\agape-edu-crm-laravel> npm run build
> npm : File C:\Program Files\nodejs\npm.ps1 cannot be loaded because running scripts is disabled on this system.
> For more information, see about_Execution_Policies at https:/go.microsoft.com/fwlink/?LinkID=135170.
> At line:1 char:1
> + npm run build
> + ~~~
>     + CategoryInfo          : SecurityError: (:) [], PSSecurityException
>     + FullyQualifiedErrorId : UnauthorizedAccess
> ```
>
> This happens because PowerShell restricts running certain scripts.
> To fix it, open **PowerShell as Administrator** and run:
>
> ```bash
> Set-ExecutionPolicy -Scope CurrentUser RemoteSigned
> ```
>
> Then try `npm run build` again.

9. Start the development server:

```bash
php artisan serve
```

## Development

To run the application in development mode with hot reload:

```bash
npm run dev
```

In a separate terminal, start the Laravel development server:

```bash
php artisan serve
```

## Testing

Run the test suite using PHPUnit:

```bash
php artisan test
```

## Technology Stack

* **Backend:** Laravel 12.x
* **Frontend:** Vite, Tailwind CSS 4.x
* **Database:** MySQL/PostgreSQL
* **Excel Processing:** FastExcel
* **Testing:** PHPUnit

## Troubleshooting

**1. `.env` not found or misconfigured**
Ensure you’ve created a `.env` file from `.env.example` and updated database credentials correctly.

**2. Permission errors on Linux/Mac**
Run the following to fix permission issues:

```bash
sudo chmod -R 775 storage bootstrap/cache
```

**3. Node build or Vite not working**
If frontend assets fail to compile, remove `node_modules` and reinstall:

```bash
rm -rf node_modules
npm install
npm run build
```

**4. Migration or seeding errors**
Run migrations fresh with seeders:

```bash
php artisan migrate:fresh --seed
```

**5. Cache issues after environment updates**
Clear Laravel caches to reflect config or route changes:

```bash
php artisan optimize:clear
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Built With

This application is built with [Laravel](https://laravel.com), the PHP framework for web artisans.
