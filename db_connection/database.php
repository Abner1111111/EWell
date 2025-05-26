<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'ewell-php'; // Make sure this matches your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to ensure proper encoding
if (!$conn->set_charset("utf8mb4")) {
    error_log("Error setting charset: " . $conn->error);
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log successful connection
error_log("Successfully connected to database: " . $database);

// This function checks if a table exists in the database
if (!function_exists('tableExists')) {
    function tableExists($conn, $tableName) {
        $result = $conn->query("SHOW TABLES LIKE '$tableName'");
        return $result && $result->num_rows > 0;
    }
}

// Check if users table exists
if (!tableExists($conn, 'users')) {
    error_log("Warning: users table does not exist in database: " . $database);
}
?>