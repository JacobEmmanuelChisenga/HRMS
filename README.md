# HRMS

HRMS is a multi-tenant Human Resource Management System built with Laravel 12 for organizations that need employee administration, leave workflows, attendance tracking, document management, reporting, and company-level customization in a single platform.

The application supports both platform administration and company-specific tenant workspaces. Super Admin users manage the platform from the main domain, while company users access their workspace through tenant subdomains with isolated data and role-based permissions.

## Core Features

- Multi-tenant architecture with subdomain-based company isolation
- Platform and tenant authentication flows
- Role-based access control for 8 roles
- Employee records, departments, positions, and organizational chart
- Leave requests, approvals, balances, calendar, and Zambian leave policies
- QR-based attendance, clock in/out, late detection, and reports
- Document uploads, categorization, expiry tracking, and access logs
- Holiday management and announcements
- Company branding, landing page customization, and auth page customization
- Audit trails, activity logs, dashboards, and reports

## Roles Supported

- Super Admin
- Company Admin
- HR Manager
- Department Head
- Team Lead
- Employee
- Auditor
- IT Admin

## Tech Stack

- PHP 8.2+
- Laravel 12
- Blade templates
- Tailwind CSS
- Vite
- Alpine.js
- SQLite by default via `.env.example`
- Simple QR Code package for attendance QR generation

## Architecture Overview

### Platform Layer

The platform layer is intended for Super Admin users and includes:

- Company provisioning and management
- Subscription and billing views
- Platform user management
- System reports and settings
- Cross-tenant landing/auth page management

### Tenant Layer

Each company operates in its own tenant context with isolated data for:

- Employees
- Leave
- Attendance
- Documents
- Holidays
- Announcements
- Reports
- Company settings

## Quick Start

### Prerequisites

Make sure you have the following installed:

- PHP 8.2 or newer
- Composer
- Node.js 18+ and npm
- SQLite or another supported database

### Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
```

You can also use the bundled setup script:

```bash
composer setup
```

### Run the App

For local development:

```bash
composer dev
```

This starts:

- the Laravel development server
- the queue listener
- Laravel Pail for logs
- the Vite dev server

## Environment Notes

The default `.env.example` is configured for SQLite. If you prefer MySQL or another database, update the database connection values in `.env` before running migrations.

For local multi-tenant testing, make sure your environment supports subdomain-based access so tenant routes resolve correctly.

Optional environment variables for the seeded Super Admin account:

```env
SUPER_ADMIN_EMAIL=superadmin@hrms.test
SUPER_ADMIN_PASSWORD=password
```

## Database Seeding

By default, the main database seeder runs:

- `SuperAdminSeeder`
- `RolePermissionSeeder`

An additional `CompanyUserSeeder` exists for richer demo data, including:

- a demo company
- departments and positions
- employee profiles
- leave types
- document categories
- attendance settings
- demo tenant users

At the moment, `CompanyUserSeeder` is present but commented out in `database/seeders/DatabaseSeeder.php`. If you want full demo data, uncomment it and reseed the database.

## Demo Accounts

### Default Seeded Account

If you use the default seeders, the platform Super Admin account is:

- Email: `superadmin@hrms.test`
- Password: `password`

### Optional Demo Company Accounts

If `CompanyUserSeeder` is enabled, the following sample tenant users are created:

- `admin@democompany.com` / `password`
- `hr@democompany.com` / `password`
- `itmanager@democompany.com` / `password`
- `john.doe@democompany.com` / `password`
- `jane.smith@democompany.com` / `password`

## Main Modules

### Employee Management

- employee directory and profiles
- department and position management
- emergency and employment information
- organizational chart

### Leave Management

- leave request and approval workflow
- leave balances and adjustments
- leave calendar
- policy support for Zambian leave types

### Attendance

- QR code generation and rotation
- employee clock in/out
- manual attendance marking
- daily, monthly, and employee summary reporting

### Document Management

- categorized uploads
- download and access tracking
- expiry monitoring
- document request/report views

### Administration and Reporting

- announcements
- holiday management
- dashboards and reports
- activity logs and audits
- settings and branding

## Project Status

This project has an internal readiness assessment in `MVP_READINESS_ASSESSMENT.md`, which currently marks the system as ready for MVP presentation.

Known future enhancement areas noted in the assessment include:

- email and SMS notifications
- advanced analytics
- payroll integration
- performance management
- recruitment
- expanded automated testing

## Useful Commands

```bash
php artisan migrate --seed
php artisan test
npm run dev
npm run build
```

## Repository Structure

```text
app/                Application logic, controllers, models, middleware
bootstrap/          Framework bootstrap
config/             Application configuration
database/           Migrations, factories, seeders
public/             Public entry point and static assets
resources/          Blade views, CSS, JS
routes/             Web and auth routes
tests/              Automated tests
```

## License

This project is currently distributed under the MIT License through the Laravel application base unless you choose to apply a different project-specific license.
