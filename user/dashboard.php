<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EWell - Dashboard</title>
    <link rel="stylesheet" href="../css/variables.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(3, auto);
            gap: 20px;
            padding: 20px;
        }

        .dashboard-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .card-icon {
            font-size: 2rem;
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .wellness-content {
            margin-top: 15px;
        }

        .wellness-features {
            list-style: none;
            padding: 0;
            margin: 15px 0;
        }

        .wellness-features li {
            margin: 8px 0;
            display: flex;
            align-items: center;
        }

        .wellness-features li i {
            color: var(--primary-color);
            margin-right: 10px;
        }

        .action-btn {
            display: inline-block;
            padding: 8px 20px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .action-btn:hover {
            background-color: var(--primary-dark);
        }

        .wellness-overview {
            grid-column: 1 / -1;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .wellness-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-card i {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .stat-info h3 {
            font-size: 1rem;
            margin: 0;
        }

        .stat-info p {
            margin: 5px 0 0;
            color: #666;
        }

        .wellness-tips {
            grid-column: 1 / -1;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .tips-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .tip-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .tip-card i {
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .tip-card p {
            margin: 0;
            font-size: 0.9rem;
        }

        .financial-chart-container {
            margin-top: 15px;
            height: 200px;
            position: relative;
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            .wellness-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            .tips-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <?php include('includes/header.php'); ?>

        <!-- Main Content -->
        <main class="main-content">
         
            <div class="dashboard-grid">
                <!-- Row 1: Wellness Overview -->
                <section class="wellness-overview">
                    <h2>Your Wellness Journey</h2>
                    <div class="wellness-stats">
                        <div class="stat-card">
                            <i class="fas fa-book"></i>
                            <div class="stat-info">
                                <h3>Journal Entries</h3>
                                <p>0</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-spa"></i>
                            <div class="stat-info">
                                <h3>Relaxation Sessions</h3>
                                <p>0</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-lightbulb"></i>
                            <div class="stat-info">
                                <h3>Quiz Points</h3>
                                <p>0</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-utensils"></i>
                            <div class="stat-info">
                                <h3>Nutrition Logs</h3>
                                <p>0</p>
                            </div>
                        </div>
                    </div>
                </section>

 
                <div class="dashboard-card financial-wellness">
                    <div class="card-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3>Financial Well-being</h3>
                    <div class="wellness-content">
                        <p>Manage your finances and achieve financial stability.</p>
                        <div class="financial-chart-container">
                            <canvas id="financialChart"></canvas>
                        </div>
                       
                        <a href="financial_wellness.php" class="action-btn">View Finances</a>
                    </div>
                </div>

                <div class="dashboard-card motivational-quotes">
                    <div class="card-icon">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <h3>Daily Inspiration</h3>
                    <div class="wellness-content">
                        <div class="quote-container">
                            <p class="quote-text">"The only way to do great work is to love what you do."</p>
                            <p class="quote-author">- Steve Jobs</p>
                        </div>
                        <div class="quote-controls">
                            <button class="quote-btn" onclick="previousQuote()">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="quote-btn" onclick="nextQuote()">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Mental and Social Wellness -->
                <div class="dashboard-card mental-wellness">
                    <div class="card-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3>Emotional & Mental Well-being</h3>
                    <div class="wellness-content">
                        <p>Nurture your mental health and emotional balance.</p>
                        <ul class="wellness-features">
                            <li><i class="fas fa-check"></i> Mood Tracker</li>
                            <li><i class="fas fa-check"></i> Meditation Sessions</li>
                            <li><i class="fas fa-check"></i> Stress Management</li>
                        </ul>
                        <a href="mental_wellness.php" class="action-btn">Start Meditation</a>
                    </div>
                </div>

                <div class="dashboard-card social-wellness">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Social Well-being</h3>
                    <div class="wellness-content">
                        <p>Build meaningful connections and community support.</p>
                        <ul class="wellness-features">
                            <li><i class="fas fa-check"></i> Community Events</li>
                            <li><i class="fas fa-check"></i> Support Groups</li>
                            <li><i class="fas fa-check"></i> Social Activities</li>
                        </ul>
                        <a href="social_wellness.php" class="action-btn">Join Community</a>
                    </div>
                </div>

                <!-- Row 4: Wellness Tips -->
                <section class="wellness-tips">
                    <h2>Today's Wellness Tips</h2>
                    <div class="tips-container">
                        <div class="tip-card">
                            <i class="fas fa-lightbulb"></i>
                            <p>Take a 5-minute break every hour to stretch and refresh your mind.</p>
                        </div>
                        <div class="tip-card">
                            <i class="fas fa-lightbulb"></i>
                            <p>Practice mindful breathing for 2 minutes to reduce stress.</p>
                        </div>
                        <div class="tip-card">
                            <i class="fas fa-lightbulb"></i>
                            <p>Stay hydrated by drinking at least 8 glasses of water today.</p>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script src="../js/dashboard.js"></script>
    <script>
        // Initialize the financial chart
        const ctx = document.getElementById('financialChart').getContext('2d');
        
        // Sample data - replace with actual data from your backend
        const data = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [
                {
                    label: 'Budget',
                    data: [5000, 5500, 5200, 5800, 6000, 5500],
                    borderColor: '#36A2EB',
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Actual',
                    data: [4800, 5200, 5100, 5600, 5800, 5300],
                    borderColor: '#FF6384',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        };

        new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toFixed(0);
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 10
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.raw || 0;
                                return `${label}: ₱${value.toFixed(0)}`;
                            }
                        }
                    }
                }
            }
        });
        const quotes = [
            {
                text: "The only way to do great work is to love what you do.",
                author: "Steve Jobs"
            },
            {
                text: "Believe you can and you're halfway there.",
                author: "Theodore Roosevelt"
            },
            {
                text: "Success is not final, failure is not fatal: it is the courage to continue that counts.",
                author: "Winston Churchill"
            },
            {
                text: "The future belongs to those who believe in the beauty of their dreams.",
                author: "Eleanor Roosevelt"
            },
            {
                text: "Don't watch the clock; do what it does. Keep going.",
                author: "Sam Levenson"
            }
        ];

        let currentQuoteIndex = 0;

        function updateQuote() {
            const quote = quotes[currentQuoteIndex];
            document.querySelector('.quote-text').textContent = `"${quote.text}"`;
            document.querySelector('.quote-author').textContent = `- ${quote.author}`;
        }

        function nextQuote() {
            currentQuoteIndex = (currentQuoteIndex + 1) % quotes.length;
            updateQuote();
        }

        function previousQuote() {
            currentQuoteIndex = (currentQuoteIndex - 1 + quotes.length) % quotes.length;
            updateQuote();
        }

        // Initialize with first quote
        updateQuote();
    </script>
</body>

</html>