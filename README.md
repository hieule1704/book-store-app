# Bookly - Online Bookstore

Bookly is a modern online bookstore web application built with PHP, MySQL, and Bootstrap 5. It provides a seamless experience for users to browse, search, and purchase books, as well as a robust admin panel for managing products, orders, users, and messages.

## Features

### User Features
- **User Authentication:** Register, login, and logout securely.
- **Product Catalog:** Browse and search for books with images, prices, and details.
- **Shopping Cart:** Add, update, and remove books from the cart.
- **Checkout:** Place orders with address and payment method.
- **Order History:** View all your placed orders.
- **Contact Form:** Send messages to the admin.

### Admin Features
- **Dashboard:** Overview of orders, users, products, and messages.
- **Product Management:** Add, update, and delete books.
- **Order Management:** View, update, and delete orders.
- **User Management:** View and delete user accounts.
- **Message Management:** View and delete contact messages.

### UI/UX
- **Responsive Design:** Fully responsive using Bootstrap 5.
- **Modern Look:** Clean, user-friendly interface with Font Awesome icons.
- **No Custom CSS:** All styling is handled by Bootstrap and CDN stylesheets.

## Technologies Used

- **Backend:** PHP 7+, MySQL
- **Frontend:** Bootstrap 5.3, Font Awesome 6
- **Database:** MySQL

## Getting Started

### Prerequisites

- PHP 7.x or higher
- MySQL
- Web server (e.g., Apache, Nginx)
- Composer (optional, for local development)

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/bookly.git
   cd bookly
   ```

2. **Import the database:**
   - Import the provided SQL file (`database.sql`) into your MySQL server.
   - Update `config.php` with your database credentials.

3. **Configure your web server:**
   - Point your web server’s document root to the project folder.

4. **Start using Bookly:**
   - Open your browser and go to `http://localhost/bookly` (or your configured domain).

## Folder Structure

```
project/
│
├── admin_contacts.php
├── admin_header.php
├── admin_orders.php
├── admin_page.php
├── admin_products.php
├── admin_users.php
├── cart.php
├── checkout.php
├── config.php
├── contact.php
├── footer.php
├── header.php
├── home.php
├── login.php
├── logout.php
├── orders.php
├── register.php
├── search_page.php
├── shop.php
├── about.php
├── uploaded_img/         # Uploaded product images
├── images/               # Static images (about, authors, etc.)
└── js/
    └── script.js         # (Optional) Custom JS for user pages
```

## Customization

- **Images:** Place your product and author images in the `uploaded_img/` and `images/` folders.
- **Branding:** Update the site name, logo, and footer in `header.php` and `footer.php`.
- **Contact Info:** Edit contact details in `footer.php`.

## Notes

- All styling is handled by Bootstrap 5 and Font Awesome via CDN.
- No custom CSS is required, but you can add your own if needed.
- Admin and user authentication is session-based.

## License

This project is open-source and available under the [MIT License](LICENSE).

---

**Made with ❤️ by Double H**
