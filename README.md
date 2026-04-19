# Team Activity Tracker — Laravel

A Laravel 10 web application for tracking the daily activities of an applications support team.

Built for **Npontu Technologies** platforms developer assessment.

The link to the deployed site : teamtracker-by-yenma-abambire-bawa-production.up.railway.app
## Default Login Accounts

| Role   | Email                        | Password     |
|--------|------------------------------|--------------|
| Admin  | admin@company.com            | Admin@1234   |
| Member | john.mensah@company.com      | Member@1234  |

> The **Admin** account can manage activities and team members. He can perform CRUD functionalities
> The **Member** account can update activity statuses and view reports.

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
