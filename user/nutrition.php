<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EWell - Nutrition & Diet</title>
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/variables.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/nutrition.css">
    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include 'includes/header.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="nutrition-container">
                <!-- Meal Plans Section -->
                <section class="nutrition-section">
                    <h2 class="section-title">Your Meal Plans</h2>
                    <div class="nutrition-grid">
                        <div class="nutrition-card">
                            <h3><i class="fas fa-calendar-alt"></i> Weekly Meal Plan</h3>
                            <ul class="meal-plan-list">
                                <li>
                                    <span>Breakfast</span>
                                    <i class="fas fa-chevron-right"></i>
                                </li>
                                <li>
                                    <span>Lunch</span>
                                    <i class="fas fa-chevron-right"></i>
                                </li>
                                <li>
                                    <span>Dinner</span>
                                    <i class="fas fa-chevron-right"></i>
                                </li>
                                <li>
                                    <span>Snacks</span>
                                    <i class="fas fa-chevron-right"></i>
                                </li>
                            </ul>
                            <a href="#" class="register-btn">View Full Plan</a>
                        </div>

                       

                        <div class="nutrition-card">
                            <h3><i class="fas fa-chalkboard-teacher"></i> Upcoming Workshops</h3>
                            <ul class="workshop-list">
                                <li class="workshop-item">
                                    <div class="workshop-info">
                                        <h4>Healthy Meal Prep Basics</h4>
                                        <p>Learn how to prepare nutritious meals for the week</p>
                                        <span class="workshop-date">June 15, 2024 - 2:00 PM</span>
                                    </div>
                                    <a href="#" class="register-btn">Register</a>
                                </li>
                                <li class="workshop-item">
                                    <div class="workshop-info">
                                        <h4>Understanding Nutrition Labels</h4>
                                        <p>Master reading and understanding food labels</p>
                                        <span class="workshop-date">June 20, 2024 - 3:00 PM</span>
                                    </div>
                                    <a href="#" class="register-btn">Register</a>
                                </li>
                                <li class="workshop-item">
                                    <div class="workshop-info">
                                        <h4>Balanced Diet Planning</h4>
                                        <p>Create your personalized balanced diet plan</p>
                                        <span class="workshop-date">June 25, 2024 - 1:00 PM</span>
                                    </div>
                                    <a href="#" class="register-btn">Register</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script>
        // Chat functionality
        document.querySelector('.chat-input button').addEventListener('click', function() {
            const input = document.querySelector('.chat-input input');
            const message = input.value.trim();
            
            if (message) {
                const chatMessages = document.querySelector('.chat-messages');
                const messageElement = document.createElement('p');
                messageElement.textContent = message;
                messageElement.style.textAlign = 'right';
                messageElement.style.margin = '10px 0';
                chatMessages.appendChild(messageElement);
                input.value = '';
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        });

        // Workshop registration
        document.querySelectorAll('.register-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.textContent === 'Register') {
                    e.preventDefault();
                    const workshopTitle = this.parentElement.querySelector('h4').textContent;
                    if (confirm(`Would you like to register for "${workshopTitle}"?`)) {
                        this.textContent = 'Registered';
                        this.style.background = 'var(--secondary-color)';
                    }
                }
            });
        });
    </script>
</body>
</html> 