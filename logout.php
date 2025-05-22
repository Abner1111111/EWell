<?php
include 'DBconnection.php'; // Assuming this file initializes $connection
session_destroy();

// Redirect to the login page
header("Location: ./main/index.html");
exit();
?>