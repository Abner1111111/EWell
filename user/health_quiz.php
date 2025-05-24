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
                        <h2>Welcome to Your Health Assessment</h2>
                        <p>Take this comprehensive quiz to evaluate your physical, mental, and lifestyle health. Your answers will help us provide personalized recommendations for your wellness journey.</p>
                        <div class="quiz-info">
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <span>5-10 minutes</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-list-check"></i>
                                <span>15 questions</span>
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
                        <div class="question-number">Question <span id="currentQuestion">1</span> of 15</div>
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
                        <i class="fas fa-chart-line"></i>
                        <h2>Your Health Assessment Results</h2>
                        <div class="results-summary">
                            <div class="score-circle">
                                <span id="healthScore">0</span>%
                            </div>
                            <div class="result-categories">
                                <div class="category">
                                    <h4>Physical Health</h4>
                                    <div class="category-score" id="physicalScore"></div>
                                </div>
                                <div class="category">
                                    <h4>Mental Health</h4>
                                    <div class="category-score" id="mentalScore"></div>
                                </div>
                                <div class="category">
                                    <h4>Lifestyle</h4>
                                    <div class="category-score" id="lifestyleScore"></div>
                                </div>
                            </div>
                        </div>
                        <div class="recommendations">
                            <h3>Personalized Recommendations</h3>
                            <ul id="recommendationsList"></ul>
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
        // Quiz data
        const quizQuestions = [
            {
                question: "How often do you exercise?",
                options: [
                    "Never",
                    "1-2 times per week",
                    "3-4 times per week",
                    "5 or more times per week"
                ],
                category: "physical"
            },
            {
                question: "How many hours of sleep do you get on average?",
                options: [
                    "Less than 6 hours",
                    "6-7 hours",
                    "7-8 hours",
                    "More than 8 hours"
                ],
                category: "physical"
            },
            {
                question: "How would you rate your stress levels?",
                options: [
                    "Very high",
                    "Moderate to high",
                    "Moderate",
                    "Low"
                ],
                category: "mental"
            },
            // Add more questions here...
        ];

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
            prevBtn.disabled = currentQuestionIndex === 0;
        }

        function showResults() {
            document.getElementById('quizQuestions').style.display = 'none';
            document.getElementById('quizResults').style.display = 'block';
            
            // Calculate scores
            const scores = calculateScores();
            displayScores(scores);
            generateRecommendations(scores);
        }

        function calculateScores() {
            // Implement scoring logic here
            return {
                physical: 75,
                mental: 80,
                lifestyle: 85,
                overall: 80
            };
        }

        function displayScores(scores) {
            document.getElementById('healthScore').textContent = scores.overall;
            
            // Set category scores
            document.getElementById('physicalScore').style.setProperty('--score', `${scores.physical}%`);
            document.getElementById('mentalScore').style.setProperty('--score', `${scores.mental}%`);
            document.getElementById('lifestyleScore').style.setProperty('--score', `${scores.lifestyle}%`);
        }

        function generateRecommendations(scores) {
            const recommendations = [
                "Consider increasing your daily physical activity",
                "Practice mindfulness meditation for stress management",
                "Aim for 7-8 hours of sleep each night",
                "Stay hydrated throughout the day"
            ];

            const recommendationsList = document.getElementById('recommendationsList');
            recommendationsList.innerHTML = recommendations
                .map(rec => `<li>${rec}</li>`)
                .join('');
        }

        function saveResults() {
            // Implement save functionality
            alert('Results saved successfully!');
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