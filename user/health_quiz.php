<?php
session_start();
include '../db_connection/database.php';
include '../back_end/session.php';  

$sql = "SELECT * FROM quizzes WHERE title = 'Health Trivia Quiz' LIMIT 1";
$result = $conn->query($sql);
$quiz = $result->fetch_assoc();

if (!$quiz) {
    die("Health quiz not found. Please contact the administrator.");
}

// Fetch questions for the quiz with their points
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
$totalPoints = 0;
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
        'id' => $row['id'],
        'question' => $row['question'],
        'options' => $options,
        'points' => $row['points'],
        'category' => $category,
        'correct_answers' => $correct_answers
    ];
    $totalPoints += $row['points'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EWell - Health Quiz</title>
    <link rel="stylesheet" href="../css/variables.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/health_quiz.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  
</head>
<body>
    <div class="dashboard-container">
        <?php include 'includes/header.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="quiz-content">
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
                                <i class="fas fa-star"></i>
                                <span><?php echo $totalPoints; ?> total points</span>
                            </div>
                        </div>
                        <button class="start-quiz-btn" onclick="startQuiz()">
                            Start Assessment
                        </button>
                    </div>
                </section>

                <!-- Quiz Questions -->
                <section class="quiz-questions" id="quizQuestions" style="display: none;">
                    <div class="progress-bar">
                        <div class="progress" id="quizProgress"></div>
                    </div>
                    <div class="question-container">
                        <div class="timer-container" id="timer">
                            <i class="fas fa-clock"></i>
                            <span id="timeLeft">15</span>s
                        </div>
                        <div class="question-number">Question <span id="currentQuestion">1</span> of <?php echo count($quizQuestions); ?></div>
                        <div class="question" id="questionText"></div>
                        <div class="options" id="questionOptions"></div>
                        <div id="feedbackMessage" class="feedback-message" style="display: none;"></div>
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
        const quizQuestions = <?php echo json_encode($quizQuestions); ?>;
        
        let currentQuestionIndex = 0;
        let answers = [];
        let timer;
        let timeLeft = 15;
        let canAnswer = true;

        function createConfetti() {
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.backgroundColor = `hsl(${Math.random() * 360}, 100%, 50%)`;
                confetti.style.animationDelay = Math.random() * 3 + 's';
                document.body.appendChild(confetti);
                
                // Remove confetti after animation
                setTimeout(() => confetti.remove(), 3000);
            }
        }

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

            // Reset timer and feedback
            resetTimer();
            document.getElementById('feedbackMessage').style.display = 'none';
            canAnswer = true;

            updateProgress();
        }

        function resetTimer() {
            clearInterval(timer);
            timeLeft = 15;
            updateTimerDisplay();
            startTimer();
        }

        function startTimer() {
            timer = setInterval(() => {
                timeLeft--;
                updateTimerDisplay();
                
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    handleTimeUp();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const timerElement = document.getElementById('timeLeft');
            const timerContainer = document.querySelector('.timer-container');
            
            timerElement.textContent = timeLeft;
            
            // Update timer color based on time left
            timerContainer.className = 'timer-container';
            if (timeLeft <= 5) {
                timerContainer.classList.add('danger');
            } else if (timeLeft <= 10) {
                timerContainer.classList.add('warning');
            }
        }

        function handleTimeUp() {
            if (!canAnswer) return;
            
            canAnswer = false;
            const currentQuestion = quizQuestions[currentQuestionIndex];
            const options = document.querySelectorAll('.option');
            
            // Show correct answer
            options.forEach((option, index) => {
                if (currentQuestion.correct_answers[index] === '1') {
                    option.classList.add('correct');
                }
            });

            // Show feedback message
            const feedbackMessage = document.getElementById('feedbackMessage');
            feedbackMessage.textContent = 'Time\'s up!';
            feedbackMessage.className = 'feedback-message incorrect';
            feedbackMessage.style.display = 'block';

            // Move to next question after delay
            setTimeout(() => {
                if (currentQuestionIndex < quizQuestions.length - 1) {
                    currentQuestionIndex++;
                    showQuestion();
                } else {
                    showResults();
                }
            }, 2000);
        }

        function selectOption(optionIndex) {
            if (!canAnswer) return;
            
            canAnswer = false;
            clearInterval(timer);
            
            const options = document.querySelectorAll('.option');
            const currentQuestion = quizQuestions[currentQuestionIndex];
            const isCorrect = currentQuestion.correct_answers[optionIndex] === '1';
            
            // Show selected answer
            options[optionIndex].classList.add('selected');
            
            // Show correct/incorrect feedback
            options.forEach((option, index) => {
                if (currentQuestion.correct_answers[index] === '1') {
                    option.classList.add('correct');
                    // Add points badge if this was the selected answer
                    if (index === optionIndex) {
                        const pointsBadge = document.createElement('div');
                        pointsBadge.className = 'points-badge';
                        pointsBadge.textContent = `+${currentQuestion.points} points`;
                        option.appendChild(pointsBadge);
                    }
                } else if (index === optionIndex) {
                    option.classList.add('incorrect');
                }
            });

            // Show feedback message
            const feedbackMessage = document.getElementById('feedbackMessage');
            feedbackMessage.textContent = isCorrect ? 
                `Correct! +${currentQuestion.points} points` : 
                'Incorrect!';
            feedbackMessage.className = `feedback-message ${isCorrect ? 'correct' : 'incorrect'}`;
            feedbackMessage.style.display = 'block';

            // Store answer
            answers[currentQuestionIndex] = {
                questionId: currentQuestion.id,
                answerIndex: optionIndex,
                points: isCorrect ? currentQuestion.points : 0
            };

            // Move to next question after delay
            setTimeout(() => {
            if (currentQuestionIndex < quizQuestions.length - 1) {
                currentQuestionIndex++;
                showQuestion();
            } else {
                showResults();
            }
            }, 2000);
        }

        function updateProgress() {
            const progress = ((currentQuestionIndex + 1) / quizQuestions.length) * 100;
            document.getElementById('quizProgress').style.width = `${progress}%`;
        }

        function showResults() {
            document.getElementById('quizQuestions').style.display = 'none';
            document.getElementById('quizResults').style.display = 'block';
            const scores = calculateScores();
            animateScores(scores);
            generateRecommendations(scores);
            createConfetti();
        }

        function animateScores(scores) {
            const categories = ['physical', 'mental', 'lifestyle'];
            categories.forEach(category => {
                const element = document.getElementById(`${category}Score`);
                const targetScore = scores[category];
                let currentScore = 0;
                
                const interval = setInterval(() => {
                    if (currentScore >= targetScore) {
                        clearInterval(interval);
                        element.textContent = `${targetScore}%`;
                    } else {
                        currentScore++;
                        element.textContent = `${currentScore}%`;
                    }
                }, 20);
            });
        }

        function calculateScores() {
            const scores = {
                physical: 0,
                mental: 0,
                lifestyle: 0
            };
            
            const categoryPoints = {
                physical: 0,
                mental: 0,
                lifestyle: 0
            };
            
            const categoryTotalPoints = {
                physical: 0,
                mental: 0,
                lifestyle: 0
            };
            
            quizQuestions.forEach((question, index) => {
                const answer = answers[index];
                const category = question.category;
                
                if (answer) {
                    categoryPoints[category] += question.points;
                }
                categoryTotalPoints[category] += question.points;
            });
            
            // Calculate percentages
            Object.keys(scores).forEach(category => {
                scores[category] = Math.round((categoryPoints[category] / categoryTotalPoints[category]) * 100);
            });
            
            return scores;
        }

        function generateRecommendations(scores) {
            const recommendations = document.getElementById('recommendations');
            recommendations.innerHTML = '';
            if (scores.physical < 70) {
                addRecommendation(recommendations, 'Physical Health', [
                    'Consider increasing your exercise frequency',
                    'Aim for 7-8 hours of sleep each night',
                    'Increase your water intake',
                    'Add more fruits and vegetables to your diet'
                ]);
            }
            if (scores.mental < 70) {
                addRecommendation(recommendations, 'Mental Health', [
                    'Practice mindfulness or meditation daily',
                    'Take regular breaks during work',
                    'Maintain regular social connections',
                    'Consider stress management techniques'
                ]);
            }
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
                    alert('Error saving results: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving results. Please try again.');
            });
        }

        function shareResults() {
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