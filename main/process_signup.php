<?php
require_once '../config/database.php';

// Start session
session_start();

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $firstName = sanitize_input($_POST['firstName']);
    $lastName = sanitize_input($_POST['lastName']);
    $birthDate = sanitize_input($_POST['birth_date']);
    $gender = sanitize_input($_POST['gender']);
    $email = sanitize_input($_POST['email']);
    $phoneNo = sanitize_input($_POST['phone_no']);
    $height = sanitize_input($_POST['height']);
    $weight = sanitize_input($_POST['weight']);
    $activityLevel = sanitize_input($_POST['activity_level']);
    $healthGoals = sanitize_input($_POST['health_goals']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: signup.php");
        exit();
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match";
        header("Location: signup.php");
        exit();
    }

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Email already exists";
        header("Location: signup.php");
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, birth_date, gender, email, phone_no, height, weight, activity_level, health_goals, password, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'User', NOW())");
    $stmt->bind_param("ssssssddsss", $firstName, $lastName, $birthDate, $gender, $email, $phoneNo, $height, $weight, $activityLevel, $healthGoals, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: signup.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // If not POST request, redirect to signup page
    header("Location: signup.php");
    exit();
}
?> 