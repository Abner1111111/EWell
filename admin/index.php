<?php
session_start();
include '../db_connection/database.php';
include '../back_end/session.php';  

$admin_name = 'Admin'; 
$admin_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if ($admin) {
    $admin_name = $admin['first_name'] . ' ' . $admin['last_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - EWell</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_sidebar.css">
    <link rel="stylesheet" href="../css/admin_index.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include "include/sidebar.php";?>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-heartbeat"></i>
                EWell Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
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

    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 mb-3">Welcome, <span class="admin-name"><?php echo htmlspecialchars($admin_name); ?></span></h1>
                    <p class="welcome-message">Manage your wellness platform with ease</p>
                </div>
               
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Statistics Cards -->
        <div class="card mb-4">
         
            <div class="card-body">
                <div class="row">
                    <?php
                    // Get total users
                    $userQuery = "SELECT COUNT(*) as total FROM users WHERE role = 'User'";
                    $userResult = mysqli_query($conn, $userQuery);
                    $totalUsers = mysqli_fetch_assoc($userResult)['total'];

                    // Get total quizzes
                    $quizQuery = "SELECT COUNT(*) as total FROM quizzes";
                    $quizResult = mysqli_query($conn, $quizQuery);
                    $totalQuizzes = mysqli_fetch_assoc($quizResult)['total'];

                    // Get total announcements
                    $announcementQuery = "SELECT COUNT(*) as total FROM announcements";
                    $announcementResult = mysqli_query($conn, $announcementQuery);
                    $totalAnnouncements = mysqli_fetch_assoc($announcementResult)['total'];

                    // Get total news articles
                    $newsQuery = "SELECT COUNT(*) as total FROM news_articles";
                    $newsResult = mysqli_query($conn, $newsQuery);
                    $totalNews = mysqli_fetch_assoc($newsResult)['total'];
                    ?>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stats-info">
                                <h3><?php echo $totalUsers; ?></h3>
                                <p>Total Users</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div class="stats-info">
                                <h3><?php echo $totalQuizzes; ?></h3>
                                <p>Total Quizzes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="stats-info">
                                <h3><?php echo $totalAnnouncements; ?></h3>
                                <p>Announcements</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <div class="stats-info">
                                <h3><?php echo $totalNews; ?></h3>
                                <p>News Articles</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-chart-line me-2"></i>User Activity
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="userActivityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-chart-bar me-2"></i>Quiz Performance
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="quizPerformanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions and Recent Activity -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-bolt me-2"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <a href="create_quiz.php" class="btn btn-primary">
                                <i class="fas fa-plus-circle"></i>
                                Create New Quiz
                            </a>
                            <a href="manage_users.php" class="btn btn-info text-white">
                                <i class="fas fa-users"></i>
                                Manage Users
                            </a>
                            <a href="Announcement.php" class="btn btn-success">
                                <i class="fas fa-bullhorn"></i>
                                Post Announcement
                            </a>
                            <a href="News.php" class="btn btn-warning text-dark">
                                <i class="fas fa-newspaper"></i>
                                Add News Article
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">
                            <i class="fas fa-history me-2"></i>Recent Activity
                        </h5>
                        <div class="activity-filters">
                            <select class="form-select form-select-sm">
                                <option value="all">All Activities</option>
                                <option value="quiz">Quizzes</option>
                                <option value="user">Users</option>
                                <option value="announcement">Announcements</option>
                                <option value="news">News</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="activity-feed">
                            <?php
                            // Get recent activities
                            $activityQuery = "SELECT 
                                'quiz' as type, title, created_at, 'Quiz created' as action, 'primary' as color
                                FROM quizzes 
                                UNION ALL
                                SELECT 'user' as type, CONCAT(first_name, ' ', last_name) as title, created_at, 'New user registered' as action, 'success' as color
                                FROM users 
                                WHERE role = 'User'
                                UNION ALL
                                SELECT 'announcement' as type, title, created_at, 'New announcement' as action, 'warning' as color
                                FROM announcements
                                UNION ALL
                                SELECT 'news' as type, title, created_at, 'News article published' as action, 'info' as color
                                FROM news_articles
                                ORDER BY created_at DESC LIMIT 5";
                            
                            $activityResult = mysqli_query($conn, $activityQuery);
                            while ($activity = mysqli_fetch_assoc($activityResult)):
                                $timeAgo = time_elapsed_string($activity['created_at']);
                            ?>
                            <div class="activity-item" data-type="<?php echo $activity['type']; ?>">
                                <div class="activity-icon bg-<?php echo $activity['color']; ?>-light">
                                    <?php
                                    switch($activity['type']) {
                                        case 'quiz':
                                            echo '<i class="fas fa-clipboard-list"></i>';
                                            break;
                                        case 'user':
                                            echo '<i class="fas fa-user"></i>';
                                            break;
                                        case 'announcement':
                                            echo '<i class="fas fa-bullhorn"></i>';
                                            break;
                                        case 'news':
                                            echo '<i class="fas fa-newspaper"></i>';
                                            break;
                                    }
                                    ?>
                                </div>
                                <div class="activity-content">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="activity-title"><?php echo htmlspecialchars($activity['action']); ?></h6>
                                            <p class="activity-desc"><?php echo htmlspecialchars($activity['title']); ?></p>
                                        </div>
                                        <span class="activity-time" title="<?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?>">
                                            <?php echo $timeAgo; ?>
                                        </span>
                                    </div>
                                    <div class="activity-meta">
                                        <span class="badge bg-<?php echo $activity['color']; ?>-light text-<?php echo $activity['color']; ?>">
                                            <?php echo ucfirst($activity['type']); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // User Activity Chart
        const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
        new Chart(userActivityCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Active Users',
                    data: [65, 78, 90, 85, 95, 100],
                    borderColor: '#8CB369',
                    backgroundColor: 'rgba(139, 179, 105, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Quiz Performance Chart
        const quizPerformanceCtx = document.getElementById('quizPerformanceChart').getContext('2d');
        new Chart(quizPerformanceCtx, {
            type: 'bar',
            data: {
                labels: ['Quiz 1', 'Quiz 2', 'Quiz 3', 'Quiz 4', 'Quiz 5'],
                datasets: [{
                    label: 'Average Score',
                    data: [75, 82, 68, 90, 85],
                    backgroundColor: '#5B8E7D',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>

<?php
// Add this function at the bottom of the file, before the closing PHP tag
function time_elapsed_string($datetime) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->d > 7) {
        return date('M d, Y', strtotime($datetime));
    }
    
    if ($diff->d > 0) {
        return $diff->d . 'd ago';
    }
    if ($diff->h > 0) {
        return $diff->h . 'h ago';
    }
    if ($diff->i > 0) {
        return $diff->i . 'm ago';
    }
    return 'Just now';
}
?> 