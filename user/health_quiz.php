<?php
session_start();
include '../db_connection/database.php';
include '../back_end/session.php';  

$sql = "SELECT * FROM quizzes WHERE title = 'Health Assessment' LIMIT 1";
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
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/variables.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/health_quiz.css">
    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Quiz-specific styles */
        .quiz-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .intro-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-out;
        }

        .intro-card i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            animation: bounce 2s infinite;
        }

        .quiz-info {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin: 2rem 0;
        }

        .info-item {
            background: rgba(255, 255, 255, 0.9);
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-5px);
        }

        .start-quiz-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .start-quiz-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .question-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.5s ease-out;
            position: relative;
        }

        .timer-container {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .timer-container.warning {
            background: #ffc107;
            animation: pulse 1s infinite;
        }

        .timer-container.danger {
            background: #dc3545;
            animation: pulse 0.5s infinite;
        }

        .progress-bar {
            height: 8px;
            background: #f0f0f0;
            border-radius: 4px;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .progress {
            height: 100%;
            background: var(--primary-color);
            transition: width 0.3s ease;
        }

        .question {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .options {
            display: grid;
            gap: 1rem;
        }

        .option {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .option:hover {
            background: #e9ecef;
            transform: translateX(10px);
        }

        .option.selected {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .option.correct {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }

        .option.incorrect {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        .option-feedback {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
        }

        .points-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #ffc107;
            color: #000;
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: bold;
            animation: bounceIn 0.5s ease-out;
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .nav-btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .prev-btn {
            background: #e9ecef;
            color: #495057;
        }

        .next-btn {
            background: var(--primary-color);
            color: white;
        }

        .results-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-out;
        }

        .results-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .result-category {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            transition: transform 0.3s ease;
        }

        .result-category:hover {
            transform: translateY(-5px);
        }

        .score {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-top: 0.5rem;
        }

        .recommendations {
            margin: 2rem 0;
            text-align: left;
        }

        .recommendation-category {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 1rem;
        }

        .recommendation-category h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .recommendation-category ul {
            list-style: none;
            padding: 0;
        }

        .recommendation-category li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .recommendation-category li:last-child {
            border-bottom: none;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .action-btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary-color);
            color: white;
        }

        .action-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .feedback-message {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
            animation: fadeIn 0.5s ease-out;
        }

        .feedback-message.correct {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .feedback-message.incorrect {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateX(-50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-20px); }
            60% { transform: translateY(-10px); }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        @keyframes bounceIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        /* Confetti animation */
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background-color: var(--primary-color);
            opacity: 0;
            animation: confetti-fall 3s ease-in infinite;
        }

        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
        }

        @media (max-width: 600px) {
            .quiz-content {
                padding: 0.5rem;
                max-width: 100vw;
            }
            .intro-card {
                padding: 1rem;
                border-radius: 12px;
                max-width: 100vw;
            }
            .quiz-info {
                grid-template-columns: 1fr;
                gap: 0.5rem;
                margin: 1rem 0;
            }
            .info-item {
                font-size: 0.95rem;
                padding: 0.5rem 0.2rem;
            }
            .start-quiz-btn {
                padding: 0.7rem 1.2rem;
                font-size: 1rem;
                width: 100%;
                justify-content: center;
            }
            .quiz-questions {
                padding: 0.5rem;
            }
            .question-container {
                padding: 1rem;
                border-radius: 12px;
            }
            .question {
                font-size: 1rem;
                margin-bottom: 1rem;
            }
            .options {
                gap: 0.5rem;
                margin-bottom: 1rem;
            }
            .option {
                padding: 0.7rem;
                font-size: 0.98rem;
            }
            .progress-bar {
                margin-bottom: 1rem;
            }
            .navigation-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
            .nav-btn {
                width: 100%;
                font-size: 1rem;
                padding: 0.7rem 0.5rem;
            }
            .quiz-results {
                padding: 0.5rem;
            }
            .results-card {
                padding: 1rem;
                border-radius: 12px;
            }
            .results-summary {
                grid-template-columns: 1fr;
                gap: 1rem;
                margin: 1rem 0;
            }
            .score {
                font-size: 1.1rem;
            }
            .recommendations ul {
                padding: 0;
            }
            .recommendations li {
                padding: 0.7rem;
                font-size: 0.95rem;
            }
            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
                margin-top: 1rem;
            }
            .action-btn {
                width: 100%;
                font-size: 1rem;
                padding: 0.7rem 0.5rem;
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
            
            // Calculate scores for each category
            const scores = calculateScores();
            
            // Animate scores
            animateScores(scores);
            
            // Generate recommendations
            generateRecommendations(scores);
            
            // Create confetti effect
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
                    alert('Error saving results: ' + data.message);
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