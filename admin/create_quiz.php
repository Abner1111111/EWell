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
            case 'add':
                // Add new question
                $question = $conn->real_escape_string($_POST['question']);
                $sql = "INSERT INTO questions (question) VALUES ('$question')";
                if ($conn->query($sql)) {
                    $question_id = $conn->insert_id;
                    
                    // Add answers
                    $answers = $_POST['answers'];
                    $correct_answer = $_POST['correct_answer'];
                    
                    foreach ($answers as $index => $answer) {
                        $answer_text = $conn->real_escape_string($answer);
                        $is_correct = ($index == $correct_answer) ? 1 : 0;
                        $sql = "INSERT INTO answers (question_id, answer, is_correct) 
                                VALUES ($question_id, '$answer_text', $is_correct)";
                        $conn->query($sql);
                    }
                    echo "<script>alert('Question added successfully!');</script>";
                }
                break;

            case 'update':
                // Update existing question
                $question_id = $_POST['question_id'];
                $question = $conn->real_escape_string($_POST['question']);
                $sql = "UPDATE questions SET question = '$question' WHERE id = $question_id";
                if ($conn->query($sql)) {
                    // Delete existing answers
                    $sql = "DELETE FROM answers WHERE question_id = $question_id";
                    $conn->query($sql);
                    
                    // Add new answers
                    $answers = $_POST['answers'];
                    $correct_answer = $_POST['correct_answer'];
                    
                    foreach ($answers as $index => $answer) {
                        $answer_text = $conn->real_escape_string($answer);
                        $is_correct = ($index == $correct_answer) ? 1 : 0;
                        $sql = "INSERT INTO answers (question_id, answer, is_correct) 
                                VALUES ($question_id, '$answer_text', $is_correct)";
                        $conn->query($sql);
                    }
                    echo "<script>alert('Question updated successfully!');</script>";
                }
                break;

            case 'delete':
                // Delete question and its answers
                $question_id = $_POST['question_id'];
                $sql = "DELETE FROM answers WHERE question_id = $question_id";
                $conn->query($sql);
                $sql = "DELETE FROM questions WHERE id = $question_id";
                if ($conn->query($sql)) {
                    echo "<script>alert('Question deleted successfully!');</script>";
                }
                break;
        }
    }
}

// Fetch existing questions
$questions = $conn->query("SELECT * FROM questions ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .answer-container {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Quiz Management</h2>
        
        <!-- Add New Question Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Add New Question</h4>
            </div>
            <div class="card-body">
                <form method="POST" id="addQuestionForm">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Question:</label>
                        <input type="text" name="question" class="form-control" required>
                    </div>
                    <div id="answersContainer">
                        <div class="answer-container">
                            <input type="text" name="answers[]" class="form-control" placeholder="Answer 1" required>
                            <input type="radio" name="correct_answer" value="0" required> Correct Answer
                        </div>
                        <div class="answer-container">
                            <input type="text" name="answers[]" class="form-control" placeholder="Answer 2" required>
                            <input type="radio" name="correct_answer" value="1"> Correct Answer
                        </div>
                        <div class="answer-container">
                            <input type="text" name="answers[]" class="form-control" placeholder="Answer 3" required>
                            <input type="radio" name="correct_answer" value="2"> Correct Answer
                        </div>
                        <div class="answer-container">
                            <input type="text" name="answers[]" class="form-control" placeholder="Answer 4" required>
                            <input type="radio" name="correct_answer" value="3"> Correct Answer
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mb-3" onclick="addAnswerField()">Add Another Answer</button>
                    <button type="submit" class="btn btn-primary">Add Question</button>
                </form>
            </div>
        </div>

        <!-- Existing Questions -->
        <div class="card">
            <div class="card-header">
                <h4>Existing Questions</h4>
            </div>
            <div class="card-body">
                <?php while ($question = $questions->fetch_assoc()): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Question: <?php echo htmlspecialchars($question['question']); ?></h5>
                            
                            <?php
                            $answers = $conn->query("SELECT * FROM answers WHERE question_id = {$question['id']}");
                            while ($answer = $answers->fetch_assoc()):
                            ?>
                                <div class="ms-3">
                                    <?php echo htmlspecialchars($answer['answer']); ?>
                                    <?php if ($answer['is_correct']): ?>
                                        <span class="badge bg-success">Correct Answer</span>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile; ?>
                            
                            <div class="mt-3">
                                <button class="btn btn-sm btn-primary" onclick="editQuestion(<?php echo $question['id']; ?>)">Edit</button>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <script>
        function addAnswerField() {
            const container = document.getElementById('answersContainer');
            const answerCount = container.children.length;
            
            const newAnswer = document.createElement('div');
            newAnswer.className = 'answer-container';
            newAnswer.innerHTML = `
                <input type="text" name="answers[]" class="form-control" placeholder="Answer ${answerCount + 1}" required>
                <input type="radio" name="correct_answer" value="${answerCount}"> Correct Answer
            `;
            
            container.appendChild(newAnswer);
        }

        function editQuestion(questionId) {
            // Implement edit functionality
            // You can create a modal or redirect to an edit page
            alert('Edit functionality to be implemented');
        }
    </script>
</body>
</html>
