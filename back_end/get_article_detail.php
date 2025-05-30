<?php
session_start();
include '../db_connection/database.php';
include 'session.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'No article ID provided']);
    exit;
}

$article_id = mysqli_real_escape_string($conn, $_GET['id']);

// Get article details
$query = "SELECT * FROM news_articles WHERE id = '$article_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $article = mysqli_fetch_assoc($result);
    
    // Clean the data before sending
    $clean_article = array(
        'id' => $article['id'],
        'title' => htmlspecialchars($article['title']),
        'category' => htmlspecialchars($article['category']),
        'content' => nl2br(htmlspecialchars($article['content'])), // Convert newlines to <br> tags
        'summary' => htmlspecialchars($article['summary']),
        'image_url' => htmlspecialchars($article['image_url']),
        'publication_date' => $article['publication_date'],
        'status' => htmlspecialchars($article['status'])
    );
    
    echo json_encode($clean_article);
} else {
    echo json_encode(['error' => 'Article not found']);
}
?> 