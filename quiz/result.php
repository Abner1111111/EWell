<?php include 'db.php';
$user_id = $_GET['user_id'];
$quiz_id = $_GET['quiz_id']; ?>
<h2>Your Score:</h2>

<?php
$score = $conn->query("
    SELECT COUNT(*) AS score
    FROM user_answers ua
    JOIN options o ON ua.selected_option_id = o.option_id
    JOIN questions q ON ua.question_id = q.question_id
    WHERE ua.user_id = $user_id AND o.is_correct = 1 AND q.quiz_id = $quiz_id
")->fetch_assoc();

$total = $conn->query("SELECT COUNT(*) AS total FROM questions WHERE quiz_id = $quiz_id")->fetch_assoc();

echo "<p>Correct: {$score['score']} / {$total['total']}</p>";
?>