<?php
session_start();
include '../db_connection/database.php';

// Check if user is logged in and is an admin


// Get admin name
$admin_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$admin_name = $admin['first_name'] . ' ' . $admin['last_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - EWell</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #45a049;
            --accent-color: #2196F3;
            --text-color: #333;
            --light-bg: #f8f9fa;
            --border-color: #dee2e6;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: var(--text-color);
        }

        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: white !important;
        }

        .admin-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: white;
            border-bottom: 2px solid var(--border-color);
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .feature-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .welcome-message {
            font-size: 1.2rem;
            margin-bottom: 0;
        }

        .admin-name {
            font-weight: bold;
            color: white;
        }

        .logout-btn {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            background-color: rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background-color: rgba(255,255,255,0.3);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">EWell Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">View Site</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link logout-btn" href="../logout.php">Logout</a>
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
                <div class="col-md-4 text-end">
                    <a href="setup_health_quiz.php" class="btn btn-light">Setup Health Quiz</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <!-- Quiz Management -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quiz Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-clipboard-list feature-icon"></i>
                        </div>
                        <p class="card-text">Create and manage quizzes, questions, and answers for your wellness platform.</p>
                        <div class="d-grid gap-2">
                            <a href="create_quiz.php" class="btn btn-primary">Create New Quiz</a>
                            <a href="manage_quizzes.php" class="btn btn-outline-primary">Manage Existing Quizzes</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Management -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">User Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-users feature-icon"></i>
                        </div>
                        <p class="card-text">View and manage user accounts, track progress, and monitor engagement.</p>
                        <div class="d-grid gap-2">
                            <a href="manage_users.php" class="btn btn-primary">View All Users</a>
                            <a href="user_progress.php" class="btn btn-outline-primary">Track User Progress</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Analytics & Reports</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-chart-bar feature-icon"></i>
                        </div>
                        <p class="card-text">Access detailed analytics and generate reports on user engagement and quiz performance.</p>
                        <div class="d-grid gap-2">
                            <a href="analytics.php" class="btn btn-primary">View Analytics</a>
                            <a href="reports.php" class="btn btn-outline-primary">Generate Reports</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Platform Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-cog feature-icon"></i>
                        </div>
                        <p class="card-text">Configure platform settings, manage content, and customize the user experience.</p>
                        <div class="d-grid gap-2">
                            <a href="settings.php" class="btn btn-primary">General Settings</a>
                            <a href="content_management.php" class="btn btn-outline-primary">Content Management</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 