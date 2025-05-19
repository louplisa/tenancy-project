# Tenancy Project

A multi-tenant Laravel 12 application using [stancl/tenancy v3](https://tenancyforlaravel.com/), built with Laravel Sail and Docker. This setup uses **separate databases** per tenant and includes Laravel Breeze for authentication and a local mail testing setup via Mailpit.

---

## 🚀 Features

- Laravel 12 + Laravel Breeze (Inertia, Vue, Tailwind)
- Multi-tenancy with `stancl/tenancy v3`
- Per-tenant MySQL databases
- Docker-based development via Laravel Sail
- Mail testing with Mailpit
- Easily add new tenants via artisan commands

---

## ⚙️ Requirements

- Docker Desktop (with WSL2 enabled if on Windows)
- Ports `82`, `3307`, and `8026` must be available
- Apache/Nginx must be stopped if using port `80`
- mkcert (optional) for HTTPS on localhost

---

## 🛠️ Installation Guide

### 1. Clone the Repository

```bash
git clone https://github.com/louplisa/tenancy-project.git
cd tenancy-project
```

### 2. Copy and Configure Environment File
```bash
cp .env.example .env
```

```dotenv
APP_NAME="Tenancy Project"
APP_PORT=82
FORWARD_DB_PORT=3307
FORWARD_MAILPIT_DASHBOARD_PORT=8026

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=central
DB_USERNAME=sail
DB_PASSWORD=password
```

### 3. Start Docker Containers
```bash
./vendor/bin/sail up -d
```

### 4. Install PHP Dependencies
```bash
./vendor/bin/sail composer install
```

### 5. Generate Application Key
```bash
./vendor/bin/sail artisan key:generate
```

### 6. Run Central Database Migrations
```bash
./vendor/bin/sail artisan migrate
```

### 7. Install and Compile Front-End Assets
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

## 🧪 Breeze Starter Kit
```bash
./vendor/bin/sail artisan breeze:install
./vendor/bin/sail npm install && ./vendor/bin/sail npm run build
```

## 🌐 Host Configuration
Edit your /etc/hosts file and add:
```bash
127.0.0.1 tenant1.localhost
127.0.0.1 tenant2.localhost
```

## 🧩 Create Tenants
```bash
./vendor/bin/sail artisan tenants:create tenant1 localhost
```
