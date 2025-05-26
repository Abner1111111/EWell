<?php
session_start();
include '../db_connection/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    // Debug: Log the received email
    error_log("Login attempt for email: " . $email);

    // Validate input
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Please fill in all fields';
        header('Location: ../main/login.php');
        exit;
    }

    try {
        $checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE email = ?");
        if (!$checkStmt) {
            throw new Exception("Check user prepare error: " . $conn->error);
        }
        
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $userCount = $checkResult->fetch_assoc()['count'];
        $checkStmt->close();

        error_log("User count for email {$email}: {$userCount}");

        if ($userCount === 0) {
            $_SESSION['error'] = 'Invalid email or password';
            header('Location: ../main/login.php');
            exit;
        }

        $stmt = $conn->prepare("SELECT id, first_name, last_name, password, role FROM users WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Database prepare error: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) {
            throw new Exception("Database execute error: " . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            error_log("Stored password hash: " . $user['password']);
            error_log("Input password length: " . strlen($password));
            error_log("Stored password length: " . strlen($user['password']));
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['logged_in'] = true;
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    $expires = time() + (30 * 24 * 60 * 60);
                    $updateStmt = $conn->prepare("UPDATE users SET remember_token = ?, token_expires = ? WHERE id = ?");
                    if (!$updateStmt) {
                        throw new Exception("Token update prepare error: " . $conn->error);
                    }
                    
                    $updateStmt->bind_param("ssi", $token, date('Y-m-d H:i:s', $expires), $user['id']);
                    if (!$updateStmt->execute()) {
                        throw new Exception("Token update execute error: " . $updateStmt->error);
                    }
                    setcookie('remember_token', $token, $expires, '/', '', true, true);
                }
                if ($user['role'] === 'Admin') {
                    header('Location: ../admin/index.php');
                } else {
                    header('Location: ../user/dashboard.php');
                }
                exit;
            } else {
                // Log password verification failure
                error_log("Password verification failed for email: " . $email);
                $_SESSION['error'] = 'Invalid email or password';
            }
        } else {
            // Log user not found
            error_log("User not found for email: " . $email);
            $_SESSION['error'] = 'Invalid email or password';
        }
        $stmt->close();
        
    } catch (Exception $e) {
        // Log the error
        error_log("Login error: " . $e->getMessage());
        $_SESSION['error'] = 'An error occurred during login. Please try again.';
    }
    
    // Redirect back to login page with error
    header('Location: ../main/login.php');
    exit;
} else {
    // If not POST request, redirect to login page
    header('Location: ../main/login.php');
    exit;
}
?> 