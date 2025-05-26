<?php
session_start();
include '../db_connection/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch the health quiz
$sql = "SELECT * FROM quizzes WHERE title = 'Health Assessment' LIMIT 1";
$result = $conn->query($sql);
$quiz = $result->fetch_assoc();

if (!$quiz) {
    die("Health quiz not found. Please contact the administrator.");
}

// Fetch questions for the quiz
$sql = "SELECT q.*, GROUP_CONCAT(a.answer ORDER BY a.id) as options, 
        GROUP_CONCAT(a.is_correct ORDER BY a.id) as correct_answers
        FROM questions q 
        LEFT JOIN answers a ON q.id = a.question_id 
        WHERE q.quiz_id = ?
        GROUP BY q.id
        ORDER BY q.id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $quiz['quiz_id']);
$stmt->execute();
$questions_result = $stmt->get_result();

$quizQuestions = [];
while ($row = $questions_result->fetch_assoc()) {
    $options = explode(',', $row['options']);
    $correct_answers = explode(',', $row['correct_answers']);
    
    // Determine category based on question content
    $category = 'physical'; // default category
    $question_lower = strtolower($row['question']);
    
    if (strpos($question_lower, 'stress') !== false || 
        strpos($question_lower, 'anxious') !== false || 
        strpos($question_lower, 'mindfulness') !== false) {
        $category = 'mental';
    } elseif (strpos($question_lower, 'work') !== false || 
              strpos($question_lower, 'hobby') !== false || 
              strpos($question_lower, 'vacation') !== false) {
        $category = 'lifestyle';
    }
    
    $quizQuestions[] = [
        'question' => $row['question'],
        'options' => $options,
        'category' => $category
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EWell - Health Quiz</title>
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/variables.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/health_quiz.css">
    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include 'includes/header.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="quiz-content">
                <!-- Quiz Introduction -->
                <section class="quiz-intro" id="quizIntro">
                    <div class="intro-card">
                        <i class="fas fa-clipboard-check"></i>
                        <h2><?php echo htmlspecialchars($quiz['title']); ?></h2>
                        <p><?php echo htmlspecialchars($quiz['description']); ?></p>
                        <div class="quiz-info">
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <span>5-10 minutes</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-list-check"></i>
                                <span><?php echo count($quizQuestions); ?> questions</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>Private & Secure</span>
                            </div>
                        </div>
                        <button class="start-quiz-btn" onclick="startQuiz()">
                            Start Assessment
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </section>

                <!-- Quiz Questions -->
                <section class="quiz-questions" id="quizQuestions" style="display: none;">
                    <div class="progress-bar">
                        <div class="progress" id="quizProgress"></div>
                    </div>
                    <div class="question-container">
                        <div class="question-number">Question <span id="currentQuestion">1</span> of <?php echo count($quizQuestions); ?></div>
                        <div class="question" id="questionText"></div>
                        <div class="options" id="questionOptions"></div>
                        <div class="navigation-buttons">
                            <button class="nav-btn prev-btn" onclick="previousQuestion()" disabled>
                                <i class="fas fa-arrow-left"></i>
                                Previous
                            </button>
                            <button class="nav-btn next-btn" onclick="nextQuestion()">
                                Next
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </section>

                <!-- Quiz Results -->
                <section class="quiz-results" id="quizResults" style="display: none;">
                    <div class="results-card">
                        <i class="fas fa-chart-pie"></i>
                        <h2>Your Health Assessment Results</h2>
                        <div class="results-summary">
                            <div class="result-category">
                                <h3>Physical Health</h3>
                                <div class="score" id="physicalScore">0%</div>
                            </div>
                            <div class="result-category">
                                <h3>Mental Health</h3>
                                <div class="score" id="mentalScore">0%</div>
                            </div>
                            <div class="result-category">
                                <h3>Lifestyle</h3>
                                <div class="score" id="lifestyleScore">0%</div>
                            </div>
                        </div>
                        <div class="recommendations" id="recommendations">
                            <!-- Recommendations will be dynamically added here -->
                        </div>
                        <div class="action-buttons">
                            <button class="action-btn" onclick="saveResults()">
                                <i class="fas fa-save"></i>
                                Save Results
                            </button>
                            <button class="action-btn" onclick="shareResults()">
                                <i class="fas fa-share-alt"></i>
                                Share Results
                            </button>
                            <button class="action-btn" onclick="retakeQuiz()">
                                <i class="fas fa-redo"></i>
                                Retake Quiz
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script>
        // Quiz data from PHP
        const quizQuestions = <?php echo json_encode($quizQuestions); ?>;
        
        let currentQuestionIndex = 0;
        let answers = [];

        function startQuiz() {
            document.getElementById('quizIntro').style.display = 'none';
            document.getElementById('quizQuestions').style.display = 'block';
            showQuestion();
        }

        function showQuestion() {
            const question = quizQuestions[currentQuestionIndex];
            document.getElementById('currentQuestion').textContent = currentQuestionIndex + 1;
            document.getElementById('questionText').textContent = question.question;
            
            const optionsContainer = document.getElementById('questionOptions');
            optionsContainer.innerHTML = '';
            
            question.options.forEach((option, index) => {
                const optionElement = document.createElement('div');
                optionElement.className = 'option';
                optionElement.textContent = option;
                optionElement.onclick = () => selectOption(index);
                optionsContainer.appendChild(optionElement);
            });

            updateProgress();
            updateNavigationButtons();
        }

        function selectOption(optionIndex) {
            const options = document.querySelectorAll('.option');
            options.forEach(option => option.classList.remove('selected'));
            options[optionIndex].classList.add('selected');
            answers[currentQuestionIndex] = optionIndex;
        }

        function previousQuestion() {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                showQuestion();
            }
        }

        function nextQuestion() {
            if (currentQuestionIndex < quizQuestions.length - 1) {
                currentQuestionIndex++;
                showQuestion();
            } else {
                showResults();
            }
        }

        function updateProgress() {
            const progress = ((currentQuestionIndex + 1) / quizQuestions.length) * 100;
            document.getElementById('quizProgress').style.width = `${progress}%`;
        }

        function updateNavigationButtons() {
            const prevBtn = document.querySelector('.prev-btn');
            const nextBtn = document.querySelector('.next-btn');
            
            prevBtn.disabled = currentQuestionIndex === 0;
            nextBtn.textContent = currentQuestionIndex === quizQuestions.length - 1 ? 'Finish' : 'Next';
        }

        function showResults() {
            document.getElementById('quizQuestions').style.display = 'none';
            document.getElementById('quizResults').style.display = 'block';
            
            // Calculate scores for each category
            const scores = calculateScores();
            
            // Update score displays
            document.getElementById('physicalScore').textContent = `${scores.physical}%`;
            document.getElementById('mentalScore').textContent = `${scores.mental}%`;
            document.getElementById('lifestyleScore').textContent = `${scores.lifestyle}%`;
            
            // Generate recommendations
            generateRecommendations(scores);
        }

        function calculateScores() {
            const scores = {
                physical: 0,
                mental: 0,
                lifestyle: 0
            };
            
            const categoryCounts = {
                physical: 0,
                mental: 0,
                lifestyle: 0
            };
            
            quizQuestions.forEach((question, index) => {
                const answer = answers[index];
                const category = question.category;
                
                // Convert answer to score (0-3 to 0-100)
                const answerScore = (answer / 3) * 100;
                
                scores[category] += answerScore;
                categoryCounts[category]++;
            });
            
            // Calculate averages
            Object.keys(scores).forEach(category => {
                scores[category] = Math.round(scores[category] / categoryCounts[category]);
            });
            
            return scores;
        }

        function generateRecommendations(scores) {
            const recommendations = document.getElementById('recommendations');
            recommendations.innerHTML = '';
            
            // Physical health recommendations
            if (scores.physical < 70) {
                addRecommendation(recommendations, 'Physical Health', [
                    'Consider increasing your exercise frequency',
                    'Aim for 7-8 hours of sleep each night',
                    'Increase your water intake',
                    'Add more fruits and vegetables to your diet'
                ]);
            }
            
            // Mental health recommendations
            if (scores.mental < 70) {
                addRecommendation(recommendations, 'Mental Health', [
                    'Practice mindfulness or meditation daily',
                    'Take regular breaks during work',
                    'Maintain regular social connections',
                    'Consider stress management techniques'
                ]);
            }
            
            // Lifestyle recommendations
            if (scores.lifestyle < 70) {
                addRecommendation(recommendations, 'Lifestyle', [
                    'Work on improving work-life balance',
                    'Schedule regular time for hobbies',
                    'Plan regular vacations or time off',
                    'Set boundaries between work and personal time'
                ]);
            }
        }

        function addRecommendation(container, category, items) {
            const categoryDiv = document.createElement('div');
            categoryDiv.className = 'recommendation-category';
            
            const title = document.createElement('h3');
            title.textContent = category;
            categoryDiv.appendChild(title);
            
            const list = document.createElement('ul');
            items.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item;
                list.appendChild(li);
            });
            
            categoryDiv.appendChild(list);
            container.appendChild(categoryDiv);
        }

        function saveResults() {
            // Send results to server
            fetch('save_quiz_results.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    answers: answers,
                    scores: calculateScores()
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Results saved successfully!');
                } else {
                    alert('Error saving results. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving results. Please try again.');
            });
        }

        function shareResults() {
            // Implement share functionality
            alert('Sharing feature coming soon!');
        }

        function retakeQuiz() {
            currentQuestionIndex = 0;
            answers = [];
            document.getElementById('quizResults').style.display = 'none';
            document.getElementById('quizIntro').style.display = 'flex';
        }
    </script>
</body>
</html> 