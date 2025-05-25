<?php
include '../db_connection/database.php';

// Read the SQL file
$sql = file_get_contents('../database/health_quiz_data.sql');

// Execute the SQL
if ($conn->multi_query($sql)) {
    echo "Health quiz setup completed successfully!";
} else {
    echo "Error setting up health quiz: " . $conn->error;
}

$conn->close();
?> 