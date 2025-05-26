<?php
include '../db_connection/database.php';

// Read the SQL file
$sql = file_get_contents('../database/create_health_quiz.sql');

// Execute the SQL
if ($conn->multi_query($sql)) {
    echo "Health quiz created successfully!";
} else {
    echo "Error creating health quiz: " . $conn->error;
}

$conn->close();
?> 