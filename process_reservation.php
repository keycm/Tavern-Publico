<?php
session_start(); // Start session to get user_id if logged in
require_once 'db_connect.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $resDate = htmlspecialchars(trim($_POST['resDate'] ?? ''));
    $resTime = htmlspecialchars(trim($_POST['resTime'] ?? ''));
    $numGuests = filter_var(trim($_POST['numGuests'] ?? ''), FILTER_SANITIZE_NUMBER_INT);
    $resName = htmlspecialchars(trim($_POST['resName'] ?? ''));
    $resPhone = htmlspecialchars(trim($_POST['resPhone'] ?? ''));
    $resEmail = htmlspecialchars(trim($_POST['resEmail'] ?? ''));
    $status = "Pending"; // Default status for new reservations

    // Optional: Get user_id if a user is logged in
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Basic validation
    if (empty($resDate) || empty($resTime) || empty($numGuests) || empty($resName) || empty($resPhone) || empty($resEmail)) {
        header('Location: reserve.php?status=error&message=Missing required fields.');
        exit;
    }

    // Prepare an insert statement
    $sql = "INSERT INTO reservations (user_id, res_date, res_time, num_guests, res_name, res_phone, res_email, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // CORRECTED: The type definition string is now "ississss" for both cases.
        // i: user_id (integer)
        // s: res_date (string)
        // s: res_time (string)
        // i: num_guests (integer)
        // s: res_name (string)
        // s: res_phone (string)
        // s: res_email (string)
        // s: status (string)

        if ($user_id === null) {
            // If no user is logged in, bind a null variable for user_id
            $null_user_id = null;
            mysqli_stmt_bind_param($stmt, "ississss", $null_user_id, $resDate, $resTime, $numGuests, $resName, $resPhone, $resEmail, $status);
        } else {
            // If user is logged in, bind the user_id
            mysqli_stmt_bind_param($stmt, "ississss", $user_id, $resDate, $resTime, $numGuests, $resName, $resPhone, $resEmail, $status);
        }

        if (mysqli_stmt_execute($stmt)) {
            // Reservation successfully saved to database
            header('Location: reserve.php?status=success');
            exit;
        } else {
            // Error inserting into database
            error_log("Reservation insert error: " . mysqli_stmt_error($stmt)); // Log the actual error
            header('Location: reserve.php?status=error&message=Database insert failed.');
            exit;
        }

        mysqli_stmt_close($stmt);
    } else {
        // Error preparing the statement
        error_log("Reservation prepare error: " . mysqli_error($link)); // Log the actual error
        header('Location: reserve.php?status=error&message=Database preparation failed.');
        exit;
    }

    // Close database connection
    mysqli_close($link);

} else {
    // If accessed directly without POST method
    header('Location: reserve.php');
    exit;
}
?>

