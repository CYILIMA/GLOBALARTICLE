# GLOBAL ARTICLES

A professional news and documentary publishing platform built with Laravel 12. Features role-based access control, real-time notifications, subscriber management, comment moderation, and a full-featured admin dashboard.

---

## Table of Contents

- [Overview](#overview)
- [Tech Stack](#tech-stack)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Running the Application](#running-the-application)
- [User Roles](#user-roles)
- [Project Structure](#project-structure)
- [Database](#database)
- [License](#license)

---

## Overview

GLOBAL ARTICLES is a content management system (CMS) designed for news organizations and media publishers. It supports publishing articles and documentaries, managing subscribers, moderating comments, and tracking detailed analytics — all through a clean, role-based interface.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend Framework | Laravel 12 (PHP 8.2+) |
| Database | PostgreSQL |
| ORM | Eloquent |
| Frontend Styling | Tailwind CSS 3 |
| Reactivity | Alpine.js 3 |
| Rich Text Editor | CKEditor 5 |
| Asset Bundler | Vite 7 |
| HTTP Client | Axios |
| Queue / Sessions | Database-backed |

---

## Features

- **Article Management** — Create, schedule, draft, publish, and archive news articles with SEO metadata and image uploads
- **Breaking News** — Flag articles as breaking news; email blasts sent automatically to all active subscribers
- **Documentary Management** — Dedicated documentary section with video URL support and thumbnail uploads
- **Role-Based Access Control** — Three-tier role system (Admin, Editor, Author) with Laravel Policies enforcing every action
- **Comment Moderation** — Pending review queue, keyword filters, IP/email blocklist, and bulk actions
- **Notification System** — In-app notifications with unread counts for article approvals, comments, and system events
- **Subscriber Management** — Newsletter list with status tracking, welcome emails, unsubscribe support, and CSV export
- **Media Library** — Hierarchical folder structure for uploaded files, integrated with the rich text editor
- **Analytics Dashboard** — Total views, top articles, category statistics, and 7-day view trends (admin only)
- **Audit & Security** — Login history tracking (device, browser, IP) and full activity logs for all CRUD operations
- **Settings System** — Key-value configuration store for theme, social links, and contact info with caching

---

## Requirements

Before getting started, ensure your environment has the following:

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ and npm
- PostgreSQL 14+
- PHP extensions: `pdo_pgsql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`

---

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url> globalarticle
cd globalarticle
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and configure your PostgreSQL connection:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=global_articles
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 5. Create the PostgreSQL Database

```bash
psql -U postgres -c "CREATE DATABASE global_articles;"
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Link Storage for File Uploads

```bash
php artisan storage:link
```

### 8. Create the First Admin User

```bash
php artisan user:create-admin
```

---

## Running the Application

The application has two processes that must run simultaneously: the **Laravel backend** and the **Vite frontend dev server**.

### Option A — Run Everything at Once (Recommended for Development)

This single command starts the Laravel server, Vite dev server, queue worker, and log watcher concurrently:

```bash
composer run dev
```

Then open your browser at: **http://127.0.0.1:8000**

---

### Option B — Run Backend and Frontend Separately

**Terminal 1 — Backend (Laravel)**

```bash
php artisan serve
```

Starts the Laravel development server at `http://127.0.0.1:8000`.

**Terminal 2 — Frontend (Vite)**

```bash
npm run dev
```

Starts the Vite dev server with hot module replacement (HMR) for CSS and JS.

> Both terminals must stay open while developing. Vite injects assets into the page at runtime during development.

---

### Production Build

When deploying to production, compile and minify all frontend assets first:

```bash
npm run build
php artisan serve
```

Or point your web server (Nginx / Apache) to the `/public` directory.

---

## User Roles

| Role | Permissions |
|---|---|
| **Admin** | Full access — manage users, settings, analytics, moderation, all content |
| **Editor** | Create and publish articles & documentaries, moderate comments |
| **Author** | Create articles and documentaries; content goes to pending review before publishing |

### Author Publishing Workflow

```
Author submits article → Status: pending
         ↓
Admin receives in-app notification
         ↓
Admin reviews and publishes → Author notified
         ↓  (if marked as breaking news)
Email alert sent to all active subscribers
```

---

## Project Structure

```
globalarticle/
├── app/
│   ├── Console/Commands/       # Artisan commands (CreateAdminUser)
│   ├── Http/
│   │   ├── Controllers/        # Route controllers (News, Documentary, Admin, etc.)
│   │   ├── Middleware/         # AdminMiddleware for role enforcement
│   │   └── Requests/           # Form validation classes
│   ├── Mail/                   # Mailables (BreakingNewsAlert, WelcomeSubscriber)
│   ├── Models/                 # Eloquent models (14 models)
│   ├── Policies/               # Authorization policies (NewsPolicy, DocumentaryPolicy)
│   ├── Providers/              # AppServiceProvider with view composers
│   └── Services/               # NotificationService
├── config/
│   └── database.php            # PostgreSQL-only configuration
├── database/
│   ├── factories/              # Model factories for testing
│   └── migrations/             # 24 migration files
├── resources/
│   ├── css/                    # Tailwind base + custom theme variables
│   ├── js/                     # Alpine.js + CKEditor integration
│   └── views/                  # Blade templates
│       ├── admin/              # Dashboard, news, docs, users, settings, media
│       ├── auth/               # Login, password reset, email verification
│       ├── documentaries/      # Public documentary views
│       ├── emails/             # Email templates
│       ├── layouts/            # Main, admin, app, guest layouts
│       └── news/               # Public article views
├── routes/
│   ├── web.php                 # All application routes
│   └── auth.php                # Authentication routes
├── storage/
│   └── app/public/             # Uploaded images, videos, media
└── tests/                      # PHPUnit test suite
```

---

## Database

The application uses **PostgreSQL exclusively**. The database name is `global_articles` by default.

### Key Tables

| Table | Purpose |
|---|---|
| `users` | User accounts with role (admin / editor / author) |
| `news` | Articles with status, scheduling, SEO, and view tracking |
| `documentaries` | Documentary entries with video URL and thumbnail |
| `categories` | Content categories with slugs and colors |
| `comments` | Article comments with moderation workflow |
| `blocked_commenters` | Email and IP blocklist |
| `comment_filters` | Keyword-based comment filtering rules |
| `subscribers` | Newsletter subscribers with status tracking |
| `notifications` | In-app notification records |
| `login_history` | Login attempts with device and browser info |
| `activity_logs` | Audit trail for all user actions |
| `media` | Uploaded file metadata |
| `media_folders` | Hierarchical folder structure for media |
| `settings` | Key-value application configuration |
| `article_views` | Detailed per-IP view analytics |
| `sessions` | Database-backed session storage |

### Useful Commands

```bash
# Run all migrations
php artisan migrate

# Rollback last migration batch
php artisan migrate:rollback

# Fresh migration (drops all tables and re-runs)
php artisan migrate:fresh

# Clear application cache
php artisan config:clear
php artisan cache:clear
```

---

## License

This project is proprietary software. All rights reserved.
