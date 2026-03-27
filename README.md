# Lookbook IT 🚀

A professional and modern IT Logbook application built with Laravel 10 and AdminLTE 3. Designed to streamline IT maintenance tracking, hardware/software logging, and cleaning activities with a focus on mobile responsiveness and user experience.

## ✨ Key Features
- **Dashboard Analytics**: Real-time stats (Total, Pending, Completed, Cancelled) and doughnut charts for category distribution.
- **Data Management**: Full CRUD for IT Logs with date filters, keyword search, and image preview.
- **SPA-like Experience**: Navigation powered by **Turbo** (Hotwire) for instant, no-reload page transitions.
- **Mobile Signature (QR Code)**: Unique workflow for "Pembersihan" category where mobile devices can scan a QR code to provide a digital signature on-site.
- **PWA Ready**: Installable on Android/iOS devices with a professional app icon and standalone display mode.
- **PDF Export**: Generate professional formatted PDF reports for any date range.
- **API Integration**: Dynamic unit/room selection filtered via a proxy API (bypass CORS).
- **User Management**: Secure login system using NIK (Employee ID) specifically for IT technicians.

## 🛠️ Technology Stack
- **Backend**: Laravel 10 (PHP 8.x)
- **Frontend UI**: AdminLTE 3 (Bootstrap 4)
- **Interactive**: jQuery, Select2, Chart.js, SweetAlert2
- **SPA/PWA**: Turbo, Service Workers, Web Manifest
- **Storage**: Laravel Public Disk (Storage Link)
- **PDF Engine**: DomPDF (via barryvdh/laravel-dompdf)

## 🚀 Installation & Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/mlenong/LoogbookIT.git
   cd LoogbookIT
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   # Update your .env with Database credentials
   php artisan key:generate
   ```

4. **Run Migrations & Seeding**
   ```bash
   php artisan migrate --seed
   ```

5. **Finalize Setup**
   ```bash
   php artisan storage:link
   ```

6. **Serve the Application**
   ```bash
   php artisan serve
   ```

## 📱 Mobile Installation (PWA)
1. Open the app in Chrome on your Android device.
2. Tap the three dots (⋮) and select **"Add to Home Screen"**.
3. The **Lookbook** app will now appear on your home screen as a standalone application.

---
Developed with ❤️ for IT Teams.
