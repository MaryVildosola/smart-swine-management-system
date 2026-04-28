# PorciTrack — Smart Pig Farm Management

PorciTrack is a comprehensive farm management system designed for modern piggery operations. It features real-time livestock tracking, inventory management, and an offline-first synchronization engine for field workers.

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

## Getting Started

1. **Clone the repository**
2. **Install dependencies**: `composer install` & `npm install`
3. **Setup environment**: `cp .env.example .env` & `php artisan key:generate`
4. **Run migrations**: `php artisan migrate`
5. **Start server**: `php artisan serve`

---
*For academic review and system testing.*
