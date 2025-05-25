<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "ewell-php";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// This function checks if a table exists in the database
if (!function_exists('tableExists')) {
    function tableExists($conn, $tableName) {
        $result = $conn->query("SHOW TABLES LIKE '$tableName'");
        return $result && $result->num_rows > 0;
    }
}
?>