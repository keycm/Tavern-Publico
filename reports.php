<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

// --- Data Fetching for Reports ---

// Note: For a real-world application, you might add a 'source' column 
// to your reservations table (e.g., 'Online', 'Phone', 'Walk-in').
// Here we simulate it for demonstration purposes.
$sql = "SELECT *, 
        CASE 
            WHEN reservation_id % 3 = 0 THEN 'Phone'
            WHEN reservation_id % 3 = 1 THEN 'Online'
            ELSE 'Walk-in' 
        END AS source 
        FROM reservations";

$reservations_result = mysqli_query($link, $sql);
$all_reservations = [];
if ($reservations_result) {
    while ($row = mysqli_fetch_assoc($reservations_result)) {
        $all_reservations[] = $row;
    }
    mysqli_free_result($reservations_result);
}

// --- Prepare data for charts ---
// 1. Source of Business Data
$source_counts = array_count_values(array_column($all_reservations, 'source'));

// 2. Pacing Data (Simulated for this year vs. last year)
$pacing_this_year = ['Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0];
$pacing_last_year = ['Jan' => 5, 'Feb' => 8, 'Mar' => 12, 'Apr' => 15, 'May' => 10, 'Jun' => 18, 'Jul' => 22, 'Aug' => 20, 'Sep' => 25, 'Oct' => 28, 'Nov' => 30, 'Dec' => 40]; // Simulated data

foreach ($all_reservations as $res) {
    $month = date('M', strtotime($res['res_date']));
    if (isset($pacing_this_year[$month])) {
        $pacing_this_year[$month]++;
    }
}

// 3. Guest Demographics (New vs. Returning)
$guest_emails = array_column($all_reservations, 'res_email');
$guest_counts = array_count_values($guest_emails);
$new_guests = 0;
$returning_guests = 0;
foreach($guest_counts as $count) {
    if ($count == 1) {
        $new_guests++;
    } else {
        $returning_guests++;
    }
}

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tavern Publico - Reservation Reports</title>
    <link rel="stylesheet" href="CSS/admin.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .report-section { margin-bottom: 40px; }
        .report-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .report-header .export-options button { margin-left: 10px; }
        .chart-container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .date-filter { display: flex; gap: 15px; align-items: center; }
    </style>
</head>
<body>

<div class="page-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-header"><img src="Tavern.png" alt="Home Icon" class="home-icon"></div>
        <nav>
            <ul class="sidebar-menu">
                <li class="menu-item"><a href="admin.php"><i class="material-icons">dashboard</i> Dashboard</a></li>
                <li class="menu-item"><a href="update.php"><i class="material-icons">calendar_today</i> Upload Management</a></li>
                <li class="menu-item"><a href="reservation.php"><i class="material-icons">event_note</i> Reservation</a></li>
            </ul>
            <div class="user-management-title">User Management</div>
            <ul class="sidebar-menu user-management-menu">
                <li class="menu-item"><a href="#"><i class="material-icons">people</i> Notification Control</a></li>
                <li class="menu-item"><a href="#"><i class="material-icons">security</i> Table Management</a></li>
                <li class="menu-item"><a href="customer_database.php"><i class="material-icons">settings</i> Customer Database</a></li>
                <li class="menu-item active"><a href="reports.php"><i class="material-icons">analytics</i>Reservation Reports</a></li>
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
            <h1 class="dashboard-heading">Reservation Reports</h1>

            <!-- Pacing Report Section -->
            <section class="report-section content-card">
                <div class="report-header">
                    <h3>Pacing Report (This Year vs. Last Year)</h3>
                    <div class="export-options">
                        <button class="btn btn-small export-csv" data-target="pacingChart">Export CSV</button>
                        <button class="btn btn-small print-chart" data-target="pacingChart">Print</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="pacingChart"></canvas>
                </div>
            </section>

            <!-- Source of Business Report Section -->
            <section class="report-section content-card">
                <div class="report-header">
                    <h3>Source of Business</h3>
                    <div class="export-options">
                        <button class="btn btn-small export-csv" data-target="sourceChart">Export CSV</button>
                        <button class="btn btn-small print-chart" data-target="sourceChart">Print</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="sourceChart"></canvas>
                </div>
            </section>
            
            <!-- Guest Demographics Report Section -->
            <section class="report-section content-card">
                <div class="report-header">
                    <h3>Guest Demographics (New vs. Returning)</h3>
                    <div class="export-options">
                        <button class="btn btn-small export-csv" data-target="demographicsChart">Export CSV</button>
                        <button class="btn btn-small print-chart" data-target="demographicsChart">Print</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="demographicsChart"></canvas>
                </div>
            </section>

        </main>
    </div>
</div>

<script>
    const reportData = {
        pacing: {
            labels: <?= json_encode(array_keys($pacing_this_year)); ?>,
            thisYear: <?= json_encode(array_values($pacing_this_year)); ?>,
            lastYear: <?= json_encode(array_values($pacing_last_year)); ?>
        },
        source: {
            labels: <?= json_encode(array_keys($source_counts)); ?>,
            counts: <?= json_encode(array_values($source_counts)); ?>
        },
        demographics: {
            newGuests: <?= $new_guests; ?>,
            returningGuests: <?= $returning_guests; ?>
        }
    };
</script>
<script src="JS/reports.js"></script>
</body>
</html>
