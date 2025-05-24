<?php include 'db.php'; ?>
<form method="post">
    <input type="text" name="title" placeholder="Quiz Title" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <button type="submit" name="create">Create Quiz</button>
</form>

<?php
if (isset($_POST['create'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $conn->query("INSERT INTO quizzes (title, description) VALUES ('$title', '$desc')");
    $quiz_id = $conn->insert_id;
    header("Location: add_question.php?quiz_id=$quiz_id");
}
?>