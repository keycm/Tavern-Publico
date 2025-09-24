<?php
// Start output buffering to prevent "headers already sent" errors.
ob_start();

session_start(); // Start the session at the very beginning
require_once 'db_connect.php';

// Check if the user is logged in AND is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['is_admin'] !== true) {
    header('Location: login.php'); // Redirect to login page if not logged in or not admin
    exit;
}

// Replace these with your actual database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tavern_publico";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle image uploads and return the new filename
function uploadImage($file, $targetDir) {
    $targetFile = $targetDir . basename($file["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

    // Check if image file is a real image
    $check = getimagesize($file["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (e.g., 5MB)
    if ($file["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        return false;
    } else {
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return basename($file["name"]);
        } else {
            echo "Sorry, there was an error uploading your file.";
            return false;
        }
    }
}


// Function to sanitize input data
function sanitize($conn, $data) {
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($data)));
}

// --- Event Handling ---
if (isset($_POST['add_event'])) {
    $title = sanitize($conn, $_POST['event_title']);
    $date = sanitize($conn, $_POST['event_date']);
    $description = sanitize($conn, $_POST['event_description']);
    $image = '';

    if (!empty($_FILES['event_image']['name'])) {
        $image = 'uploads/' . uploadImage($_FILES['event_image'], "uploads/");
    }

    $sql = "INSERT INTO events (title, date, description, image) VALUES ('$title', '$date', '$description', '$image')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "New event added successfully.";
        $_SESSION['scroll_to'] = "events-section";
    } else {
        $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['update_event'])) {
    $id = sanitize($conn, $_POST['event_id']);
    $title = sanitize($conn, $_POST['event_title']);
    $date = sanitize($conn, $_POST['event_date']);
    $description = sanitize($conn, $_POST['event_description']);
    $image = '';
    $sql_image_part = "";

    if (!empty($_FILES['event_image']['name'])) {
        $image = 'uploads/' . uploadImage($_FILES['event_image'], "uploads/");
        $sql_image_part = ", image = '$image'";
    }

    $sql = "UPDATE events SET title = '$title', date = '$date', description = '$description' $sql_image_part WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Event updated successfully.";
        $_SESSION['scroll_to'] = "events-section";
    } else {
        $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['delete_event'])) {
    $id = sanitize($conn, $_POST['event_id']);
    $sql = "DELETE FROM events WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Event deleted successfully.";
        $_SESSION['scroll_to'] = "events-section";
    } else {
        $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// --- Gallery Handling ---
if (isset($_POST['add_gallery_image'])) {
    if (!empty($_FILES['gallery_image']['name'])) {
        $image = 'uploads/' . uploadImage($_FILES['gallery_image'], "uploads/");
        if ($image) {
            $sql = "INSERT INTO gallery (image) VALUES ('$image')";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "New gallery image added successfully.";
                $_SESSION['scroll_to'] = "gallery-section";
            } else {
                $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        $_SESSION['message'] = "No image selected.";
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['delete_gallery_image'])) {
    $id = sanitize($conn, $_POST['gallery_id']);
    $sql = "DELETE FROM gallery WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Gallery image deleted successfully.";
        $_SESSION['scroll_to'] = "gallery-section";
    } else {
        $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// --- Menu Handling ---
if (isset($_POST['add_menu_item'])) {
    $name = sanitize($conn, $_POST['menu_name']);
    $category = sanitize($conn, $_POST['menu_category']);
    $price = sanitize($conn, $_POST['menu_price']);
    $description = sanitize($conn, $_POST['menu_description']);
    $image = '';

    if (!empty($_FILES['menu_image']['name'])) {
        $image = 'uploads/' . uploadImage($_FILES['menu_image'], "uploads/");
    }

    $sql = "INSERT INTO menu (name, category, price, description, image) VALUES ('$name', '$category', '$price', '$description', '$image')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "New menu item added successfully.";
        $_SESSION['scroll_to'] = "menu-section";
    } else {
        $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['update_menu_item'])) {
    $id = sanitize($conn, $_POST['menu_id']);
    $name = sanitize($conn, $_POST['menu_name']);
    $category = sanitize($conn, $_POST['menu_category']);
    $price = sanitize($conn, $_POST['menu_price']);
    $image = '';
    $sql_image_part = "";

    if (!empty($_FILES['menu_image']['name'])) {
        $image = 'uploads/' . uploadImage($_FILES['menu_image'], "uploads/");
        $sql_image_part = ", image = '$image'";
    }

    $sql = "UPDATE menu SET name = '$name', category = '$category', price = '$price' $sql_image_part WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Menu item updated successfully.";
        $_SESSION['scroll_to'] = "menu-section";
    } else {
        $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['delete_menu_item'])) {
    $id = sanitize($conn, $_POST['menu_id']);
    $sql = "DELETE FROM menu WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Menu item deleted successfully.";
        $_SESSION['scroll_to'] = "menu-section";
    } else {
        $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch username from the session for display
// You'll need to set $_SESSION['username'] during login
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tavern Publico - Admin Dashboard</title>
    <link rel="stylesheet" href="CSS/admin.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* New CSS for the Content Management Section */

        /* Card-like container for content sections */
        .content-card {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        /* Headings inside content cards */
        .content-card h2, .content-card h3, .content-card h4 {
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }

        /* Form styling */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="file"],
        .form-group textarea,
        .form-group select,
        .form-group input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="date"]:focus,
        .form-group textarea:focus,
        .form-group select:focus,
        .form-group input[type="number"]:focus {
            border-color: #3498db;
            outline: none;
        }

        /* Data list styling */
        .data-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .data-item {
            background-color: #f9f9f9;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 6px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            position: relative;
            transition: box-shadow 0.3s ease;
        }

        .data-item:hover {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .data-item h4 {
            margin: 0;
            color: #3498db;
        }

        .data-item p {
            margin: 0;
            color: #666;
            font-size: 0.9em;
        }

        .data-item img {
            border-radius: 4px;
            max-width: 100%;
            height: auto;
            object-fit: cover;
        }

        /* Button Styling */
        button, .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button[type="submit"], .btn-primary {
            background-color: #27ae60;
            color: #fff;
        }

        button[type="submit"]:hover, .btn-primary:hover {
            background-color: #2ecc71;
            transform: translateY(-2px);
        }

        .delete-btn {
            background-color: #e74c3c;
            color: #830c0cff;
            font-size: 0.9em;
            padding: 8px 15px;
        }

        .delete-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        /* Grid layout for gallery images */
        .image-grid-admin {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        /* Grid layout for menu items */
        .menu-grid-admin {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        /* Style for individual menu items */
        .menu-item-admin {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .menu-item-admin img {
            align-self: center;
        }
        
        /* New styles for menu category navigation */
        .menu-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }
        
        .menu-nav-link {
            padding: 8px 15px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.9em;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        
        .menu-nav-link:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .category-items {
            display: none; /* Hide all categories by default */
        }

        .category-items.active {
            display: grid; /* Show the active category */
        }

        /* Initial display of all items */
        .show-all .category-items {
            display: grid;
        }
        
        /* Message Box Styling */
        .message-box {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 30px;
            background-color: #4CAF50;
            color: white;
            border-radius: 8px;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            font-weight: bold;
            display: none; /* Hidden by default */
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

    </style>
</head>
<body>

    <div class="page-wrapper">

        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <img src="Tavern.png" alt="Home Icon" class="home-icon">
            </div>
            <nav>
                <ul class="sidebar-menu">
                    <li class="menu-item">
                        <a href="admin.php"><i class="material-icons">dashboard</i> Dashboard</a>
                    </li>
                    <li class="menu-item active">
                        <a href="update.php"><i class="material-icons">calendar_today</i> Upload Management</a>
                    </li>
                    <li class="menu-item">
                        <a href="reservation.php"><i class="material-icons">event_note</i> Reservation</a>
                    </li>
                </ul>
                <div class="user-management-title">User Management</div>
                <ul class="sidebar-menu user-management-menu">
                    <li class="menu-item">
                        <a href="#"><i class="material-icons">people</i> Notification Control</a>
                    </li>
                    <li class="menu-item">
                        <a href="#"><i class="material-icons">security</i> Table Management</a>
                    </li>
                    <li class="menu-item">
                        <a href="#"><i class="material-icons">settings</i> Customer Database</a>
                    </li>
                    <li class="menu-item">
                        <a href="#"><i class="material-icons">settings</i>Reservation Reports</a>
                    </li>
                    <li class="menu-item">
                        <a href="logout.php"><i class="material-icons">logout</i> Log out</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="admin-content-area">
            <header class="main-header">
                <div class="header-content">
                    <div class="admin-header-right">
                        <img src="images/PEOPLE.jpg" alt="User Avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                        <span><?= $_SESSION['username']; ?></span>
                        <span class="admin-role">Admin</span>
                    </div>
                </div>
            </header>
            
            <main class="dashboard-main-content">
                <div id="message-box" class="message-box"></div>
                
                <h1 class="dashboard-heading">Content Management</h1>

                <!-- Events Section -->
                <section class="content-card" id="events-section">
                    <h2>Events</h2>
                    <h3>Add New Event</h3>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="event_title">Title:</label>
                            <input type="text" id="event_title" name="event_title" required>
                        </div>
                        <div class="form-group">
                            <label for="event_date">Date:</label>
                            <input type="date" id="event_date" name="event_date" required>
                        </div>
                        <div class="form-group">
                            <label for="event_description">Description:</label>
                            <textarea id="event_description" name="event_description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="event_image">Image:</label>
                            <input type="file" id="event_image" name="event_image">
                        </div>
                        <button type="submit" name="add_event">Add Event</button>
                    </form>

                    <h3>Existing Events</h3>
                    <div class="data-list">
                        <?php
                        $sql = "SELECT * FROM events ORDER BY date DESC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<div class='data-item'>";
                                echo "<h4>" . htmlspecialchars($row['title']) . "</h4>";
                                echo "<p><strong>Date:</strong> " . htmlspecialchars($row['date']) . "</p>";
                                echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                                if ($row['image']) {
                                    echo "<img src='" . htmlspecialchars($row['image']) . "' alt='Event Image' style='max-width: 150px;'>";
                                }
                                echo "<form action='' method='post' style='display:inline; margin-top: 10px;'>";
                                echo "<input type='hidden' name='event_id' value='" . $row['id'] . "'>";
                                echo "<button type='submit' name='delete_event' class='delete-btn'>Delete</button>";
                                echo "</form>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>No events found.</p>";
                        }
                        ?>
                    </div>
                </section>

                <hr>

                <!-- Gallery Section -->
                <section class="content-card" id="gallery-section">
                    <h2>Gallery</h2>
                    <h3>Add New Gallery Image</h3>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="gallery_image">Image:</label>
                            <input type="file" id="gallery_image" name="gallery_image" required>
                        </div>
                        <button type="submit" name="add_gallery_image">Add Image</button>
                    </form>

                    <h3>Existing Gallery Images</h3>
                    <div class="data-list image-grid-admin">
                        <?php
                        $sql = "SELECT * FROM gallery ORDER BY id DESC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<div class='data-item' style='flex-direction: column; align-items: center;'>";
                                echo "<img src='" . $row['image'] . "' alt='Gallery Image' style='max-width: 100%; height: auto;'>";
                                echo "<form action='' method='post' style='display:block; margin-top: 10px;'>";
                                echo "<input type='hidden' name='gallery_id' value='" . $row['id'] . "'>";
                                echo "<button type='submit' name='delete_gallery_image' class='delete-btn'>Delete</button>";
                                echo "</form>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>No gallery images found.</p>";
                        }
                        ?>
                    </div>
                </section>

                <hr>

                <!-- Menu Section -->
                <section class="content-card" id="menu-section">
                    <h2>Menu</h2>
                    <h3>Add New Menu Item</h3>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="menu_category">Category:</label>
                            <select id="menu_category" name="menu_category" required>
                                <option value="Specialty">Specialty</option>
                                <option value="Breakfast">All Day Breakfast</option>
                                <option value="Lunch">Ala Carte/For Sharing</option>
                                <option value="Sizzlers">Sizzling Plates</option>
                                <option value="Coffee">Cafe Drinks</option>
                                <option value="Cool Creations">Frappe</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="menu_name">Name:</label>
                            <input type="text" id="menu_name" name="menu_name" required>
                        </div>
                        <div class="form-group">
                            <label for="menu_description">Description:</label>
                            <textarea id="menu_description" name="menu_description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="menu_price">Price:</label>
                            <input type="number" id="menu_price" name="menu_price" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="menu_image">Image:</label>
                            <input type="file" id="menu_image" name="menu_image">
                        </div>
                        <button type="submit" name="add_menu_item">Add Menu Item</button>
                    </form>

                    <h3>Existing Menu Items</h3>
                    
                    <!-- New Menu Navigation -->
                    <nav class="menu-nav">
                        <a href="#all" class="menu-nav-link" data-category="all">View All</a>
                        <a href="#specialty" class="menu-nav-link" data-category="specialty">Specialty</a>
                        <a href="#breakfast" class="menu-nav-link" data-category="breakfast">All Day Breakfast</a>
                        <a href="#lunch" class="menu-nav-link" data-category="lunch">Ala Carte/For Sharing</a>
                        <a href="#sizzlers" class="menu-nav-link" data-category="sizzlers">Sizzling Plates</a>
                        <a href="#coffee" class="menu-nav-link" data-category="coffee">Cafe Drinks</a>
                        <a href="#creations" class="menu-nav-link" data-category="creations">Cool Creations</a>
                    </nav>

                    <div class="menu-container">
                        <?php
                        $sql = "SELECT * FROM menu ORDER BY category, id DESC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $current_category = "";
                            while($row = $result->fetch_assoc()) {
                                $category_id = strtolower(str_replace(' ', '-', $row['category']));
                                if ($row['category'] != $current_category) {
                                    if ($current_category != "") {
                                        echo "</div>"; // Close previous category div
                                    }
                                    echo "<h4 id='" . $category_id . "'>" . htmlspecialchars($row['category']) . "</h4>";
                                    echo "<div class='category-items menu-grid-admin' id='category-" . $category_id . "'>"; // Open new category div
                                    $current_category = $row['category'];
                                }
                                echo "<div class='data-item menu-item-admin'>";
                                echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
                                if ($row['image']) {
                                    echo "<img src='" . htmlspecialchars($row['image']) . "' alt='Menu Image' style='width: 100%; height: 200px; object-fit: cover;'>";
                                }
                                echo "<p><strong>Price:</strong> ₱" . number_format($row['price'], 2) . "</p>";
                                echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                                echo "<form action='' method='post' style='display:block; margin-top: 10px;'>";
                                echo "<input type='hidden' name='menu_id' value='" . $row['id'] . "'>";
                                echo "<button type='submit' name='delete_menu_item' class='delete-btn'>Delete</button>";
                                echo "</form>";
                                echo "</div>";
                            }
                            echo "</div>"; // Close the last category div
                        } else {
                            echo "<p>No menu items found.</p>";
                        }
                        ?>
                    </div>
                </section>
                
            </main>

        </div> 
    </div> 
    <script src="JS/admin.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navLinks = document.querySelectorAll('.menu-nav-link');
            const categoryContainers = document.querySelectorAll('.category-items');
            const menuContainer = document.querySelector('.menu-container');

            // Function to show all categories
            const showAllCategories = () => {
                categoryContainers.forEach(container => {
                    container.style.display = 'grid'; // Show all containers
                });
                const h4Elements = document.querySelectorAll('.menu-container h4');
                h4Elements.forEach(h4 => h4.style.display = 'block');
            };

            // Set the initial state to "View All"
            showAllCategories();

            navLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const category = e.target.getAttribute('data-category');

                    // If "View All" is clicked, show all categories
                    if (category === 'all') {
                        showAllCategories();
                        return;
                    }

                    // Hide all category containers
                    categoryContainers.forEach(container => {
                        container.style.display = 'none';
                    });

                    // Hide all category headings
                    const h4Elements = document.querySelectorAll('.menu-container h4');
                    h4Elements.forEach(h4 => h4.style.display = 'none');
                    
                    // Show the selected category container
                    const selectedContainer = document.getElementById(`category-${category}`);
                    if (selectedContainer) {
                        selectedContainer.style.display = 'grid';
                        // Show the corresponding heading
                        const headingId = selectedContainer.id.replace('category-', '');
                        const heading = document.getElementById(headingId);
                        if (heading) {
                            heading.style.display = 'block';
                        }
                    }
                });
            });

            // Check for a message from the server-side
            const messageBox = document.getElementById('message-box');
            <?php
            // Check for a message in the session
            if (isset($_SESSION['message'])) {
                echo "messageBox.textContent = '{$_SESSION['message']}';";
                echo "messageBox.style.display = 'block';";
                echo "setTimeout(() => { messageBox.style.opacity = '1'; }, 10);"; // Fade in
                echo "setTimeout(() => { messageBox.style.opacity = '0'; }, 3000);"; // Fade out after 3 seconds
                echo "setTimeout(() => { messageBox.style.display = 'none'; }, 3500);"; // Hide after fade out
                
                // Scroll to the correct section
                if (isset($_SESSION['scroll_to'])) {
                    echo "const section = document.getElementById('{$_SESSION['scroll_to']}');";
                    echo "if (section) { section.scrollIntoView({ behavior: 'smooth' }); }";
                }
                
                // Clear the session variables after use
                unset($_SESSION['message']);
                unset($_SESSION['scroll_to']);
            }
            ?>
        });
    </script>
</body>
</html>
<?php
$conn->close();
ob_end_flush();
?>
