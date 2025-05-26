<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EWell - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_sidebar.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'include/sidebar.php'; ?>

        <main class="main-content">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <a class="navbar-brand" href="index.php">
                            <i class="fas fa-heartbeat me-2"></i>EWell Admin
                        </a>
                        <button class="btn btn-link d-md-none" id="sidebarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="../index.php">
                                    <i class="fas fa-external-link-alt me-1"></i>View Site
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../logout.php">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid">
                <header class="content-header">
                    <h1>Welcome to EWell</h1>
                    <div class="user-info">
                        <span class="user-name"><?php echo $_SESSION['user_name'] ?? 'User'; ?></span>
                    </div>
                </header>

                <!-- Wellness Overview -->
                <section class="wellness-overview">
                    <h2>Your Wellness Journey</h2>
                    <div class="wellness-stats">
                        <div class="stat-card">
                            <i class="fas fa-heartbeat"></i>
                            <div class="stat-info">
                                <h3>Physical Activity</h3>
                                <p>30 minutes today</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-brain"></i>
                            <div class="stat-info">
                                <h3>Mental Wellness</h3>
                                <p>15 minutes meditation</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-coins"></i>
                            <div class="stat-info">
                                <h3>Financial Goals</h3>
                                <p>On track</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-users"></i>
                            <div class="stat-info">
                                <h3>Social Connections</h3>
                                <p>3 activities this week</p>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="dashboard-grid">
                    <!-- Physical Well-being Card -->
                    <div class="dashboard-card physical-wellness">
                        <div class="card-icon">
                            <i class="fas fa-dumbbell"></i>
                        </div>
                        <h3>Physical Well-being</h3>
                        <div class="wellness-content">
                            <p>Track your fitness journey and maintain a healthy lifestyle.</p>
                            <ul class="wellness-features">
                                <li><i class="fas fa-check"></i> Daily Exercise Tracker</li>
                                <li><i class="fas fa-check"></i> Nutrition Guide</li>
                                <li><i class="fas fa-check"></i> Sleep Monitor</li>
                            </ul>
                            <a href="physical_wellness.php" class="action-btn">Start Workout</a>
                        </div>
                    </div>

                    <!-- Financial Well-being Card -->
                    <div class="dashboard-card financial-wellness">
                        <div class="card-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h3>Financial Well-being</h3>
                        <div class="wellness-content">
                            <p>Manage your finances and achieve financial stability.</p>
                            <ul class="wellness-features">
                                <li><i class="fas fa-check"></i> Budget Planner</li>
                                <li><i class="fas fa-check"></i> Savings Goals</li>
                                <li><i class="fas fa-check"></i> Financial Tips</li>
                            </ul>
                            <a href="financial_wellness.php" class="action-btn">View Finances</a>
                        </div>
                    </div>

                    <!-- Emotional & Mental Well-being Card -->
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

                    <!-- Social Well-being Card -->
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
                </div>

                <!-- Daily Wellness Tips -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin_sidebar.js"></script>
</body>

</html>