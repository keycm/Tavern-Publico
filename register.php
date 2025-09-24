<?php
// Include the database connection file
require_once 'db_connect.php';

header('Content-Type: application/json'); // Set header to return JSON response

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? ''; // Raw password for hashing

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $response['message'] = 'Please fill in all fields.';
        echo json_encode($response);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format.';
        echo json_encode($response);
        exit;
    }

    // Check if username or email already exists
    $sql_check = "SELECT user_id FROM users WHERE username = ? OR email = ?";
    if ($stmt_check = mysqli_prepare($link, $sql_check)) {
        mysqli_stmt_bind_param($stmt_check, "ss", $param_username, $param_email);
        $param_username = $username;
        $param_email = $email;

        if (mysqli_stmt_execute($stmt_check)) {
            mysqli_stmt_store_result($stmt_check);
            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                $response['message'] = 'Username or Email already taken.';
                echo json_encode($response);
                mysqli_stmt_close($stmt_check);
                mysqli_close($link);
                exit;
            }
        } else {
            $response['message'] = 'Oops! Something went wrong with the check. Please try again later.';
            echo json_encode($response);
            mysqli_stmt_close($stmt_check);
            mysqli_close($link);
            exit;
        }
        mysqli_stmt_close($stmt_check);
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql_insert = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
    if ($stmt_insert = mysqli_prepare($link, $sql_insert)) {
        mysqli_stmt_bind_param($stmt_insert, "sss", $param_username, $param_email, $param_password_hash);
        $param_username = $username;
        $param_email = $email;
        $param_password_hash = $password_hash;

        if (mysqli_stmt_execute($stmt_insert)) {
            $response['success'] = true;
            $response['message'] = 'Registration successful! You can now log in.';
        } else {
            $response['message'] = 'Registration failed. Please try again.';
        }
        mysqli_stmt_close($stmt_insert);
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