# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - Initial Release (September 24, 2025)

This is the initial commit of the **Tavern Publico** web application. The application provides a comprehensive system for managing a tavern or restaurant, including customer reservations, an admin dashboard, a public-facing menu, and more.

### Added Files and Features in Initial Release:

**Public Website Pages:**
- `index.php`: The homepage welcoming users to Tavern Publico, featuring a hero section slideshow with images, a welcoming interface, and links to explore the tavern.
- `about.php`: An "Our Story" page detailing the tavern's history, founded in 2024, emphasizing craft food, hospitality, and locally-sourced ingredients.
- `contact.php`: Contact information including address, phone number, email, and a contact form for users to send messages directly to the tavern.
- `events.php`: A page dedicated to showcasing upcoming events at the tavern.
- `gallery.php`: A photo gallery designed to display images of the tavern's venue, food, and atmosphere.
- `menu.php`: The public-facing menu page allowing users to browse food and drink offerings. It likely utilizes the `partials/foodmenu.php` component.

**Customer Reservation System:**
- `reserve.php`: The main page for customers to initiate a table reservation.
- `reservation.php`: Additional UI or logic page for handling user reservations.
- `check_table_availability.php`: A backend script that checks if a specific table is available on a selected date and time to prevent double bookings.
- `process_reservation.php`: The backend script responsible for inserting a new customer reservation into the database after form submission.

**Admin Dashboard & Management:**
- `admin.php`: The central administrator dashboard providing an overview of system metrics and quick links to management functions.
- `customer_database.php`: A management interface for admins to view the list of registered customers and their details.
- `manage_user.php`: An administrative page for managing user accounts, potentially including roles or access control.
- `update.php`: A robust general update utility for administrators to modify various system records.
- `reports.php`: An analytics page allowing administrators to generate and view business reports based on reservation and user data.
- `get_customer_stats.php`: A backend endpoint that aggregates customer statistics (e.g., total users, active reservations) for use in the admin reports.
- `deletion_history.php`: A log page tracking records (like users or reservations) that have been deleted from the system.
- `manage_deletion.php`: An administrative tool specifically for managing or restoring deleted records.
- `update_reservation.php`: An interface for admins to edit the details of existing reservations.
- `update_reservation_status.php`: A specialized script for administrators to quickly change the status of a reservation (e.g., from 'Pending' to 'Confirmed' or 'Cancelled').

**Authentication & User Accounts:**
- `login.php`: The user login page allowing registered customers and administrators to access their accounts.
- `register.php`: The user registration page for new customers to create an account.
- `logout.php`: A simple script to terminate a user session and log them out of the system.

**Notifications System:**
- `get_notifications.php`: A backend script that retrieves unread or active notifications for logged-in users or administrators.
- `clear_notifications.php`: A script to mark viewed notifications as read or remove them from the notification queue.

**Database Configuration:**
- `tavern_publico (3).sql`: The complete SQL dump of the initial database schema. This includes tables like `blocked_slots` (for managing unavailable times/tables) and `reservations` (for storing booking details).
- `db_connect.php`: A reusable PHP script that establishes the connection to the MySQL database.
- `config.php`: A configuration file storing global settings and potentially database credentials.

**Frontend Assets (CSS/JS):**
- **CSS:** Extensive styling across `CSS/` and `partials/CSS/` directories. Notable files include `main.css` (global styles), `admin.css` (dashboard styles), `reservation.css` (booking form styles), and `menu.css` (menu layout styles).
- **JavaScript:** Dynamic functionality in the `JS/` directory. Files like `main.js` handle global interactions, while `reservation.js` provides client-side validation and dynamic behavior for booking forms. `admin.js` and `reports.js` add interactivity to the backend dashboard.

**Images and Media:**
- `logo.png`: The main logo for Tavern Publico.
- `Tavern.png`: A primary branding image or hero graphic.
- **Uploads (`uploads/`):** Contains images used throughout the site, particularly in the gallery, homepage slideshow, and menu:
  - `1st.jpg`: Used in the hero section slideshow on `index.php`.
  - `story.jpg`: Featured on the `about.php` page to accompany the tavern's history.
  - `Bagnet.jpg`, `Chicken.jpg`, `Liempo.jpg`, `Pork.jpg`: High-quality images of specific menu items.
  - `1-OS.jpg`, `2-OS.jpg`, `PEOPLE.jpg`, `Picture1.jpg`: Various images showcasing the venue, atmosphere, or events.
  - `Roblox-removebg-preview.png`, and several numbered `.jpg` files (e.g., `...05_12215..._n.jpg`): Additional uploaded media, possibly user avatars, event photos, or temporary assets.

**Partials / Shared Components:**
- `partials/header.php`: The shared navigation bar included at the top of public pages.
- `partials/footer.php`: The shared footer component containing copyright and secondary links.
- `partials/foodmenu.php`: A dedicated, reusable component specifically for rendering the food items and their details.
- `partials/Signin-Signup.php`: A reusable component containing the forms for both logging in and registering, likely used in a modal or split-page layout.
