<?php
session_start(); // Start the session at the very beginning
require_once 'db_connect.php'; // Include your database connection

// Check if the user is logged in AND is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['is_admin'] !== true) {
    header('Location: login.php'); // Redirect to login page if not logged in or not admin
    exit;
}

// Fetch reservations from the database for the main table
$reservations = [];
// FIXED: Added "WHERE deleted_at IS NULL" to only fetch active reservations
$sql_reservations = "SELECT reservation_id, user_id, res_date, res_time, num_guests, res_name, res_phone, res_email, status, created_at FROM reservations WHERE deleted_at IS NULL ORDER BY created_at DESC";
if ($result = mysqli_query($link, $sql_reservations)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reservations[] = $row;
    }
    mysqli_free_result($result);
} else {
    error_log("Admin page database error: " . mysqli_error($link));
}

// --- UPDATED: Fetch all stats for initial page load ---

// Summary Box Counts
$totalReservations = count($reservations);
$pendingReservations = count(array_filter($reservations, function($r) { return $r['status'] === 'Pending'; }));
$confirmedReservations = count(array_filter($reservations, function($r) { return $r['status'] === 'Confirmed'; }));
$cancelledReservations = count(array_filter($reservations, function($r) { return $r['status'] === 'Cancelled'; }));

// Weekly Customers
$sql_weekly = "SELECT SUM(num_guests) as weekly_total FROM reservations WHERE YEARWEEK(res_date, 1) = YEARWEEK(CURDATE(), 1) AND deleted_at IS NULL";
$weekly_result = mysqli_query($link, $sql_weekly);
$weeklyCustomers = $weekly_result ? (int)mysqli_fetch_assoc($weekly_result)['weekly_total'] : 0;

// Monthly Customers
$sql_monthly = "SELECT SUM(num_guests) as monthly_total FROM reservations WHERE MONTH(res_date) = MONTH(CURDATE()) AND YEAR(res_date) = YEAR(CURDATE()) AND deleted_at IS NULL";
$monthly_result = mysqli_query($link, $sql_monthly);
$monthlyCustomers = $monthly_result ? (int)mysqli_fetch_assoc($monthly_result)['monthly_total'] : 0;

// Reservations Today
$sql_today = "SELECT COUNT(reservation_id) as reservations_today FROM reservations WHERE res_date = CURDATE() AND deleted_at IS NULL";
$today_result = mysqli_query($link, $sql_today);
$reservationsToday = $today_result ? (int)mysqli_fetch_assoc($today_result)['reservations_today'] : 0;

// Total Registered Users
$sql_users = "SELECT COUNT(user_id) as total_users FROM users WHERE is_admin = 0 AND deleted_at IS NULL";
$users_result = mysqli_query($link, $sql_users);
$totalUsers = $users_result ? (int)mysqli_fetch_assoc($users_result)['total_users'] : 0;

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tavern Publico - Admin Dashboard</title>
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
                    <li class="menu-item active"><a href="admin.php"><i class="material-icons">dashboard</i> Dashboard</a></li>
                     <li class="menu-item"><a href="update.php"><i class="material-icons">file_upload</i> Upload Management</a></li>
                    <li class="menu-item"><a href="reservation.php"><i class="material-icons">event_note</i> Reservation</a></li>
                </ul>
                <div class="user-management-title">User Management</div>
                <ul class="sidebar-menu user-management-menu">
                    <li class="menu-item"><a href="#"><i class="material-icons">people</i> Notification Control</a></li>
                    <li class="menu-item"><a href="#"><i class="material-icons">security</i> Table Management</a></li>
                    <li class="menu-item"><a href="customer_database.php"><i class="material-icons">settings</i> Customer Database</a></li>
                    <li class="menu-item"><a href="reports.php"><i class="material-icons">analytics</i>Reservation Reports</a></li>
                    <li class="menu-item"><a href="deletion_history.php"><i class="material-icons">history</i> Deletion History</a></li>
                    <li class="menu-item"><a href="logout.php"><i class="material-icons">logout</i> Log out</a></li>
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
                <h1 class="dashboard-heading">Reservation Dashboard</h1>

                <section class="dashboard-summary">
                    <div class="summary-box total">
                        <h3>Total reservations</h3>
                        <p><?php echo $totalReservations; ?></p>
                        <div class="box-icon">📊</div>
                    </div>
                    <div class="summary-box pending">
                        <h3>Pending</h3>
                        <p><?php echo $pendingReservations; ?></p>
                        <div class="box-icon">🕒</div>
                    </div>
                    <div class="summary-box confirmed">
                        <h3>Confirmed</h3>
                        <p><?php echo $confirmedReservations; ?></p>
                        <div class="box-icon">✅</div>
                    </div>
                    <div class="summary-box cancelled">
                        <h3>Cancelled</h3>
                        <p><?php echo $cancelledReservations; ?></p>
                        <div class="box-icon">❌</div>
                    </div>
                </section>

                <section class="dashboard-stats-and-calendar">
                    <div class="dashboard-stats">
                        <div class="stat-box">
                            <h3>Total customer a weeks</h3>
                            <p id="weeklyCustomerCount"><?php echo $weeklyCustomers; ?></p> <div class="box-icon">📈</div>
                        </div>
                        <div class="stat-box">
                            <h3>Total customer a months</h3>
                            <p id="monthlyCustomerCount"><?php echo $monthlyCustomers; ?></p> <div class="box-icon">📈</div>
                        </div>
                        <div class="stat-box">
                            <h3>Reservations Today</h3>
                            <p id="reservationsTodayCount"><?php echo $reservationsToday; ?></p>
                            <div class="box-icon">📅</div>
                        </div>
                        <div class="stat-box">
                            <h3>Registered Users</h3>
                            <p id="totalUsersCount"><?php echo $totalUsers; ?></p>
                            <div class="box-icon">👥</div>
                        </div>
                    </div>
                    <div class="calendar-box">
                        <h3>Calendar</h3>
                        <div id="calendar"></div>
                    </div>
                </section>

                <section class="recent-reservations-section">
                    <h2>Recent reservations <input type="text" id="reservationSearchTop" class="search-input-top" placeholder="Search"></h2>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>CUSTOMER</th>
                                    <th>DATE</th>
                                    <th>TIME</th>
                                    <th>STATUS</th>
                                    <th>Info</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($reservations)): ?>
                                    <tr><td colspan="5">No reservations found.</td></tr>
                                <?php else: ?>
                                    <?php foreach (array_slice($reservations, 0, 5) as $reservation): // Limit to 5 most recent ?>
                                        <?php
                                            $statusClass = strtolower($reservation['status']);
                                            $displayData = [
                                                'Reservation ID' => $reservation['reservation_id'],
                                                'User ID' => $reservation['user_id'] ?? 'N/A',
                                                'Date' => $reservation['res_date'],
                                                'Time' => date("g:i A", strtotime($reservation['res_time'])),
                                                'Guests' => $reservation['num_guests'],
                                                'Name' => $reservation['res_name'],
                                                'Phone' => $reservation['res_phone'],
                                                'Email' => $reservation['res_email'],
                                                'Status' => $reservation['status'],
                                                'Booked At' => $reservation['created_at']
                                            ];
                                            $fullReservationJson = htmlspecialchars(json_encode($displayData), ENT_QUOTES, 'UTF-8');
                                        ?>
                                        <tr data-reservation-id="<?php echo $reservation['reservation_id']; ?>" data-full-reservation='<?php echo $fullReservationJson; ?>'>
                                            <td>
                                                <div class="customer-info">
                                                    <img src="images/default_avatar.png" alt="Customer Avatar" class="customer-avatar">
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($reservation['res_name']); ?></strong><br>
                                                        <small><?php echo htmlspecialchars($reservation['res_email']); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($reservation['res_date']); ?></td>
                                            <td><?php echo date("g:i A", strtotime($reservation['res_time'])); ?></td>
                                            <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($reservation['status']); ?></span></td>
                                            <td class="actions">
                                                <button class="btn btn-small view-btn">View</button>
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
                    <h2>Reservation Details</h2>
                    <div id="modalDetails"></div>
                    <div class="modal-actions">
                        <button class="btn btn-small modal-confirm-btn" data-status="Confirmed">Confirm</button>
                        <button class="btn btn-small modal-decline-btn" data-status="Declined">Decline</button>
                        <button class="btn btn-small modal-delete-btn">Delete</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="JS/admin.js"></script>
</body>
</html>