<?php
session_start();
include '../db_connection/database.php';
include '../back_end/session.php';

// Ensure the request is POST and user is admin
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_SESSION['role'] !== 'Admin') {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit;
}

// Get video ID from POST data
$video_id = isset($_POST['video_id']) ? intval($_POST['video_id']) : 0;

if ($video_id <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid video ID'
    ]);
    exit;
}

// Prepare and execute delete query
$stmt = $conn->prepare("DELETE FROM lecture_videos WHERE id = ?");
$stmt->bind_param("i", $video_id);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Video deleted successfully'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to delete video: ' . $conn->error
    ]);
}

$stmt->close();
$conn->close();
?> 