document.addEventListener('DOMContentLoaded', () => {
    const quizData = {
        mental: [
            {
                question: "Which of the following is a common symptom of anxiety?",
                options: [
                    "Rapid heartbeat and sweating",
                    "Increased appetite",
                    "Improved concentration",
                    "Better sleep quality"
                ],
                correct: 0,
                feedback: "Rapid heartbeat and sweating are common physical symptoms of anxiety. It's important to recognize these signs and practice calming techniques."
            },
            {
                question: "What is mindfulness?",
                options: [
                    "A form of exercise",
                    "Being present in the moment without judgment",
                    "A type of medication",
                    "A sleep technique"
                ],
                correct: 1,
                feedback: "Mindfulness is the practice of being fully present in the moment, observing thoughts and feelings without judgment."
            }
        ],
        stress: [
            {
                question: "Which of these is NOT a healthy way to manage stress?",
                options: [
                    "Regular exercise",
                    "Excessive alcohol consumption",
                    "Deep breathing exercises",
                    "Meditation"
                ],
                correct: 1,
                feedback: "Excessive alcohol consumption is not a healthy way to manage stress. It can actually increase stress levels and lead to other health problems."
            },
            {
                question: "What is the 'fight or flight' response?",
                options: [
                    "A type of exercise",
                    "The body's natural response to stress",
                    "A meditation technique",
                    "A sleep disorder"
                ],
                correct: 1,
                feedback: "The fight or flight response is the body's natural reaction to perceived threats, preparing us to either confront or escape danger."
            }
        ],
        physical: [
            {
                question: "How much physical activity is recommended for adults per week?",
                options: [
                    "30 minutes once a week",
                    "150 minutes of moderate activity",
                    "60 minutes daily",
                    "No specific amount"
                ],
                correct: 1,
                feedback: "Adults should aim for at least 150 minutes of moderate-intensity physical activity per week for optimal health benefits."
            },
            {
                question: "Which of these is a benefit of regular exercise?",
                options: [
                    "Increased stress levels",
                    "Poor sleep quality",
                    "Improved mood and energy",
                    "Decreased flexibility"
                ],
                correct: 2,
                feedback: "Regular exercise has been shown to improve mood, increase energy levels, and contribute to overall well-being."
            }
        ],
        sleep: [
            {
                question: "What is the recommended amount of sleep for adults?",
                options: [
                    "4-5 hours",
                    "6-7 hours",
                    "7-9 hours",
                    "10+ hours"
                ],
                correct: 2,
                feedback: "Most adults need 7-9 hours of sleep per night for optimal health and functioning."
            },
            {
                question: "Which of these can improve sleep quality?",
                options: [
                    "Using electronic devices before bed",
                    "Consuming caffeine in the evening",
                    "Maintaining a consistent sleep schedule",
                    "Exercising right before bed"
                ],
                correct: 2,
                feedback: "Maintaining a consistent sleep schedule helps regulate your body's internal clock and can improve sleep quality."
            }
        ],
        nutrition: [
            {
                question: "Which of these is a good source of omega-3 fatty acids?",
                options: [
                    "Processed meats",
                    "Fried foods",
                    "Fatty fish like salmon",
                    "White bread"
                ],
                correct: 2,
                feedback: "Fatty fish like salmon are excellent sources of omega-3 fatty acids, which are important for brain health."
            },
            {
                question: "What is the recommended daily water intake?",
                options: [
                    "2-3 glasses",
                    "4-5 glasses",
                    "6-8 glasses",
                    "10+ glasses"
                ],
                correct: 2,
                feedback: "The general recommendation is to drink 6-8 glasses of water per day, though individual needs may vary."
            }
        ]
    };

    let currentCategory = 'mental';
    let currentQuestion = 0;
    let score = 0;
    let userAnswers = [];

    // DOM Elements
    const categoryButtons = document.querySelectorAll('.category-btn');
    const questionText = document.getElementById('question-text');
    const optionsContainer = document.getElementById('options-container');
    const progressFill = document.querySelector('.progress-fill');
    const currentQuestionSpan = document.getElementById('current-question');
    const totalQuestionsSpan = document.getElementById('total-questions');
    const feedbackDiv = document.getElementById('quiz-feedback');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const quizContent = document.querySelector('.quiz-content');
    const quizSummary = document.getElementById('quiz-summary');
    const finalScore = document.getElementById('final-score');
    const resultMessage = document.getElementById('result-message');
    const tipsList = document.getElementById('tips-list');
    const restartBtn = document.getElementById('restart-quiz');

    // Initialize quiz
    function initQuiz() {
        currentQuestion = 0;
        score = 0;
        userAnswers = [];
        updateProgress();
        showQuestion();
        quizContent.style.display = 'block';
        quizSummary.style.display = 'none';
    }

    // Update progress bar and counter
    function updateProgress() {
        const progress = ((currentQuestion) / quizData[currentCategory].length) * 100;
        progressFill.style.width = `${progress}%`;
        currentQuestionSpan.textContent = currentQuestion + 1;
        totalQuestionsSpan.textContent = quizData[currentCategory].length;
    }

    // Show current question
    function showQuestion() {
        const question = quizData[currentCategory][currentQuestion];
        questionText.textContent = question.question;
        
        optionsContainer.innerHTML = '';
        question.options.forEach((option, index) => {
            const button = document.createElement('button');
            button.className = 'option-btn';
            button.textContent = option;
            button.addEventListener('click', () => selectOption(index));
            optionsContainer.appendChild(button);
        });

        updateProgress();
        prevBtn.disabled = currentQuestion === 0;
        nextBtn.textContent = currentQuestion === quizData[currentCategory].length - 1 ? 'Finish' : 'Next';
    }

    // Handle option selection
    function selectOption(index) {
        const question = quizData[currentCategory][currentQuestion];
        const options = optionsContainer.querySelectorAll('.option-btn');
        
        options.forEach(option => option.classList.remove('selected'));
        options[index].classList.add('selected');

        userAnswers[currentQuestion] = index;
        showFeedback(question, index);
    }

    // Show feedback for selected answer
    function showFeedback(question, selectedIndex) {
        const options = optionsContainer.querySelectorAll('.option-btn');
        options.forEach(option => {
            option.classList.remove('correct', 'incorrect');
        });

        if (selectedIndex === question.correct) {
            options[selectedIndex].classList.add('correct');
            feedbackDiv.textContent = question.feedback;
            feedbackDiv.className = 'quiz-feedback show';
        } else {
            options[selectedIndex].classList.add('incorrect');
            options[question.correct].classList.add('correct');
            feedbackDiv.textContent = question.feedback;
            feedbackDiv.className = 'quiz-feedback show';
        }
    }

    // Show quiz summary
    function showSummary() {
        score = userAnswers.reduce((total, answer, index) => {
            return total + (answer === quizData[currentCategory][index].correct ? 1 : 0);
        }, 0);

        const percentage = (score / quizData[currentCategory].length) * 100;
        finalScore.textContent = `${percentage}%`;

        // Generate result message
        if (percentage >= 80) {
            resultMessage.textContent = "Excellent! You have a great understanding of this topic.";
        } else if (percentage >= 60) {
            resultMessage.textContent = "Good job! You have a solid understanding of this topic.";
        } else {
            resultMessage.textContent = "Keep learning! There's always room for improvement.";
        }

        // Generate personalized tips
        generateTips(percentage);

        quizContent.style.display = 'none';
        quizSummary.style.display = 'block';
    }

    // Generate personalized tips based on score
    function generateTips(percentage) {
        tipsList.innerHTML = '';
        const tips = getTipsForCategory(currentCategory, percentage);
        tips.forEach(tip => {
            const li = document.createElement('li');
            li.textContent = tip;
            tipsList.appendChild(li);
        });
    }

    // Get tips based on category and score
    function getTipsForCategory(category, score) {
        const tips = {
            mental: [
                "Practice daily mindfulness meditation",
                "Keep a gratitude journal",
                "Stay connected with friends and family",
                "Learn to recognize early signs of stress"
            ],
            stress: [
                "Incorporate regular exercise into your routine",
                "Practice deep breathing exercises",
                "Maintain a healthy work-life balance",
                "Learn time management techniques"
            ],
            physical: [
                "Aim for 30 minutes of exercise daily",
                "Stay hydrated throughout the day",
                "Take regular breaks from sitting",
                "Incorporate stretching into your routine"
            ],
            sleep: [
                "Maintain a consistent sleep schedule",
                "Create a relaxing bedtime routine",
                "Limit screen time before bed",
                "Keep your bedroom cool and dark"
            ],
            nutrition: [
                "Eat a balanced diet with plenty of fruits and vegetables",
                "Stay hydrated with water",
                "Limit processed foods",
                "Practice mindful eating"
            ]
        };

        return tips[category].slice(0, score >= 80 ? 2 : 4);
    }

    // Event Listeners
    categoryButtons.forEach(button => {
        button.addEventListener('click', () => {
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            currentCategory = button.dataset.category;
            initQuiz();
        });
    });

    prevBtn.addEventListener('click', () => {
        if (currentQuestion > 0) {
            currentQuestion--;
            showQuestion();
        }
    });

    nextBtn.addEventListener('click', () => {
        if (currentQuestion < quizData[currentCategory].length - 1) {
            currentQuestion++;
            showQuestion();
        } else {
            showSummary();
        }
    });

    restartBtn.addEventListener('click', initQuiz);

    // Initialize the quiz
    initQuiz();
}); 