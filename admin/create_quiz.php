<?php
include '../db_connection/database.php';

// Use the $connection variable from database.php
$conn = $connection;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_quiz':
                // Add new quiz
                $title = $conn->real_escape_string($_POST['title']);
                $description = $conn->real_escape_string($_POST['description']);
                $sql = "INSERT INTO quizzes (title, description, created_at) VALUES ('$title', '$description', NOW())";
                if ($conn->query($sql)) {
                    echo "<script>alert('Quiz added successfully!');</script>";
                }
                break;

            case 'add_question':
                // Add new question
                $quiz_id = $_POST['quiz_id'];
                $question_text = $conn->real_escape_string($_POST['question_text']);
                $sql = "INSERT INTO questions (quiz_id, question_text) VALUES ($quiz_id, '$question_text')";
                if ($conn->query($sql)) {
                    $question_id = $conn->insert_id;
                    
                    // Add choices
                    $choices = $_POST['choices'];
                    $correct_choice = $_POST['correct_choice'];
                    
                    foreach ($choices as $index => $choice) {
                        $choice_text = $conn->real_escape_string($choice);
                        $is_correct = ($index == $correct_choice) ? 1 : 0;
                        $sql = "INSERT INTO choices (question_id, choice_text, is_correct) 
                                VALUES ($question_id, '$choice_text', $is_correct)";
                        $conn->query($sql);
                    }
                    echo "<script>alert('Question added successfully!');</script>";
                }
                break;

            case 'update_question':
                // Update existing question
                $question_id = $_POST['question_id'];
                $question_text = $conn->real_escape_string($_POST['question_text']);
                $sql = "UPDATE questions SET question_text = '$question_text' WHERE id = $question_id";
                if ($conn->query($sql)) {
                    // Delete existing choices
                    $sql = "DELETE FROM choices WHERE question_id = $question_id";
                    $conn->query($sql);
                    
                    // Add new choices
                    $choices = $_POST['choices'];
                    $correct_choice = $_POST['correct_choice'];
                    
                    foreach ($choices as $index => $choice) {
                        $choice_text = $conn->real_escape_string($choice);
                        $is_correct = ($index == $correct_choice) ? 1 : 0;
                        $sql = "INSERT INTO choices (question_id, choice_text, is_correct) 
                                VALUES ($question_id, '$choice_text', $is_correct)";
                        $conn->query($sql);
                    }
                    echo "<script>alert('Question updated successfully!');</script>";
                }
                break;

            case 'delete_question':
                // Delete question and its choices
                $question_id = $_POST['question_id'];
                $sql = "DELETE FROM choices WHERE question_id = $question_id";
                $conn->query($sql);
                $sql = "DELETE FROM questions WHERE id = $question_id";
                if ($conn->query($sql)) {
                    echo "<script>alert('Question deleted successfully!');</script>";
                }
                break;
        }
    }
}

// Fetch existing quizzes
$quizzes = $conn->query("SELECT * FROM quizzes ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .choice-container {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Quiz Management</h2>
        
        <!-- Add New Quiz Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Add New Quiz</h4>
            </div>
            <div class="card-body">
                <form method="POST" id="addQuizForm">
                    <input type="hidden" name="action" value="add_quiz">
                    <div class="mb-3">
                        <label class="form-label">Quiz Title:</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description:</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Quiz</button>
                </form>
            </div>
        </div>

        <!-- Add New Question Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Add New Question</h4>
            </div>
            <div class="card-body">
                <form method="POST" id="addQuestionForm">
                    <input type="hidden" name="action" value="add_question">
                    <div class="mb-3">
                        <label class="form-label">Select Quiz:</label>
                        <select name="quiz_id" class="form-control" required>
                            <?php while ($quiz = $quizzes->fetch_assoc()): ?>
                                <option value="<?php echo $quiz['id']; ?>"><?php echo htmlspecialchars($quiz['title']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Question:</label>
                        <input type="text" name="question_text" class="form-control" required>
                    </div>
                    <div id="choicesContainer">
                        <div class="choice-container">
                            <input type="text" name="choices[]" class="form-control" placeholder="Choice 1" required>
                            <input type="radio" name="correct_choice" value="0" required> Correct Answer
                        </div>
                        <div class="choice-container">
                            <input type="text" name="choices[]" class="form-control" placeholder="Choice 2" required>
                            <input type="radio" name="correct_choice" value="1"> Correct Answer
                        </div>
                        <div class="choice-container">
                            <input type="text" name="choices[]" class="form-control" placeholder="Choice 3" required>
                            <input type="radio" name="correct_choice" value="2"> Correct Answer
                        </div>
                        <div class="choice-container">
                            <input type="text" name="choices[]" class="form-control" placeholder="Choice 4" required>
                            <input type="radio" name="correct_choice" value="3"> Correct Answer
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mb-3" onclick="addChoiceField()">Add Another Choice</button>
                    <button type="submit" class="btn btn-primary">Add Question</button>
                </form>
            </div>
        </div>

        <!-- Existing Quizzes and Questions -->
        <?php
        $quizzes->data_seek(0); // Reset quiz pointer
        while ($quiz = $quizzes->fetch_assoc()):
        ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h4><?php echo htmlspecialchars($quiz['title']); ?></h4>
                    <p class="mb-0"><?php echo htmlspecialchars($quiz['description']); ?></p>
                </div>
                <div class="card-body">
                    <?php
                    $questions = $conn->query("SELECT * FROM questions WHERE quiz_id = {$quiz['id']} ORDER BY id DESC");
                    while ($question = $questions->fetch_assoc()):
                    ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5>Question: <?php echo htmlspecialchars($question['question_text']); ?></h5>
                                
                                <?php
                                $choices = $conn->query("SELECT * FROM choices WHERE question_id = {$question['id']}");
                                while ($choice = $choices->fetch_assoc()):
                                ?>
                                    <div class="ms-3">
                                        <?php echo htmlspecialchars($choice['choice_text']); ?>
                                        <?php if ($choice['is_correct']): ?>
                                            <span class="badge bg-success">Correct Answer</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endwhile; ?>
                                
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-primary" onclick="editQuestion(<?php echo $question['id']; ?>)">Edit</button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="delete_question">
                                        <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        function addChoiceField() {
            const container = document.getElementById('choicesContainer');
            const choiceCount = container.children.length;
            
            const newChoice = document.createElement('div');
            newChoice.className = 'choice-container';
            newChoice.innerHTML = `
                <input type="text" name="choices[]" class="form-control" placeholder="Choice ${choiceCount + 1}" required>
                <input type="radio" name="correct_choice" value="${choiceCount}"> Correct Answer
            `;
            
            container.appendChild(newChoice);
        }

        function editQuestion(questionId) {
            // Implement edit functionality
            // You can create a modal or redirect to an edit page
            alert('Edit functionality to be implemented');
        }
    </script>
</body>
</html>
