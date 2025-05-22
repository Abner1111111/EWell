<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EWell - Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>EWell</h2>
            </div>
            <ul class="nav-links">
                <li>
                    <a href="dashboard.php" class="active">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li>
                    <a href="relaxation.php">
                        <i class="fas fa-spa"></i>
                        <span>Relaxation Library</span>
                    </a>
                </li>
                <li>
                    <a href="breathing.php">
                        <i class="fas fa-wind"></i>
                        <span>Breathing</span>
                    </a>
                </li>
                <li>
                    <a href="journal.php">
                        <i class="fas fa-book"></i>
                        <span>Journal</span>
                    </a>
                </li>
                <li>
                    <a href="soundscapes.php">
                        <i class="fas fa-music"></i>
                        <span>Soundscapes</span>
                    </a>
                </li>
                <li>
                    <a href="health_quiz.php">
                        <i class="fas fa-question-circle"></i>
                        <span>Health Quiz</span>
                    </a>
                </li>
                <li>
                    <a href="tools.php">
                        <i class="fas fa-tools"></i>
                        <span>Tools</span>
                    </a>
                </li>

                <li>
                    <a href="settings.php">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <h1>Welcome to EWell</h1>
                <div class="user-info">
                    <span class="user-name"><?php echo $_SESSION['user_name'] ?? 'User'; ?></span>
                    <a href="../logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </header>

            <div class="dashboard-grid">
                <!-- Quick Access Cards -->
                <div class="dashboard-card">
                    <h3>Today's Focus</h3>
                    <div class="focus-content">
                        <p>Take a moment to breathe and center yourself.</p>
                        <a href="breathing.php" class="action-btn">Start Breathing Exercise</a>
                    </div>
                </div>

                <div class="dashboard-card">
                    <h3>Mood Tracker</h3>
                    <div class="mood-content">
                        <p>How are you feeling today?</p>
                        <div class="mood-options">
                            <button class="mood-btn" data-mood="happy">😊</button>
                            <button class="mood-btn" data-mood="calm">😌</button>
                            <button class="mood-btn" data-mood="stressed">😰</button>
                            <button class="mood-btn" data-mood="tired">😴</button>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <h3>Health Quiz</h3>
                    <div class="quiz-content">
                        <p>Test your knowledge and learn more about wellness.</p>
                        <a href="health_quiz.php" class="action-btn">Take a Quiz</a>
                    </div>
                </div>

                <div class="dashboard-card">
                    <h3>Journal Entry</h3>
                    <div class="journal-content">
                        <p>Take a moment to reflect on your day.</p>
                        <a href="journal.php" class="action-btn">Write in Journal</a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../js/dashboard.js"></script>
</body>

</html>