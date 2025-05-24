<?php
include 'db.php';
session_start();

$quiz_id = $_GET['quiz_id'];
$user = $_SESSION['user'];

// Get user ID
$res = $conn->query("SELECT user_id FROM users WHERE username='$user'");
$row = $res->fetch_assoc();
$user_id = $row['user_id'];

// Check if user has already answered this quiz
$check = $conn->query("
    SELECT 1 FROM user_answers ua
    JOIN questions q ON ua.question_id = q.question_id
    WHERE ua.user_id = $user_id AND q.quiz_id = $quiz_id
    LIMIT 1
");

if ($check->num_rows > 0) {
    echo "<p>You have already taken this quiz.</p>";
    echo "<a href='result.php?user_id=$user_id&quiz_id=$quiz_id'>View Your Result</a>";
    exit;
}
?>

<!-- Quiz Form Display (if user hasn't answered yet) -->
<form method="post" action="submit_quiz.php">
    <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
    <?php
    $questions = $conn->query("SELECT * FROM questions WHERE quiz_id=$quiz_id");
    while ($q = $questions->fetch_assoc()) {
        echo "<h4>{$q['question_text']}</h4>";
        $opts = $conn->query("SELECT * FROM options WHERE question_id={$q['question_id']}");
        while ($opt = $opts->fetch_assoc()) {
            echo "<label><input type='radio' name='answers[{$q['question_id']}]' value='{$opt['option_id']}' required> {$opt['option_text']}</label><br>";
        }
    }
    ?>
    <button type="submit" name="submit">Submit Quiz</button>
</form>