# Team Activity Tracker — Laravel

A Laravel 10 web application for tracking the daily activities of an applications support team.

Built for **Npontu Technologies** platforms developer assessment.

## Features

1. **Daily Activity Dashboard** — View all activities for any date, grouped by category
2. **Status Updates** — Mark activities as Done or Pending with remarks
3. **Bio Capture** — Logged-in user's name, employee ID, and timestamp recorded on every update
4. **Handover View** — See who updated what and when for smooth shift handovers
5. **Reports** — Query activity history by custom date range, activity, team member, or status
6. **Authentication** — Secure login with hashed passwords and session management

## Stack

- **Framework:** Laravel 10 (PHP 8.2)
- **Database:** SQLite (zero-config, file-based)
- **Frontend:** Blade templates + Bootstrap 5
- **Auth:** Custom session-based authentication with bcrypt password hashing

## Local Development

```bash
# 1. Install PHP dependencies
composer install

# 2. Set up environment
cp .env.example .env
php artisan key:generate

# 3. Run migrations and seed default data
php artisan migrate
php artisan db:seed

# 4. Start the server
php artisan serve

# Open http://localhost:8000
```

**Default login:**
- Admin: `admin@company.com` / `Admin@1234`
- Member: `john.mensah@company.com` / `Member@1234`

## Deploy to Railway

1. Push this folder to a new GitHub repository.
2. Go to [railway.app](https://railway.app) → **New Project → Deploy from GitHub repo**.
3. Select the repo. Railway auto-detects PHP via `composer.json`.
4. Add these environment variables in Railway's **Variables** tab:

| Variable | Value |
|---|---|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | Run `php artisan key:generate --show` locally and paste here |
| `SESSION_SECURE_COOKIE` | `true` |

5. Add a **Railway Volume** mounted at `/app/storage` to persist sessions and database.
6. Then set `DB_DATABASE` to `/app/storage/database/tracker.db`.
7. Railway will run migrations and seed automatically on each deploy.

## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/       ← Auth, Dashboard, Logs, Activities, Users, Reports
│   │   └── Middleware/        ← AuthenticateSession, AdminMiddleware
│   ├── Models/                ← User, Activity, ActivityLog
│   └── Providers/
├── database/
│   ├── migrations/            ← users, activities, activity_logs tables
│   └── seeders/               ← Default admin, member, and activities
├── resources/views/
│   ├── layouts/app.blade.php  ← Sidebar layout
│   ├── auth/login.blade.php
│   ├── logs/                  ← daily, update, history
│   ├── activities/            ← index, form
│   ├── users/                 ← index, form
│   └── reports/index.blade.php
├── routes/web.php
├── railway.json
└── nixpacks.toml
```
