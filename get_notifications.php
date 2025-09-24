<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

$response = ['success' => false, 'notifications' => []];

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Select notifications for the current user that have not been read (is_notified is false)
// and whose status is not 'Pending'.
$sql = "SELECT reservation_id, res_date, res_time, status FROM reservations WHERE user_id = ? AND status != 'Pending' AND is_notified = 0 ORDER BY created_at DESC";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $notifications = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $notifications[] = $row;
        }
        $response['success'] = true;
        $response['notifications'] = $notifications;
        mysqli_free_result($result);
    } else {
        error_log("Error executing notification query: " . mysqli_stmt_error($stmt));
    }
    mysqli_stmt_close($stmt);
} else {
    error_log("Error preparing notification query: " . mysqli_error($link));
}

mysqli_close($link);
echo json_encode($response);
?>