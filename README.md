# SwineForge — Smart Pig Farm Management

SwineForge is a comprehensive farm management system designed for modern piggery operations. It features real-time livestock tracking, inventory management, and an offline-first synchronization engine for field workers.

## 🚀 Key Features
- **Live Analytics Dashboard**: 360° visibility over farm performance.
- **Offline-First Workflow**: Workers can log check-ins and medical data without internet.
- **Critical Alert System**: Immediate escalation of animal health emergencies.
- **Nutritional Management**: Feed formula building and inventory tracking.
- **QR Tracking**: Individual pig record access via mobile scanning.

## 🛠 Tech Stack
- **Backend**: Laravel 12 / PHP 8.4
- **Frontend**: Blade, Vanilla CSS, JavaScript (ES6+)
- **Database**: MySQL / MariaDB
- **Tools**: SweetAlert2, Boxicons, Mermaid.js

## 📚 Documentation
- **[Database Schema & Architecture](DATABASE.md)**: Detailed ERD and table references.

---

## 🛠 Setup Instructions

Follow these steps carefully to set up the project on your local machine:

1. **Clone the repository**
2. **Install Backend Dependencies**: 
   ```bash
   composer install
   ```
3. **Install Frontend Dependencies**:
   ```bash
   npm install
   ```
4. **Environment Setup**:
   - Create a copy of the environment file: `cp .env.example .env`
   - Generate application key: `php artisan key:generate`
   - **Important**: Create a database named `porcitrack` in your MySQL server (Laragon/XAMPP).
5. **Database Initialization**:
   - Run migrations and seeders:
     ```bash
     php artisan migrate:fresh --seed
     ```
     *Note: Avoid importing `structure.sql` or `backup.sql` directly as they may be outdated. Use migrations for the latest schema.*
6. **Compile Assets**:
   ```bash
   npm run build
   ```
7. **Start the Application**:
   ```bash
   php artisan serve
   ```

---

## 🔑 Default Credentials

After running `php artisan db:seed`, you can log in with:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin@porcitrack.com` | `admin123` |
| **Farm Worker** | `worker@porcitrack.com` | `password123` |

---

## ⚠️ Troubleshooting

- **403 Unauthorized**: This happens if your account doesn't have a role assigned. Use the credentials above or register a new account ensuring you select a role.
- **Vite/Manifest Error**: Run `npm install && npm run build` to ensure assets are compiled.
- **Missing Columns**: If you imported the SQL files instead of running migrations, the `users` table will be missing the `role` and `status` columns. Run `php artisan migrate:fresh` to fix this.

---
*For academic review and system testing.*
