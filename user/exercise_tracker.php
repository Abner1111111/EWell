<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EWell - Exercise Tracker</title>
    <link rel="stylesheet" href="../css/exercise_tracker.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="exercise-container">
        <!-- Sidebar Navigation -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>EWell</h2>
            </div>
            <ul class="nav-links">
                <li>
                    <a href="dashboard.php">
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
                <h1>Exercise Tracker</h1>
                <div class="user-info">
                    <span class="user-name"><?php echo $_SESSION['user_name'] ?? 'User'; ?></span>
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </header>

            <div class="exercise-grid">
                <!-- Exercise Summary -->
                <section class="exercise-summary">
                    <h2>Today's Summary</h2>
                    <div class="summary-cards">
                        <div class="summary-card">
                            <i class="fas fa-fire"></i>
                            <div class="summary-info">
                                <h3>Calories Burned</h3>
                                <p class="calories">0 kcal</p>
                            </div>
                        </div>
                        <div class="summary-card">
                            <i class="fas fa-clock"></i>
                            <div class="summary-info">
                                <h3>Total Duration</h3>
                                <p class="duration">0 min</p>
                            </div>
                        </div>
                        <div class="summary-card">
                            <i class="fas fa-dumbbell"></i>
                            <div class="summary-info">
                                <h3>Exercises</h3>
                                <p class="exercise-count">0</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- New Exercise Form -->
                <section class="new-exercise-section">
                    <h2>Log Exercise</h2>
                    <form id="exerciseForm" class="exercise-form">
                        <div class="form-group">
                            <label for="exerciseType">Exercise Type</label>
                            <select id="exerciseType" name="exerciseType" required>
                                <option value="">Select exercise type</option>
                                <option value="running">Running</option>
                                <option value="walking">Walking</option>
                                <option value="cycling">Cycling</option>
                                <option value="swimming">Swimming</option>
                                <option value="yoga">Yoga</option>
                                <option value="strength">Strength Training</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="duration">Duration (minutes)</label>
                            <input type="number" id="duration" name="duration" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="intensity">Intensity Level</label>
                            <select id="intensity" name="intensity" required>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea id="notes" name="notes" rows="3" placeholder="Add any notes about your exercise..."></textarea>
                        </div>
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-plus"></i>
                            Log Exercise
                        </button>
                    </form>
                </section>

                <!-- Exercise History -->
                <section class="exercise-history">
                    <div class="history-header">
                        <h2>Exercise History</h2>
                        <div class="history-filter">
                            <select id="filterType">
                                <option value="">All Types</option>
                                <option value="running">Running</option>
                                <option value="walking">Walking</option>
                                <option value="cycling">Cycling</option>
                                <option value="swimming">Swimming</option>
                                <option value="yoga">Yoga</option>
                                <option value="strength">Strength Training</option>
                                <option value="other">Other</option>
                            </select>
                            <input type="date" id="filterDate">
                        </div>
                    </div>
                    <div class="history-list">
                        <!-- Sample Exercise Entry -->
                        <article class="exercise-entry">
                            <div class="entry-header">
                                <h3>Morning Run</h3>
                                <span class="entry-date">March 15, 2024</span>
                            </div>
                            <div class="entry-details">
                                <div class="detail">
                                    <i class="fas fa-clock"></i>
                                    <span>30 minutes</span>
                                </div>
                                <div class="detail">
                                    <i class="fas fa-fire"></i>
                                    <span>250 kcal</span>
                                </div>
                                <div class="detail">
                                    <i class="fas fa-bolt"></i>
                                    <span>High Intensity</span>
                                </div>
                            </div>
                            <p class="entry-notes">Great morning run along the beach!</p>
                            <div class="entry-actions">
                                <button class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </button>
                                <button class="action-btn delete-btn">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </div>
                        </article>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script src="../js/exercise_tracker.js"></script>
</body>
</html> 