# Teammate Setup Guide

If you are missing new features (like updated designs, database changes, or logic), follow these steps to sync your local environment.

## 🚀 One-Command Setup (Recommended)
This project includes a handy setup script in `composer.json`. Run this command to handle PHP dependencies, migrations, frontend builds, and `.env` setup automatically:

```bash
composer run setup
```

## 🛠 Manual Setup (If the above fails)

### 1. PHP Dependencies (Backend)
Install any new PHP packages added to `composer.json` by your teammates.
```bash
composer install
```

### 2. Database Migrations (Schema Changes)
Apply any new database tables or columns. This fixes "Column not found" errors.
```bash
php artisan migrate
```

### 3. JavaScript/CSS Assets (Frontend)
Install new Node packages and compile the Tailwind/JS assets. THIS IS OFTEN WHY THE UI LOOKS BROKEN OR OLD.
```bash
npm install
npm run build
```

### 4. Storage Linking (Images)
If images are missing or broken, ensure the storage symlink exists.
```bash
php artisan storage:link
```


## ⚠️ Troubleshooting Common Issues
- **"Vite manifest not found"**: Always run `npm run build`.
- **"Base table or view not found"**: Run `php artisan migrate`.
- **"Class not found"**: Run `composer dump-autoload`.
