# Bookly — Modern Online Bookstore

Bookly is a lightweight, native PHP online bookstore built with PHP 8, MySQL/MariaDB and Bootstrap 5. It includes user authentication, email verification, password reset, cart & checkout, admin tools and a responsive UI.

---

## Quick demo / highlights

- Email verification and password reset (SMTP / PHPMailer)
- Google OAuth (optional)
- Cart, checkout, order history
- Admin dashboard with basic product management
- Dark mode toggle and responsive Bootstrap UI

---

## Tech stack

- PHP 8.x
- MySQL / MariaDB
- Bootstrap 5.3, Font Awesome
- Composer libraries:
  - phpmailer/phpmailer
  - vlucas/phpdotenv
  - google/apiclient (optional)

---

## Requirements

- PHP 8.0+
- Composer
- MySQL / MariaDB (XAMPP, WAMP, Laragon, etc.)
- Web server (Apache / Nginx)

---

## Installation

1. Clone repository

   ```
   git clone https://github.com/hieu1704/book-store-app.git
   cd book-store-app
   ```

2. Install PHP dependencies

   ```
   composer install
   ```

3. Database

   - Create database `shop_db`.
   - Import `shop_db.sql` using phpMyAdmin or mysql CLI:
     ```
     mysql -u root -p shop_db < shop_db.sql
     ```

4. Configuration

   - Copy `.env.example` to `.env` (or create `.env`) and set SMTP / Google values:

     ```
     SMTP_HOST=smtp.example.com
     SMTP_USER=you@example.com
     SMTP_PASS=your_smtp_password
     SMTP_PORT=587
     SMTP_SECURE=tls

     MAIL_FROM=you@example.com
     MAIL_FROM_NAME="Bookly Store"

     GOOGLE_CLIENT_ID=...
     GOOGLE_CLIENT_SECRET=...
     ```

   - Update `config.php` DB credentials if needed:
     ```php
     // config.php
     $conn = mysqli_connect('localhost', 'root', '', 'shop_db') or die('connection failed');
     ```

5. Place project in your web root (e.g. XAMPP `htdocs`) and open:
   ```
   http://localhost/bookly/
   ```

---

## Files & structure (important)

- config.php — DB connection
- session_config.php — secure session start
- header.php — shared header / dark-mode CSS
- forgot_password.php, reset_password.php — email reset flow
- vendor/ — Composer packages
- uploaded_img/ — product images
- shop_db.sql — DB dump

---

## Notes & troubleshooting

- If email sending fails, check `.env` SMTP settings and allow less-secure / app-password as needed for Gmail.
- If reset links show "invalid" or "expired":
  - Verify `reset_token` and `reset_expiry` are written in `users` table.
  - Ensure `reset_expiry` is stored as `Y-m-d H:i:s`.
- For local Google OAuth, set redirect URI in Google Console to:
  ```
  http://localhost/bookly/google_login.php
  ```
- Use `Ctrl+F5` to force-refresh CSS/JS when testing UI changes.

---

## Admin

- Default admin account may be present in `users` table (check `user_type = 'admin'`).
- Update admin credentials in DB or create an admin via registration then change `user_type`.

---

## Security tips

- Move to `password_hash()` instead of `md5()` for production passwords.
- Use HTTPS in production.
- Limit reset token lifetime and throttle forgot-password requests.

---

## License & credits

Made with ❤️. See project repo for license and contributor details.
