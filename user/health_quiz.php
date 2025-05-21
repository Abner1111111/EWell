<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EWell - Health Quiz</title>
    <link rel="stylesheet" href="../css/health_quiz.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="quiz-container">
        <header class="quiz-header">
            <h1>Health & Wellness Quiz</h1>
            <div class="quiz-categories">
                <button class="category-btn active" data-category="mental">Mental Health</button>
                <button class="category-btn" data-category="stress">Stress Awareness</button>
                <button class="category-btn" data-category="physical">Physical Wellness</button>
                <button class="category-btn" data-category="sleep">Sleep Hygiene</button>
                <button class="category-btn" data-category="nutrition">Nutrition</button>
            </div>
        </header>

        <main class="quiz-content">
            <div class="quiz-progress">
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <span class="question-counter">Question <span id="current-question">1</span> of <span id="total-questions">10</span></span>
            </div>

            <div class="quiz-question-container">
                <div class="question-card">
                    <h2 id="question-text">Loading question...</h2>
                    <div class="options-container" id="options-container">
                        <!-- Options will be dynamically inserted here -->
                    </div>
                </div>
            </div>

            <div class="quiz-feedback" id="quiz-feedback">
                <!-- Feedback will be shown here -->
            </div>

            <div class="quiz-controls">
                <button id="prev-btn" class="control-btn" disabled>Previous</button>
                <button id="next-btn" class="control-btn">Next</button>
            </div>
        </main>

        <div class="quiz-summary" id="quiz-summary" style="display: none;">
            <h2>Quiz Summary</h2>
            <div class="score-display">
                <div class="score-circle">
                    <span id="final-score">0</span>
                    <span class="score-label">Score</span>
                </div>
            </div>
            <div class="feedback-section">
                <h3>Your Results</h3>
                <p id="result-message"></p>
            </div>
            <div class="tips-section">
                <h3>Personalized Tips</h3>
                <ul id="tips-list"></ul>
            </div>
            <button id="restart-quiz" class="primary-btn">Take Another Quiz</button>
        </div>
    </div>

    <script src="../js/health_quiz.js"></script>
</body>
</html> 