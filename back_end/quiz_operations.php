<?php
session_start();
include '../db_connection/database.php';

class QuizOperations {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAdminName($admin_id) {
        $stmt = $this->conn->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $admin_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($admin = $result->fetch_assoc()) {
                return $admin['first_name'] . ' ' . $admin['last_name'];
            }
            $stmt->close();
        }
        return 'Admin';
    }

    public function addQuiz($title, $description) {
        $stmt = $this->conn->prepare("INSERT INTO quizzes (title, description, created_at) VALUES (?, ?, NOW())");
        if (!$stmt) {
            return ['success' => false, 'message' => 'Database error: ' . $this->conn->error];
        }

        $stmt->bind_param("ss", $title, $description);
        if (!$stmt->execute()) {
            return ['success' => false, 'message' => 'Error creating quiz: ' . $stmt->error];
        }

        $quiz_id = $this->conn->insert_id;
        $stmt->close();
        return ['success' => true, 'quiz_id' => $quiz_id];
    }

    public function addQuestion($quiz_id, $question, $answers, $correct_answer) {
        // First check if the quiz exists
        $check_stmt = $this->conn->prepare("SELECT quiz_id FROM quizzes WHERE quiz_id = ?");
        if (!$check_stmt) {
            return ['success' => false, 'message' => 'Database error: ' . $this->conn->error];
        }

        $check_stmt->bind_param("i", $quiz_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows === 0) {
            return ['success' => false, 'message' => 'Quiz not found'];
        }
        $check_stmt->close();

        // Start transaction
        $this->conn->begin_transaction();

        try {
            // Insert question
            $stmt = $this->conn->prepare("INSERT INTO questions (quiz_id, question) VALUES (?, ?)");
            if (!$stmt) {
                throw new Exception('Database error: ' . $this->conn->error);
            }

            $stmt->bind_param("is", $quiz_id, $question);
            if (!$stmt->execute()) {
                throw new Exception('Error adding question: ' . $stmt->error);
            }

            $question_id = $this->conn->insert_id;
            $stmt->close();

            // Insert answers
            foreach ($answers as $index => $answer) {
                $is_correct = ($index == $correct_answer) ? 1 : 0;
                $stmt = $this->conn->prepare("INSERT INTO answers (question_id, answer, is_correct) VALUES (?, ?, ?)");
                if (!$stmt) {
                    throw new Exception('Database error: ' . $this->conn->error);
                }

                $stmt->bind_param("isi", $question_id, $answer, $is_correct);
                if (!$stmt->execute()) {
                    throw new Exception('Error adding answer: ' . $stmt->error);
                }
                $stmt->close();
            }

            $this->conn->commit();
            return ['success' => true, 'question_id' => $question_id];

        } catch (Exception $e) {
            $this->conn->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getAllQuizzes() {
        $quizzes = $this->conn->query("SELECT * FROM quizzes ORDER BY created_at DESC");
        if (!$quizzes) {
            return ['success' => false, 'message' => 'Error fetching quizzes: ' . $this->conn->error];
        }
        return ['success' => true, 'quizzes' => $quizzes];
    }

    public function getQuizQuestions($quiz_id) {
        $stmt = $this->conn->prepare("
            SELECT q.id as question_id, q.question, 
                   GROUP_CONCAT(a.answer ORDER BY a.id) as answers,
                   GROUP_CONCAT(a.is_correct ORDER BY a.id) as correct_answers
            FROM questions q
            LEFT JOIN answers a ON q.id = a.question_id
            WHERE q.quiz_id = ?
            GROUP BY q.id
            ORDER BY q.id
        ");

        if (!$stmt) {
            return ['success' => false, 'message' => 'Database error: ' . $this->conn->error];
        }

        $stmt->bind_param("i", $quiz_id);
        if (!$stmt->execute()) {
            return ['success' => false, 'message' => 'Error fetching questions: ' . $stmt->error];
        }

        $result = $stmt->get_result();
        $questions = [];
        while ($row = $result->fetch_assoc()) {
            $questions[] = $row;
        }

        $stmt->close();
        return ['success' => true, 'questions' => $questions];
    }

    public function getQuestionCount($quiz_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as question_count FROM questions WHERE quiz_id = ?");
        if (!$stmt) {
            return ['success' => false, 'message' => 'Database error: ' . $this->conn->error];
        }

        $stmt->bind_param("i", $quiz_id);
        if (!$stmt->execute()) {
            return ['success' => false, 'message' => 'Error counting questions: ' . $stmt->error];
        }

        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['question_count'];
        $stmt->close();
        return ['success' => true, 'count' => $count];
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quizOps = new QuizOperations($conn);
    $response = ['success' => false, 'message' => 'Invalid request'];

    if (isset($_POST['add_quiz'])) {
        $response = $quizOps->addQuiz($_POST['title'], $_POST['description']);
    }
    elseif (isset($_POST['add_question'])) {
        $response = $quizOps->addQuestion(
            $_POST['quiz_id'],
            $_POST['question'],
            $_POST['answers'],
            $_POST['correct_answer']
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?> 