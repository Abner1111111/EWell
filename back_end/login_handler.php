<?php
session_start();
include '../db_connection/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password, role, first_name, last_name FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];

            // Redirect based on role
            if ($user['role'] === 'Admin') {
                header("Location: ../admin/index.php");
            } else {
                header("Location: ../user/dashboard.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid password";
            header("Location: ../main/login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "User not found";
        header("Location: ../main/login.php");
        exit();
    }
} else {
    header("Location: ../main/login.php");
    exit();
}
?> 