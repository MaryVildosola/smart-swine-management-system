# SwineForge - Smart Farm Management System
Thank you for purchasing **SwineForge**! This document will guide you through setting up the project on your local server or production environment.

## 📌 Requirements
Before installing, ensure your server meets the following requirements:
- **PHP** >= 8.2
- **Composer** (Latest version)
- **Node.js** & **NPM**
- **MySQL** or **MariaDB**
- A Firebase Account (for Google/Firebase Authentication)

---

## 🚀 Installation Guide

### 1. Extract the Project
Unzip the downloaded `swineforge.zip` file into your server's web directory (e.g., `htdocs`, `www`, or your VPS folder).

### 2. Install Dependencies
Open your terminal/command prompt, navigate to the project directory, and run the following commands:
```bash
composer install
npm install
npm run build
```

### 3. Environment Configuration
Copy the `.env.example` file and rename it to `.env`:
```bash
cp .env.example .env
```
Open the `.env` file and update your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=swineforge
DB_USERNAME=root
DB_PASSWORD=your_password
```

Generate the application key:
```bash
php artisan key:generate
```

### 4. Database Setup
Create a new MySQL database named `swineforge` (or whatever you set in `.env`). 
You have two options to populate your database:

**Option A (Recommended): Import Dummy Data**
Import the `database-dummy-data.sql` file (included in the root folder) directly into your MySQL database using phpMyAdmin, HeidiSQL, or the command line. This will instantly populate the system with realistic data so you can see how it works!

**Option B: Fresh Install**
Run the following command to migrate the database structure without any dummy data:
```bash
php artisan migrate
```

### 5. Firebase Configuration (Mandatory)
SwineForge uses Firebase for authentication and real-time operations.
1. Go to the [Firebase Console](https://console.firebase.google.com/).
2. Create a new project.
3. Add a "Web App" to your project to get your Firebase configuration details.
4. Enable **Email/Password Authentication** in the Authentication section.
5. In your SwineForge code, navigate to `resources/views/auth/register.blade.php` and `layouts/guest.blade.php`, and replace the `firebaseConfig` object with your new credentials.

### 6. Run the Application
Start the local development server:
```bash
php artisan serve
```
Your application will be available at `http://localhost:8000`.

---

## 👨‍💻 Default Credentials
If you ran the seeders (`php artisan migrate --seed`), you can log in using the following default accounts:

**Admin Account:**
- Email: `admin@swineforge.com`
- Password: `password`

**Worker Account:**
- Email: `worker@swineforge.com`
- Password: `password`

---

## 🎨 Customizing the UI
The user interface leverages modern TailwindCSS and raw CSS. 
To modify the main brand colors or glassmorphism effects, edit the following files:
- `resources/views/layouts/worker.blade.php`
- `resources/views/pens/index.blade.php`
- `resources/views/landing.blade.php`

Recompile your assets after making changes:
```bash
npm run dev
```

---

**Need Help?** 
If you encounter any issues during setup, feel free to reach out to the author's support channel or consult the Laravel documentation.
