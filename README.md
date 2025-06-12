# 🧪 Software Engineer Test – Laravel Backend

This is the backend implementation of the Software Engineer Test using **Laravel 12** and **PostgreSQL**. It dynamically loads a form structure from a JSON file (`submission.json`), stores it in a relational database, and exposes APIs to render and submit forms.

---

## ⚙️ Requirements

-   PHP 8.2+
-   Composer
-   PostgreSQL 13+
-   Laravel 12

---

## 🚀 Setup Instructions

### 1. Clone the repository

```bash
git clone https://github.com/afrodyy/vesperia-backend
cd vesperia-backend/backend
```

### 2. Install dependencies

```bash
composer install
```

### 3. Create `.env` file

```bash
cp .env.example .env
```

Edit your `.env` database config:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=vesperia_backend
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 4. Generate application key

```bash
php artisan key:generate
```

---

## 🗃️ Setup Database

### a. Create database manually

Make sure a database exists in PostgreSQL:

```sql
CREATE DATABASE software_test;
```

### b. Run migration and seeder

```bash
php artisan migrate --seed
```

Or to reset everything:

```bash
php artisan migrate:fresh --seed
```

The seeder will read from `database/data/submission.json` and store the form, fields, and options.

---

## 📁 submission.json

Form structure is imported from:

```
database/data/submission.json
```

You can replace this with any valid JSON structure that follows the original format.

---

## ▶️ Run the server

```bash
php artisan serve
```

---

## 🧪 Run Tests

```bash
php artisan test
```

Tests included:

-   ✅ `FormSeederTest` – checks that the form, fields, and options are seeded correctly.

---

## ✅ Done

Once you've seeded the database and run the server, you can use the frontend to dynamically fetch and display the form, as well as submit it.

Want to test with a frontend? Jump to the [React frontend README](../frontend/README.md) 🚀
