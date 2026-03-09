# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - Initial Release

This is the initial commit of the **Tavern Publico** web application. The application provides a comprehensive system for managing a tavern or restaurant, including customer reservations, an admin dashboard, a public-facing menu, and more.

### Added
- **Public Website Pages:**
  - `index.php`: The homepage with a slideshow and welcoming interface.
  - `about.php`: Information about the tavern's history and mission.
  - `contact.php`: Contact information and form.
  - `events.php`: Information on upcoming events.
  - `gallery.php`: Photo gallery showcasing the venue and food.
  - `menu.php`: The public-facing food and drinks menu.

- **Customer Reservation System:**
  - `reserve.php` / `reservation.php`: Pages for customers to make table reservations.
  - `check_table_availability.php`: Checks if a specific table is available at a given time.
  - `process_reservation.php`: Handles the backend processing of booking a table.

- **Admin Dashboard & Management:**
  - `admin.php`: The main administrator dashboard.
  - `customer_database.php`: Page to view and manage registered customers.
  - `manage_user.php`: Page to manage user accounts.
  - `update.php`: General update functionality for the admin.
  - `reports.php`: Page to generate and view business reports.
  - `get_customer_stats.php`: Backend endpoint to retrieve customer statistics for reporting.
  - `deletion_history.php` & `manage_deletion.php`: Pages to track and manage deleted records.
  - `update_reservation.php` & `update_reservation_status.php`: Pages to modify existing reservations and their status (e.g., pending, confirmed, cancelled).

- **Authentication & User Accounts:**
  - `login.php`: User login page.
  - `register.php`: User registration page.
  - `logout.php`: User logout functionality.

- **Notifications System:**
  - `get_notifications.php`: Retrieves active notifications for users/admins.
  - `clear_notifications.php`: Clears viewed notifications.

- **Database:**
  - `tavern_publico (3).sql`: The initial database schema dump including tables for reservations, users, blocked slots, etc.
  - `db_connect.php` & `config.php`: Database connection configuration and logic.

- **Frontend Assets:**
  - Extensive CSS files in `CSS/` and `partials/CSS/` (`main.css`, `admin.css`, `reservation.css`, `menu.css`, etc.) for styling the application.
  - JavaScript files in `JS/` for dynamic functionality (`main.js`, `reservation.js`, `admin.js`, `reports.js`, etc.).
  - Image assets including `logo.png`, `Tavern.png`, and various images in the `uploads/` directory for the gallery and menu.

- **Partials / Shared Components:**
  - `partials/header.php`, `partials/footer.php`: Shared navigation and footer components.
  - `partials/foodmenu.php`: Reusable component for displaying the food menu.
  - `partials/Signin-Signup.php`: Reusable authentication component.
