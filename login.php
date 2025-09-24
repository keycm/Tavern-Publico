<?php
session_start(); // Start the session at the very beginning
require_once 'db_connect.php';

header('Content-Type: application/json'); // Set header to return JSON response

$response = ['success' => false, 'message' => '', 'redirect' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = trim($_POST['username_email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username_or_email) || empty($password)) {
        $response['message'] = 'Please fill in both username/email and password.';
        echo json_encode($response);
        exit;
    }

    $user_id = null;
    $is_admin = false;
    $hashed_password = null;
    $db_username = null; // To store the actual username from DB

    // Prepare a select statement
    $sql = "SELECT user_id, username, password_hash, is_admin FROM users WHERE username = ? OR email = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $param_username_email, $param_username_email);
        $param_username_email = $username_or_email;

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $user_id, $db_username, $hashed_password, $is_admin);
                mysqli_stmt_fetch($stmt);

                if (password_verify($password, $hashed_password)) {
                    // Password is correct, start a new session
                    session_regenerate_id(true); // Regenerate session ID for security
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $db_username; // Store username in session
                    $_SESSION['is_admin'] = boolval($is_admin);

                    $response['success'] = true;
                    $response['message'] = 'Login successful!';

                    // Decide redirect based on user role (placeholder for future)
                    // For now, redirect to index.php or admin.php if the user is 'admin'
                    if ($_SESSION['is_admin']) { // You might want a more robust role management system
                        $response['redirect'] = 'admin.php';
                    } else {
                        $response['redirect'] = 'index.php'; // Redirect regular users to homepage
                    }

                } else {
                    // Password is not valid
                    $response['message'] = 'Invalid username/email or password.';
                }
            } else {
                // Username or email doesn't exist
                $response['message'] = 'Invalid username/email or password.';
            }
        } else {
            $response['message'] = 'Oops! Something went wrong. Please try again later.';
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        $response['message'] = 'Database error: Could not prepare statement.';
    }

    // Close connection
    mysqli_close($link);

} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>