Bookly - Modern Online Bookstore
Bookly is a robust, full-featured B2C E-commerce application built with Native PHP 8, MySQL, and Bootstrap 5. It features advanced functionality including Google OAuth login, Email verification (SMTP), QR Code payments (VietQR), and a comprehensive Admin Dashboard with analytics.

✨ Features
🛒 User Features
Advanced Authentication:

Secure Registration & Login.

Google Login (OAuth 2.0) integration.

Email Verification via OTP/Link.

Forgot Password recovery system.

Smart Shopping:

Search & Filter: Real-time filtering by Author, Publisher, and Price.

Stock Management: Visual indicators for low stock or out-of-stock items.

Cart System: Dynamic cart updates and management.

Checkout: Streamlined checkout flow with address validation.

Payment Integration:

Cash on Delivery (COD).

VietQR Support: Auto-generate QR codes for bank transfers.

Content & Engagement:

Blog System: Read articles and news.

Author & Publisher Profiles: Dedicated pages to browse books by specific creators.

Dark Mode: Toggle between Light/Dark themes.

🛠️ Admin Features
Dashboard Analytics: Visual charts (Chart.js) for revenue, best-selling products, and user statistics.

Inventory Management:

CRUD Operations: Manage Books, Authors, Publishers, and Categories.

Bulk Import: Import products via CSV file.

Data Export: Export Orders, Users, and Products to CSV.

CMS: Built-in Blog editor (CKEditor 5).

Order Processing: Update payment statuses and manage delivery workflows.

🚀 Technologies Used
Backend: PHP 8.0+

Database: MySQL / MariaDB

Frontend: Bootstrap 5.3, FontAwesome 6, JavaScript.

Libraries (via Composer):

google/apiclient: Google OAuth 2.0.

phpmailer/phpmailer: SMTP Email sending.

vlucas/phpdotenv: Environment variable management.

Chart.js: Data visualization.

CKEditor 5: Rich text editor.

📦 Installation & Setup
Follow these steps to get the project running on your local machine.

1. Prerequisites
   PHP: Version 8.0 or higher.

Composer: Dependency manager for PHP.

MySQL: Local server (XAMPP/WAMP/Laragon).

2. Clone the Repository
   Bash

git clone https://github.com/hieu1704/book-store-app.git
cd bookly 3. Install Dependencies
This project uses Composer to manage libraries. Run the following command in the root directory:

Bash

composer install
This will create a vendor/ folder containing PHPMailer, Google Client, etc.

4. Database Setup
   Open phpMyAdmin (or your preferred SQL tool).

Create a new database named shop_db.

Import the provided SQL file:

File: shop_db.sql (Located in the root directory).

Check config.php: Ensure the connection settings match your local database user (default is usually root with no password).

PHP

// config.php
$conn = mysqli_connect('localhost', 'root', '', 'shop_db') or die('connection failed'); 5. Environment Configuration (.env)
This project uses a .env file to secure sensitive credentials.

Rename the file .env.example (if it exists) to .env, or create a new file named .env in the root directory.

Add the following configuration keys:

Ini, TOML

# Google Login Configuration

# Get these from https://console.cloud.google.com

GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here

# SMTP Email Configuration (for Forgot Password & Verification)

# Example using Gmail App Password

SMTP_HOST=smtp.gmail.com
SMTP_USER=your_email@gmail.com
SMTP_PASS="your_app_password_here"
SMTP_PORT=587
SMTP_SECURE=tls

# Sender Information

MAIL_FROM=your_email@gmail.com
MAIL_FROM_NAME="Bookly Bookstore" 6. Start the Server
If you are using XAMPP, ensure Apache and MySQL are running. Place the project folder in htdocs. Access the site via: http://localhost/bookly/home.php

📁 Project Structure
bookly/
├── .env # Environment variables (GIT IGNORED)
├── composer.json # Dependencies definition
├── config.php # Database connection
├── session*config.php # Secure session settings
├── vendor/ # Composer libraries (Auto-generated)
│
├── admin*_.php # Admin controllers (products, orders, stats...)
├── _.php # User controllers (home, shop, cart...)
│
├── uploaded_img/ # Product & Blog images
└── shop_db.sql # Database import file
🔑 Admin Credentials
(Default credentials if imported from shop_db.sql)

Email: lehieu17042004@gmail.com (or check the users table for user_type = 'admin')

Password: (Check the database or register a new admin via code logic)

📝 Notes for Developers
Google Login: To test this locally, ensure your Google Cloud Console "Authorized redirect URIs" includes http://localhost/bookly/google_login.php.

Email Sending: If using Gmail, you must enable "2-Step Verification" and generate an App Password to put in the .env file. Do not use your raw login password.

Made with ❤️ by Double H
