<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Ewell</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <a href="index.html" class="logo">E<span>well</span></a>
            </div>
            <ul class="nav-links">
                <li class="active">
                    <a href="#"><i class="fas fa-home"></i> Dashboard</a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-chart-line"></i> Progress</a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-utensils"></i> Nutrition</a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-dumbbell"></i> Workouts</a>
                </li>
                <li>
                    <a href="tools.php"><i class="fas fa-clock"></i>Tools</a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-cog"></i> Settings</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                <div class="user-menu">
                    <i class="fas fa-bell"></i>
                    <div class="settings-wrapper">
                        <i class="fas fa-cog settings-icon"></i>
                        <div class="settings-dropdown">
                            <div class="dropdown-header">
                                <img src="../main/image/pic/avatar.jpg" alt="User Avatar" class="avatar">
                                <div class="user-info">
                                    <span class="user-name">John Doe</span>
                                    <span class="user-email">john.doe@example.com</span>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <ul class="dropdown-menu">
                                <li><a href="#"><i class="fas fa-user"></i> Profile</a></li>
                                <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
                                <li><a href="#"><i class="fas fa-moon"></i> Dark Mode</a></li>
                                <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <div class="welcome-text">
                        <h1>Welcome back, <span class="user-name">John</span>!</h1>
                        <p>Track your wellness journey and achieve your goals</p>
                    </div>
                    <div class="date-info">
                        <i class="fas fa-calendar"></i>
                        <span id="current-date"></span>
                    </div>
                </div>


                <!-- Stats Cards -->
                <div class="stats-cards">
                    <div class="stat-card square">
                        <div class="stat-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Daily Calories</h3>
                            <p class="stat-value">1,850</p>
                            <p class="stat-target">Target: 2,000</p>
                        </div>
                    </div>
                    <div class="stat-card square">
                        <div class="stat-icon">
                            <i class="fas fa-walking"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Steps Today</h3>
                            <p class="stat-value">6,543</p>
                            <p class="stat-target">Target: 10,000</p>
                        </div>
                    </div>
                    <div class="stat-card rectangle">
                        <div class="profile-avatar">
                            <img src="../main/image/pic/avatar.jpg" alt="User Avatar">
                        </div>
                        <div class="profile-info">
                            <h2>John Doe</h2>
                            <p class="profile-role">Wellness Enthusiast</p>
                            <div class="profile-stats">
                                <div class="stat">
                                    <span class="stat-value">75</span>
                                    <span class="stat-label">kg</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-value">175</span>
                                    <span class="stat-label">cm</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-value">24</span>
                                    <span class="stat-label">BMI</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Card -->
                <div class="profile-card">



                    <div class="profile-progress">
                        <h3>Weekly Progress</h3>
                        <div class="progress-bars">
                            <div class="progress-item">
                                <div class="progress-label">
                                    <span>Workouts</span>
                                    <span>3/5</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress" style="width: 60%"></div>
                                </div>
                            </div>
                            <div class="progress-item">
                                <div class="progress-label">
                                    <span>Water Intake</span>
                                    <span>1.5/2L</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress" style="width: 75%"></div>
                                </div>
                            </div>
                            <div class="progress-item">
                                <div class="progress-label">
                                    <span>Sleep</span>
                                    <span>7.5/8h</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress" style="width: 94%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../js/dashboard.js"></script>
</body>

</html>