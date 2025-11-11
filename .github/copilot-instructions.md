# Copilot Instructions for Agape EDU CRM Laravel

## Project Overview
- **Purpose:** CRM system for educational institutions, built on Laravel 12.x (PHP 8.2+).
- **Major Components:**
  - `app/Models/`: Eloquent models (e.g., `User`, `Lead`, `Activity`, `Role`).
  - `app/Http/Controllers/`: Handles HTTP requests, business logic.
  - `app/Http/Middleware/`: Custom request middleware.
  - `app/Notifications/`: Laravel notification classes for events (e.g., lead assignment/status).
  - `database/migrations/`, `database/seeders/`, `database/factories/`: Schema, seed, and test data.
  - `resources/views/`: Blade templates, organized by feature (e.g., `leads/`, `users/`, `dashboard/`).
  - `public/`, `storage/`: Public assets and file storage.

## Key Workflows
- **Install dependencies:**
  - PHP: `composer install`
  - Node: `npm install`
- **Environment setup:**
  - Copy `.env.example` to `.env` and configure DB credentials.
  - Generate app key: `php artisan key:generate`
- **Database:**
  - Migrate: `php artisan migrate`
  - Seed: `php artisan db:seed`
  - Reset & reseed: `php artisan migrate:fresh --seed`
- **Frontend assets:**
  - Build: `npm run build`
  - Dev mode (hot reload): `npm run dev`
- **Run app:** `php artisan serve`
- **Testing:** `php artisan test`
- **Clear cache:** `php artisan optimize:clear`

## Project Conventions & Patterns
- **Role-based access:**
  - Roles and permissions managed via `Role` model and related migrations.
- **Notifications:**
  - Use Laravel's notification system for lead assignment/status changes (`app/Notifications/`).
- **UI:**
  - Tailwind CSS via Vite; Blade templates grouped by feature.
- **Excel import/export:**
  - Handled via FastExcel (see relevant controller logic).
- **Separation of concerns:**
  - Controllers for logic, Models for data, Views for UI.
- **Testing:**
  - Tests in `tests/Feature/` and `tests/Unit/`.

## Troubleshooting
- **PowerShell script errors:**
  - If `npm run build` fails due to execution policy, run: `Set-ExecutionPolicy -Scope CurrentUser RemoteSigned` in admin PowerShell.
- **Permission issues (Linux/Mac):**
  - `chmod -R 775 storage bootstrap/cache`
- **Cache/config issues:**
  - `php artisan optimize:clear`

## References
- See `README.md` for full setup and troubleshooting.
- Example migrations, models, and notifications in `app/` and `database/` folders.

---

*Update this file as project conventions evolve. For questions, review the README and key app folders.*
