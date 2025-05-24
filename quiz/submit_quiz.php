<?php include 'db.php';
session_start();
$user = $_SESSION['user']; ?>

<?php
$res = $conn->query("SELECT user_id FROM users WHERE username='$user'");
$row = $res->fetch_assoc();
$user_id = $row['user_id'];

foreach ($_POST['answers'] as $question_id => $option_id) {
    $conn->query("INSERT INTO user_answers (user_id, question_id, selected_option_id)
                  VALUES ($user_id, $question_id, $option_id)");
}

header("Location: result.php?user_id=$user_id&quiz_id={$_POST['quiz_id']}");
?>