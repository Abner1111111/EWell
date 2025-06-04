<?php
session_start();
include '../db_connection/database.php';
include '../back_end/session.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: ../main/login.php');
    exit;
}

// Fetch all users with their activity status and role
$sql = "SELECT 
            u.id,
            u.first_name,
            u.last_name,
            u.email,
            u.role,
            u.last_login,
            u.created_at,
            (SELECT COUNT(*) FROM journal_entries WHERE user_id = u.id) as journal_entries,
            (SELECT COUNT(*) FROM quiz_results WHERE user_id = u.id) as total_points,
            (SELECT COUNT(*) FROM relaxation_sessions WHERE user_id = u.id) as relaxation_sessions,
            (SELECT COUNT(*) FROM nutrition_logs WHERE user_id = u.id) as nutrition_logs
        FROM users u
        WHERE u.role = 'User'
        ORDER BY u.last_login DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EWell - User Progress Tracking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="../css/admin_sidebar.css">
    <link rel="stylesheet" href="../css/admin_create_quiz.css">
    <link rel="stylesheet" href="../css/variables.css">
    <link rel="stylesheet" href="../css/user_progress.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include 'include/sidebar.php'; ?>

        <main class="main-content">
            <?php include 'include/header.php'; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="quiz-form">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-chart-line me-2"></i>
                                    <h5 class="card-title mb-0">User Progress Tracking</h5>
                                </div>
                                <div class="d-flex gap-3">
                                    <div class="search-box">
                                        <i class="fas fa-search" style="color:#0d1321;"></i>
                                        <input type="text" id="searchInput" placeholder="Search users...">
                                    </div>
                                    <select class="filter-select" id="roleFilter">
                                        <option value="">All Roles</option>
                                        <option value="Admin">Admin</option>
                                        <option value="User">User</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="user-grid">
                                    <?php while ($user = $result->fetch_assoc()): ?>
                                        <div class="user-card">
                                          
                                            
                                            <div class="user-header">
                                                <div class="user-info">
                                                    <div class="user-avatar">
                                                        <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                                                    </div>
                                                    <div class="user-details">
                                                        <h3><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h3>
                                                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                                                    </div>
                                                </div>
                                                <span class="user-role role-<?php echo strtolower($user['role']); ?>">
                                                    <?php echo htmlspecialchars($user['role']); ?>
                                                </span>
                                            </div>

                                            <div class="activity-stats">
                                                <div class="stat-item">
                                                    <i class="fas fa-book"></i>
                                                    <div class="stat-value"><?php echo $user['journal_entries']; ?></div>
                                                    <div class="stat-label">Journal Entries</div>
                                                </div>
                                                <div class="stat-item">
                                                <i class="bi bi-lightbulb-fill"></i>
                                                    <div class="stat-value"><?php echo $user['total_points']; ?></div>
                                                    <div class="stat-label">Quiz points</div>
                                                </div>
                                                <div class="stat-item">
                                                    <i class="fas fa-spa"></i>
                                                    <div class="stat-value"><?php echo $user['relaxation_sessions']; ?></div>
                                                    <div class="stat-label">Relaxation Sessions</div>
                                                </div>
                                                <div class="stat-item">
                                                    <i class="fas fa-utensils"></i>
                                                    <div class="stat-value"><?php echo $user['nutrition_logs']; ?></div>
                                                    <div class="stat-label">Nutrition Logs</div>
                                                </div>
                                            </div>

                                            <div class="progress-chart">
                                                <div class="chart-bar">
                                                    <div class="chart-fill" style="width: <?php echo min(($user['journal_entries'] + $user['quiz_attempts'] + $user['relaxation_sessions'] + $user['nutrition_logs']) * 10, 100); ?>%"></div>
                                                </div>
                                            </div>

                                            <div class="last-activity">
                                                <span class="status-indicator <?php echo ($user['last_login'] && strtotime($user['last_login']) > strtotime('-24 hours')) ? 'status-active' : 'status-inactive'; ?>"></span>
                                                Last active: <?php echo $user['last_login'] ? date('M d, Y H:i', strtotime($user['last_login'])) : 'Never'; ?>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const userCards = document.querySelectorAll('.user-card');

        function filterUsers() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedRole = roleFilter.value.toLowerCase();

            userCards.forEach(card => {
                const userName = card.querySelector('h3').textContent.toLowerCase();
                const userEmail = card.querySelector('p').textContent.toLowerCase();
                const userRole = card.querySelector('.user-role').textContent.toLowerCase();

                const matchesSearch = userName.includes(searchTerm) || userEmail.includes(searchTerm);
                const matchesRole = !selectedRole || userRole === selectedRole;

                card.style.display = matchesSearch && matchesRole ? 'block' : 'none';
            });
        }

        searchInput.addEventListener('input', filterUsers);
        roleFilter.addEventListener('change', filterUsers);

        // Animate stat values
        document.querySelectorAll('.stat-value').forEach(stat => {
            const value = parseInt(stat.textContent);
            let current = 0;
            const increment = value / 20;
            const duration = 1000;
            const interval = duration / 20;

            const counter = setInterval(() => {
                current += increment;
                if (current >= value) {
                    stat.textContent = value;
                    clearInterval(counter);
                } else {
                    stat.textContent = Math.floor(current);
                }
            }, interval);
        });

        // Animate progress bars
        document.querySelectorAll('.chart-fill').forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });

        // Action buttons functionality
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.title.toLowerCase();
                const userCard = this.closest('.user-card');
                const userName = userCard.querySelector('h3').textContent;
                
                if (action.includes('view')) {
                    // Implement view details functionality
                    alert(`Viewing details for ${userName}`);
                } else if (action.includes('export')) {
                    // Implement export functionality
                    alert(`Exporting data for ${userName}`);
                }
            });
        });
    </script>
</body>
</html> 