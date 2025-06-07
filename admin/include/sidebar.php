<?php
// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

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
            <span>Entertainment Management</span>
        </li>
        
        <li class="<?php echo $current_page === 'create_quiz.php' ? 'active' : ''; ?>">
            <a href="create_quiz.php">
            <i class="fas fa-tasks"></i>    
            <span>Manage Quizzes</span>
            </a>
        </li>
        <li class="<?php echo $current_page === 'add_lecture_videos.php' ? 'active' : ''; ?>">
            <a href="add_lecture_videos.php">
            <i class="bi bi-play-btn-fill"></i>
            <span>Add Lecture Videos</span>
            </a>
        </li>
      
        
        <!-- <li class="<?php echo $current_page === 'setup_health_quiz.php' ? 'active' : ''; ?>">
            <a href="setup_health_quiz.php">
                <i class="fas fa-heartbeat"></i>
                <span>Health Quiz Setup</span>
            </a>
        </li> -->
        
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
        
 
        <!-- Announcement & News -->
        <li class="menu-section">
            <span>Announcement & News</span>
        </li>
        
        <li class="<?php echo $current_page === 'Announcement.php' ? 'active' : ''; ?>">
                    <a href="Announcement.php">
                    <i class="bi bi-bell-fill"></i>
                        <span>Manage Announcement</span>
                    </a>
                </li>
            
        <li class="<?php echo $current_page === 'News.php' ? 'active' : ''; ?>">
            <a href="News.php">
            <i class="bi bi-newspaper"></i>
                <span>Manage News</span>
            </a>
        </li>


        <!-- Events -->
        <li class="menu-section">
            <span>Events</span>
        </li>

        <li class="<?php echo $current_page === 'Events.php' ? 'active' : ''; ?>">
            <a href="Events.php">
            <i class="bi bi-calendar-date"></i>
                <span>Manage Events</span>
            </a>
        </li>
        
       <li class="menu-section">
            <span>Reports & Feedbacks</span>
        </li>
        
      
        <li class="<?php echo $current_page === 'reports.php' ? 'active' : ''; ?>">
            <a href="reports.php">
                <i class="fas fa-file-alt"></i>
                <span>Reports</span>
            </a>
        </li>
          <li class="<?php echo $current_page === 'Feedback.php' ? 'active' : ''; ?>">
            <a href="Feedback.php">
               <i class="bi bi-chat-square-quote-fill"></i>
                <span>Feedbacks</span>
            </a>
        </li>


        <!-- Settings -->
        <li class="menu-section">
            <span>Settings</span>
        </li>
        
        <li class="<?php echo $current_page === 'logs.php' ? 'active' : ''; ?>">
            <a href="logs.php">
            <i class="bi bi-clock"></i>
                <span>Activity logs</span>
            </a>
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