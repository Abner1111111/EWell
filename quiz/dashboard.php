<?php include 'db.php';
session_start(); ?>
<h2>Welcome, <?= $_SESSION['user'] ?></h2>
<a href="create_quiz.php">+ Create New Quiz</a><br><br>

<h3>Available Quizzes:</h3>
<ul>
    <?php
    $q = $conn->query("SELECT * FROM quizzes");
    while ($row = $q->fetch_assoc()) {
        echo "<li><a href='take_quiz.php?quiz_id={$row['quiz_id']}'>{$row['title']}</a></li>";
    }
    ?>
</ul>