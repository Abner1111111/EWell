<?php
session_start();
include '../db_connection/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid data received']);
    exit;
}

$user_id = $_SESSION['user_id'];
$answers = $data['answers'];
$scores = $data['scores'];

try {
    // Start transaction
    $conn->begin_transaction();

    // Insert quiz result
    $sql = "INSERT INTO quiz_results (user_id, physical_score, mental_score, lifestyle_score, created_at) 
            VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $user_id, $scores['physical'], $scores['mental'], $scores['lifestyle']);
    $stmt->execute();
    
    $quiz_result_id = $conn->insert_id;

    // Insert answers
    $sql = "INSERT INTO quiz_answers (quiz_result_id, question_number, answer_value) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    foreach ($answers as $question_number => $answer) {
        $stmt->bind_param("iii", $quiz_result_id, $question_number, $answer);
        $stmt->execute();
    }

    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true, 'message' => 'Results saved successfully']);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error saving results: ' . $e->getMessage()]);
}

$conn->close();
?> 