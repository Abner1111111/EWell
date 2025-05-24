<?php include 'db.php';
$quiz_id = $_GET['quiz_id']; ?>
<h3>Add Question</h3>
<form method="post">
    <textarea name="question" placeholder="Question Text" required></textarea><br>
    <input type="text" name="option1" placeholder="Option 1" required><br>
    <input type="text" name="option2" placeholder="Option 2" required><br>
    <input type="text" name="option3" placeholder="Option 3" required><br>
    <input type="text" name="option4" placeholder="Option 4" required><br>
    <label>Correct Option:</label>
    <select name="correct">
        <option value="1">Option 1</option>
        <option value="2">Option 2</option>
        <option value="3">Option 3</option>
        <option value="4">Option 4</option>
    </select><br>
    <button type="submit" name="add">Add Question</button>
</form>

<?php
if (isset($_POST['add'])) {
    $qtext = $_POST['question'];
    $opts = [$_POST['option1'], $_POST['option2'], $_POST['option3'], $_POST['option4']];
    $correct = $_POST['correct'] - 1;

    $conn->query("INSERT INTO questions (quiz_id, question_text) VALUES ($quiz_id, '$qtext')");
    $qid = $conn->insert_id;

    foreach ($opts as $i => $opt) {
        $is_correct = ($i == $correct) ? 1 : 0;
        $conn->query("INSERT INTO options (question_id, option_text, is_correct) VALUES ($qid, '$opt', $is_correct)");
    }
    echo "Question added! <a href='add_question.php?quiz_id=$quiz_id'>Add another</a> or <a href='dashboard.php'>Go back</a>";
}
?>