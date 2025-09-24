<?php
session_start();
require_once 'db_connect.php'; // Include your database connection

// Check if the user is logged in AND is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['is_admin'] !== true) {
    header('Location: index.php');
    exit;
}

// Fetch all reservations from the database
$allReservations = [];
// FIXED: Added "WHERE deleted_at IS NULL" to only fetch active reservations
$sql = "SELECT reservation_id, user_id, res_date, res_time, num_guests, res_name, res_phone, res_email, status, created_at FROM reservations WHERE deleted_at IS NULL ORDER BY created_at DESC";

if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $allReservations[] = $row;
    }
    mysqli_free_result($result);
} else {
    error_log("Reservation page database error: " . mysqli_error($link));
    // Optionally, display an error message on the page
}

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tavern Publico - All Reservations</title>
    <link rel="stylesheet" href="CSS/admin.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
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
                     <li class="menu-item"><a href="update.php"><i class="material-icons">file_upload</i> Upload Management</a></li>
                    <li class="menu-item active">
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
                        <a href="customer_database.php"><i class="material-icons">settings</i> Customer Database</a>
                    </li>
                    <li class="menu-item">
                        <a href="reports.php"><i class="material-icons">settings</i>Reservation Reports</a>
                    </li>
                    <li class="menu-item"><a href="deletion_history.php"><i class="material-icons">history</i> Deletion History</a></li>
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
                        <span><?php echo $_SESSION['username']; ?></span>
                        <span class="admin-role">Admin</span>
                    </div>
                </div>
            </header>

            <main class="dashboard-main-content">
                <div class="reservation-page-header">
                    <h1>All Reservations</h1>
                    <input type="text" id="reservationSearch" class="search-input" placeholder="Search reservations...">
                    <button class="check-overall-availability-btn">Check Overall Availability</button>
                </div>

                <section class="all-reservations-section">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>CUSTOMER</th>
                                    <th>DATE</th>
                                    <th>TIME</th>
                                    <th>GUESTS</th>
                                    <th>PHONE</th>
                                    <th>STATUS</th>
                                    <th>BOOKED AT</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($allReservations)): ?>
                                    <tr><td colspan="8" style="text-align: center;">No reservations found.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($allReservations as $reservation): ?>
                                        <?php
                                            $statusClass = strtolower($reservation['status']);
                                            // Prepare data for the 'View/Edit' modal
                                            $fullReservationData = [
                                                'reservation_id' => $reservation['reservation_id'],
                                                'user_id' => $reservation['user_id'] ?? 'N/A',
                                                'res_date' => $reservation['res_date'],
                                                'res_time' => $reservation['res_time'],
                                                'num_guests' => $reservation['num_guests'],
                                                'res_name' => $reservation['res_name'],
                                                'res_phone' => $reservation['res_phone'],
                                                'res_email' => $reservation['res_email'],
                                                'status' => $reservation['status'],
                                                'created_at' => $reservation['created_at']
                                            ];
                                            $fullReservationJson = htmlspecialchars(json_encode($fullReservationData), ENT_QUOTES, 'UTF-8');
                                        ?>
                                        <tr data-reservation-id="<?php echo $reservation['reservation_id']; ?>" data-full-reservation='<?php echo $fullReservationJson; ?>'>
                                            <td>
                                                <div class="customer-info">
                                                    <img src="images/default_avatar.png" alt="Customer Avatar" class="customer-avatar">
                                                    <div class="customer-name-email">
                                                        <strong><?php echo htmlspecialchars($reservation['res_name']); ?></strong><br>
                                                        <small><?php echo htmlspecialchars($reservation['res_email']); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($reservation['res_date']); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['res_time']); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['num_guests']); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['res_phone']); ?></td>
                                            <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($reservation['status']); ?></span></td>
                                            <td><?php echo htmlspecialchars($reservation['created_at']); ?></td>
                                            <td class="actions">
                                                <button class="btn btn-small view-edit-btn">View/Edit</button>
                                                <button class="btn btn-small delete-btn">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>

            <div id="reservationModal" class="modal">
                <div class="modal-content">
                    <span class="close-button">&times;</span>
                    <h2>Reservation Details & Edit</h2>
                    <form id="editReservationForm">
                        <input type="hidden" id="modalReservationId" name="reservation_id">

                        <div class="form-group">
                            <label for="modalResName">Customer Name:</label>
                            <input type="text" id="modalResName" name="res_name" required>
                        </div>
                        <div class="form-group">
                            <label for="modalResEmail">Email:</label>
                            <input type="email" id="modalResEmail" name="res_email" required>
                        </div>
                        <div class="form-group">
                            <label for="modalResPhone">Phone:</label>
                            <input type="tel" id="modalResPhone" name="res_phone">
                        </div>
                        <div class="form-group">
                            <label for="modalResDate">Date:</label>
                            <input type="date" id="modalResDate" name="res_date" required>
                        </div>
                        <div class="form-group">
                            <label for="modalResTime">Time:</label>
                            <input type="time" id="modalResTime" name="res_time" required>
                        </div>
                        <div class="form-group">
                            <label for="modalNumGuests">Number of Guests:</label>
                            <input type="number" id="modalNumGuests" name="num_guests" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="modalStatus">Status:</label>
                            <select id="modalStatus" name="status">
                                <option value="Pending">Pending</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Declined">Declined</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="modalCreatedAt">Booked At:</label>
                            <input type="text" id="modalCreatedAt" name="created_at" readonly>
                        </div>
                        <div class="modal-actions">
                            <button type="submit" class="btn modal-save-btn">Save Changes</button>
                            <button type="button" class="btn modal-delete-btn">Delete Reservation</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="availabilityModal" class="modal">
                <div class="modal-content">
                    <span class="close-button">&times;</span>
                    <h2>Check Table Availability</h2>
                    <form id="checkAvailabilityForm">
                        <div class="form-group">
                            <label for="checkDate">Date:</label>
                            <input type="date" id="checkDate" name="check_date" required>
                        </div>
                        <div class="form-group">
                            <label for="checkTime">Time:</label>
                            <input type="time" id="checkTime" name="check_time" required>
                        </div>
                        <div class="form-group">
                            <label for="checkNumGuests">Number of Guests:</label>
                            <input type="number" id="checkNumGuests" name="check_num_guests" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Check Availability</button>
                    </form>
                    <div id="availabilityResult" class="availability-result" style="display: none;">
                        </div>
                </div>
            </div>

        </div>
    </div>

    <script src="JS/reservation.js"></script>
</body>
</html>