<!-- Header Navigation -->
<link rel="stylesheet" href="../../css/variables.css">
<style>
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 200px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        z-index: 1000;
        top: 100%;
        left: 0;
    }

    .dropdown:hover .dropdown-content {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    .dropdown-content a {
        color: var(--text-color);
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
    }

    .dropdown-content a:hover {
        background-color: var(--light-gray);
        color: var(--primary-color);
        transform: translateX(5px);
    }

    .dropdown-content a i {
        margin-right: 8px;
        width: 20px;
        text-align: center;
    }

    /* Announcement Dropdown Styles */
    .announcement-dropdown {
        margin-right: 20px;
    }

    .announcement-icon {
        position: relative;
        color: var(--text-color);
        font-size: 1.2rem;
        padding: 8px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .announcement-icon:hover {
        background-color: var(--light-gray);
        color: var(--primary-color);
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: #ff4444;
        color: white;
        font-size: 0.7rem;
        padding: 2px 6px;
        border-radius: 50%;
        min-width: 18px;
        text-align: center;
    }

    .announcement-content {
        min-width: 300px;
        padding: 0;
    }

    .announcement-header {
        padding: 15px;
        border-bottom: 1px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .announcement-header h3 {
        margin: 0;
        font-size: 1rem;
        color: var(--text-color);
    }

    .announcement-date {
        font-size: 0.8rem;
        color: var(--text-light);
    }

    .announcement-item {
        display: flex;
        align-items: flex-start;
        padding: 12px 15px;
        border-bottom: 1px solid var(--light-gray);
        transition: all 0.3s ease;
    }

    .announcement-item:hover {
        background-color: var(--light-gray);
    }

    .announcement-item i {
        margin-top: 3px;
        color: var(--primary-color);
    }

    .announcement-text {
        margin-left: 10px;
    }

    .announcement-text p {
        margin: 0;
        font-size: 0.9rem;
        color: var(--text-color);
    }

    .announcement-text small {
        color: var(--text-light);
        font-size: 0.8rem;
    }

    .announcement-footer {
        padding: 10px 15px;
        text-align: center;
        border-top: 1px solid var(--light-gray);
    }

    .view-all {
        color: var(--primary-color);
        font-size: 0.9rem;
        text-decoration: none;
    }

    .view-all:hover {
        text-decoration: underline;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<header class="main-header">
    <div class="header-left">
        <div class="logo">
            <i class="fas fa-heartbeat"></i>
            <h2>EWell</h2>
        </div>
        <button class="mobile-menu-btn" aria-label="Toggle navigation menu">
            <i class="fas fa-bars"></i>
        </button>
        <nav class="nav-menu">
            <ul class="nav-links">
                <li><a href="dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : ''; ?>>
                        <i class="fas fa-home"></i> Home
                    </a></li>
                <li><a href="journal.php" <?php echo basename($_SERVER['PHP_SELF']) == 'journal.php' ? 'class="active"' : ''; ?>>
                        <i class="fas fa-book"></i> Journal
                    </a></li>
                <li><a href="nutrition.php" <?php echo basename($_SERVER['PHP_SELF']) == 'nutrition.php' ? 'class="active"' : ''; ?>>
                        <i class=" fas fa-utensils"></i> Nutrition
                    </a></li>
                <li><a href="health_quiz.php" <?php echo basename($_SERVER['PHP_SELF']) == 'health_quiz.php' ? 'class="active"' : ''; ?>>
                        <i class=" fas fa-question-circle"></i> Health Quiz
                    </a></li>
                <li><a href="lecture_videos.php" <?php echo basename($_SERVER['PHP_SELF']) == 'lecture_videos.php' ? 'class="active"' : ''; ?>>
                        <i class="fas fa-video"></i> Lecture Videos
                    </a></li>
                <?php
                $currentPage = basename($_SERVER['PHP_SELF']);
                $toolPages = ['physical_tools.php', 'mental_tools.php', 'emotional_tools.php', 'budgeting.php', 'social_tools.php'];

                $isToolActive = in_array($currentPage, $toolPages);
                ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle <?php echo $isToolActive ? 'active' : ''; ?>">
                        <i class="fas fa-tools"></i> Tools
                        <i class="fas fa-chevron-down" style="font-size: 0.8em; margin-left: 5px;"></i>
                    </a>
                    <div class="dropdown-content">
                        <a href="physical_tools.php">
                            <i class="fas fa-heartbeat"></i>Physical Tools
                        </a>
                        <a href="mental_tools.php">
                            <i class="fas fa-brain"></i>Mental Tools
                        </a>
                        <a href="emotional_tools.php">
                            <i class="fas fa-smile"></i>Emotional Tools
                        </a>
                        <a href="budgeting.php">
                            <i class="fas fa-coins"></i>Financial Tools
                        </a>
                        <a href="social_tools.php">
                            <i class="fas fa-users"></i>Social Tools
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
    <div class="header-right">
        <!-- Announcement Dropdown -->
        <div class="announcement-dropdown dropdown">
            <a href="#" class="announcement-icon" aria-label="View announcements">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </a>
            <div class="dropdown-content announcement-content">
                <div class="announcement-header">
                    <h3>Announcements</h3>
                    <span class="announcement-date">Today</span>
                </div>
                <a href="#" class="announcement-item">
                    <i class="fas fa-info-circle"></i>
                    <div class="announcement-text">
                        <p>New health assessment feature available!</p>
                        <small>2 hours ago</small>
                    </div>
                </a>
                <a href="#" class="announcement-item">
                    <i class="fas fa-star"></i>
                    <div class="announcement-text">
                        <p>Weekly wellness tips updated</p>
                        <small>5 hours ago</small>
                    </div>
                </a>
                <a href="#" class="announcement-item">
                    <i class="fas fa-calendar"></i>
                    <div class="announcement-text">
                        <p>Upcoming maintenance scheduled</p>
                        <small>1 day ago</small>
                    </div>
                </a>
                <div class="announcement-footer">
                    <a href="#" class="view-all">View All Announcements</a>
                </div>
            </div>
        </div>
        <div class="user-info">
            <div class="user-profile">
                <i class="fas fa-user-circle"></i>
                <span class="user-name">
                    <?php echo $_SESSION['user_name'] ?? 'User'; ?>
                </span>
            </div>
            <a href="../logout.php" class="logout-btn" aria-label="Logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</header>

<script>
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navMenu = document.querySelector('.nav-menu');
    let isMenuOpen = false;

    mobileMenuBtn.addEventListener('click', function () {
        isMenuOpen = !isMenuOpen;
        navMenu.classList.toggle('active');

        this.style.transform = isMenuOpen ? 'rotate(90deg)' : 'rotate(0)';
    });

    document.addEventListener('click', function (event) {
        if (!navMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
            navMenu.classList.remove('active');
            mobileMenuBtn.style.transform = 'rotate(0)';
            isMenuOpen = false;
        }
    });

    const currentPage = window.location.pathname.split('/').pop();
    document.querySelectorAll('.nav-links a').forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        }
    });

    // Handle mobile dropdown
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const dropdownContent = document.querySelector('.dropdown-content');

    if (window.innerWidth <= 768) {
        dropdownToggle.addEventListener('click', function (e) {
            e.preventDefault();
            dropdownContent.style.display =
                dropdownContent.style.display === 'block' ? 'none' : 'block';
        });
    }
</script>