<?php

include '../db_connection/database.php';
include '../back_end/quiz_operations.php';
include '../back_end/session.php';  

// Check if user is logged in


// Initialize QuizOperations
$quizOps = new QuizOperations($conn);

// Get admin name
$admin_name = $quizOps->getAdminName($_SESSION['user_id']);

// Get all quizzes
$quizzes_result = $quizOps->getAllQuizzes();
if (!$quizzes_result['success']) {
    die("Error: " . $quizzes_result['message']);
}
$quizzes = $quizzes_result['quizzes'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz - EWell Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_sidebar.css">
    <link rel="stylesheet" href="../css/admin_create_quiz.css">
</head>
<body>
<div class="dashboard-container">
    <?php include 'include/sidebar.php'; ?>

    <main class="main-content">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-heartbeat me-2"></i>EWell Admin
            </a>
            <a href=""></a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
                            <i class="fas fa-external-link-alt me-1"></i>View Site
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

  

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Create New Quiz -->
            <div class="col-md-6">
                <div class="card quiz-form">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Create New Quiz
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" name="add_quiz">
                            <div class="mb-3">
                                <label for="title" class="form-label">Quiz Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            <button type="submit" name="add_quiz" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Quiz
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Add Questions -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-question-circle me-2"></i>Add Questions
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" name="add_question">
                            <div class="mb-3">
                                <label for="quiz_id" class="form-label">Select Quiz</label>
                                <select class="form-control" id="quiz_id" name="quiz_id" required>
                                    <?php while ($quiz = $quizzes->fetch_assoc()): ?>
                                        <option value="<?php echo $quiz['quiz_id']; ?>"><?php echo htmlspecialchars($quiz['title']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="question" class="form-label">Question</label>
                                <input type="text" class="form-control" id="question" name="question" required>
                            </div>
                            <div class="mb-3">
                                <label for="points" class="form-label">Points</label>
                                <input type="number" class="form-control" id="points" name="points" min="1" max="100" value="1" required>
                                <div class="form-text">Enter the points for this question (1-100)</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Answers</label>
                                <?php for ($i = 0; $i < 4; $i++): ?>
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="answers[]" required>
                                        <div class="input-group-text">
                                            <input type="radio" name="correct_answer" value="<?php echo $i; ?>" required>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </div>
                            <button type="submit" name="add_question" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Question
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Quiz List -->
            <div class="col-md-6">
                <div class="card quiz-list">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Existing Quizzes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="quiz-list">
                            <?php
                            $quizzes->data_seek(0);
                            while ($quiz = $quizzes->fetch_assoc()):
                                // Get question count and total points
                                $stmt = $conn->prepare("SELECT COUNT(*) as question_count, SUM(points) as total_points FROM questions WHERE quiz_id = ?");
                                $stmt->bind_param("i", $quiz['quiz_id']);
                                if (!$stmt->execute()) {
                                    die("Error counting questions: " . $stmt->error);
                                }
                                $result = $stmt->get_result();
                                $quiz_stats = $result->fetch_assoc();
                                $question_count = $quiz_stats['question_count'];
                                $total_points = $quiz_stats['total_points'] ?? 0;

                                // Get all questions and answers for this quiz
                                $stmt = $conn->prepare("
                                    SELECT q.id as question_id, q.question, q.points,
                                           GROUP_CONCAT(a.answer ORDER BY a.id) as answers,
                                           GROUP_CONCAT(a.is_correct ORDER BY a.id) as correct_answers
                                    FROM questions q
                                    LEFT JOIN answers a ON q.id = a.question_id
                                    WHERE q.quiz_id = ?
                                    GROUP BY q.id
                                    ORDER BY q.id
                                ");
                                $stmt->bind_param("i", $quiz['quiz_id']);
                                $stmt->execute();
                                $questions_result = $stmt->get_result();
                            ?>
                                <div class="quiz-item">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?php echo htmlspecialchars($quiz['title']); ?></h6>
                                        <button class="btn btn-outline-primary btn-sm" type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#quiz-<?php echo $quiz['quiz_id']; ?>">
                                            <i class="fas fa-eye me-1"></i>View Questions
                                        </button>
                                    </div>
                                    <p class="mb-2 small text-muted"><?php echo htmlspecialchars($quiz['description']); ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-primary me-2">
                                                <i class="fas fa-question-circle me-1"></i><?php echo $question_count; ?> Questions
                                            </span>
                                            <span class="badge bg-success">
                                                <i class="fas fa-star me-1"></i><?php echo $total_points; ?> Points
                                            </span>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i><?php echo date('M d, Y', strtotime($quiz['created_at'])); ?>
                                        </small>
                                    </div>
                                    
                                    <!-- Collapsible Questions Section -->
                                    <div class="collapse mt-3" id="quiz-<?php echo $quiz['quiz_id']; ?>">
                                        <div class="card card-body">
                                            <?php while ($q = $questions_result->fetch_assoc()): 
                                                $answers = explode(',', $q['answers']);
                                                $correct_answers = explode(',', $q['correct_answers']);
                                            ?>
                                                <div class="question-item">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="mb-0">
                                                            <i class="fas fa-question-circle me-2"></i><?php echo htmlspecialchars($q['question']); ?>
                                                        </h6>
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-star me-1"></i><?php echo $q['points']; ?> Points
                                                        </span>
                                                    </div>
                                                    <div class="answers-list">
                                                        <?php foreach ($answers as $index => $answer): ?>
                                                            <div class="answer-item d-flex align-items-center">
                                                                <span class="me-2"><?php echo $index + 1; ?>.</span>
                                                                <span><?php echo htmlspecialchars($answer); ?></span>
                                                                <?php if ($correct_answers[$index] == 1): ?>
                                                                    <span class="badge bg-success ms-2">
                                                                        <i class="fas fa-check me-1"></i>Correct
                                                                    </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endwhile; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </section>
    </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        const navbar = document.querySelector('.navbar');
        const navbarBrand = document.querySelector('.navbar-brand');
        let isSidebarVisible = true;

        // Toggle sidebar when clicking the logo
        navbarBrand.addEventListener('click', function(e) {
            e.preventDefault();
            isSidebarVisible = !isSidebarVisible;
            toggleSidebar();
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', function(e) {
            if (isSidebarVisible && !sidebar.contains(e.target) && !navbarBrand.contains(e.target)) {
                isSidebarVisible = false;
                toggleSidebar();
            }
        });

        // Handle quiz creation
        const quizForm = document.querySelector('form[name="add_quiz"]');
        if (quizForm) {
            quizForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('add_quiz', '1');
                
                fetch('../back_end/quiz_operations.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Quiz created successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while creating the quiz.');
                });
            });
        }

        // Handle question addition
        const questionForm = document.querySelector('form[name="add_question"]');
        if (questionForm) {
            questionForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('add_question', '1');
                
                fetch('../back_end/quiz_operations.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Question added successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the question.');
                });
            });
        }
    });
    </script>
</body>
</html>
