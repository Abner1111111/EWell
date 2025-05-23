<?php
// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="admin-sidebar">
    <div class="sidebar-header">
        <i class="fas fa-user-shield"></i>
        <span>Admin Panel</span>
    </div>
    
    <ul class="sidebar-menu">
        <li class="<?php echo $current_page === 'index.php' ? 'active' : ''; ?>">
            <a href="index.php">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li class="menu-section">
            <span>Quiz Management</span>
        </li>
        
        <li class="<?php echo $current_page === 'create_quiz.php' ? 'active' : ''; ?>">
            <a href="create_quiz.php">
                <i class="fas fa-plus-circle"></i>
                <span>Create Quiz</span>
            </a>
        </li>
        
        <li class="<?php echo $current_page === 'manage_quizzes.php' ? 'active' : ''; ?>">
            <a href="manage_quizzes.php">
                <i class="fas fa-tasks"></i>
                <span>Manage Quizzes</span>
            </a>
        </li>
        
        <li class="<?php echo $current_page === 'setup_health_quiz.php' ? 'active' : ''; ?>">
            <a href="setup_health_quiz.php">
                <i class="fas fa-heartbeat"></i>
                <span>Health Quiz Setup</span>
            </a>
        </li>
        
        <li class="menu-section">
            <span>User Management</span>
        </li>
        
        <li class="<?php echo $current_page === 'manage_user.php' ? 'active' : ''; ?>">
            <a href="manage_user.php">
                <i class="fas fa-users"></i>
                <span>Manage Users</span>
            </a>
        </li>
        
        <li class="<?php echo $current_page === 'user_progress.php' ? 'active' : ''; ?>">
            <a href="user_progress.php">
                <i class="fas fa-chart-line"></i>
                <span>User Progress</span>
            </a>
        </li>
        
        <li class="menu-section">
            <span>Analytics & Reports</span>
        </li>
        
        <li class="<?php echo $current_page === 'analytics.php' ? 'active' : ''; ?>">
            <a href="analytics.php">
                <i class="fas fa-chart-bar"></i>
                <span>Analytics</span>
            </a>
        </li>
        
        <li class="<?php echo $current_page === 'reports.php' ? 'active' : ''; ?>">
            <a href="reports.php">
                <i class="fas fa-file-alt"></i>
                <span>Reports</span>
            </a>
        </li>
        
        <li class="menu-section">
            <span>Settings</span>
        </li>
        
        <li class="<?php echo $current_page === 'settings.php' ? 'active' : ''; ?>">
            <a href="settings.php">
                <i class="fas fa-cog"></i>
                <span>General Settings</span>
            </a>
        </li>
        
        <li class="<?php echo $current_page === 'content_management.php' ? 'active' : ''; ?>">
            <a href="content_management.php">
                <i class="fas fa-edit"></i>
                <span>Content Management</span>
            </a>
        </li>
    </ul>
</div>
<script src="../js/admin_sidebar.js"></script>