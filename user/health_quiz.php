<?php
session_start();
include '../db_connection/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../main/login.php');
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
        'id' => $row['id'],
        'question' => $row['question'],
        'options' => $options,
        'correct_answers' => $correct_answers,
        'points' => $row['points'],
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
    <!-- Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        /* Enhanced Quiz Styles */
        .quiz-content {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .quiz-intro {
            text-align: center;
            padding: 2rem;
        }

        .intro-card {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .intro-card i {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: bounce 2s infinite;
        }

        .quiz-info {
            display: flex;
            justify-content: space-around;
            margin: 2rem 0;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .info-item {
            background: rgba(255, 255, 255, 0.2);
            padding: 1rem 2rem;
            border-radius: 10px;
            backdrop-filter: blur(5px);
        }

        .start-quiz-btn {
            background: white;
            color: #4CAF50;
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .start-quiz-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .progress-bar {
            background: #f0f0f0;
            height: 10px;
            border-radius: 5px;
            margin: 2rem 0;
            overflow: hidden;
        }

        .progress {
            background: linear-gradient(90deg, #4CAF50, #45a049);
            height: 100%;
            width: 0;
            transition: width 0.3s ease;
        }

        .question-container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .question-number {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .question {
            font-size: 1.5rem;
            font-weight: 500;
            margin-bottom: 2rem;
            color: #333;
        }

        .options {
            display: grid;
            gap: 1rem;
        }

        .option {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .option:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .option.selected {
            background: #e8f5e9;
            border-color: #4CAF50;
        }

        .option i {
            color: #4CAF50;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .option.selected i {
            opacity: 1;
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .nav-btn {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .nav-btn:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .results-card {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 3rem;
            border-radius: 15px;
            text-align: center;
        }

        .results-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .result-category {
            background: rgba(255, 255, 255, 0.2);
            padding: 1.5rem;
            border-radius: 10px;
            backdrop-filter: blur(5px);
        }

        .score {
            font-size: 2.5rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .recommendations {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 10px;
            margin: 2rem 0;
            text-align: left;
        }

        .recommendation-category {
            margin-bottom: 1.5rem;
        }

        .recommendation-category h3 {
            color: #fff;
            margin-bottom: 1rem;
        }

        .recommendation-category ul {
            list-style: none;
            padding: 0;
        }

        .recommendation-category li {
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
            position: relative;
        }

        .recommendation-category li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #fff;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .action-btn {
            background: white;
            color: #4CAF50;
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Animations */
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .quiz-content {
                margin: 1rem;
                padding: 1rem;
            }

            .intro-card {
                padding: 2rem 1rem;
            }

            .question {
                font-size: 1.2rem;
            }

            .results-summary {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include 'includes/header.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="quiz-content">
                <!-- Quiz Introduction -->
                <section class="quiz-intro animate__animated animate__fadeIn" id="quizIntro">
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
                <section class="quiz-questions animate__animated animate__fadeIn" id="quizQuestions" style="display: none;">
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
                <section class="quiz-results animate__animated animate__fadeIn" id="quizResults" style="display: none;">
                    <div class="results-card">
                        <i class="fas fa-chart-pie fa-3x"></i>
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
            const quizQuestions = document.getElementById('quizQuestions');
            quizQuestions.style.display = 'block';
            quizQuestions.classList.add('animate__fadeIn');
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
                optionElement.innerHTML = `
                    <i class="fas fa-check"></i>
                    <span>${option}</span>
                `;
                optionElement.onclick = () => selectOption(index);
                optionsContainer.appendChild(optionElement);
            });

            // Pre-select previous answer if exists
            if (answers[currentQuestionIndex] !== undefined) {
                const options = document.querySelectorAll('.option');
                options[answers[currentQuestionIndex]].classList.add('selected');
            }

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
            const results = document.getElementById('quizResults');
            results.style.display = 'block';
            results.classList.add('animate__fadeIn');
            
            // Calculate scores for each category
            const scores = calculateScores();
            
            // Animate score updates
            animateScore('physicalScore', scores.physical);
            animateScore('mentalScore', scores.mental);
            animateScore('lifestyleScore', scores.lifestyle);
            
            // Generate recommendations
            generateRecommendations(scores);
        }

        function animateScore(elementId, finalScore) {
            const element = document.getElementById(elementId);
            let currentScore = 0;
            const duration = 1500; // 1.5 seconds
            const steps = 60;
            const increment = finalScore / steps;
            const stepDuration = duration / steps;

            const interval = setInterval(() => {
                currentScore += increment;
                if (currentScore >= finalScore) {
                    currentScore = finalScore;
                    clearInterval(interval);
                }
                element.textContent = `${Math.round(currentScore)}%`;
            }, stepDuration);
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
                
                // Calculate score based on correct answer
                const correctAnswer = question.correct_answers[0];
                const isCorrect = answer === parseInt(correctAnswer);
                const questionScore = isCorrect ? 100 : 0;
                
                scores[category] += questionScore;
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
            categoryDiv.className = 'recommendation-category animate__animated animate__fadeIn';
            
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
                    quiz_id: <?php echo $quiz['quiz_id']; ?>,
                    answers: answers,
                    scores: calculateScores()
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Results saved successfully!', 'success');
                } else {
                    showNotification('Error saving results. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error saving results. Please try again.', 'error');
            });
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type} animate__animated animate__fadeIn`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('animate__fadeOut');
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        }

        function shareResults() {
            // Implement share functionality
            showNotification('Sharing feature coming soon!', 'info');
        }

        function retakeQuiz() {
            currentQuestionIndex = 0;
            answers = [];
            document.getElementById('quizResults').style.display = 'none';
            const intro = document.getElementById('quizIntro');
            intro.style.display = 'flex';
            intro.classList.add('animate__fadeIn');
        }
    </script>
</body>
</html> 