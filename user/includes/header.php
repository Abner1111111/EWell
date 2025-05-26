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
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-tools"></i> Tools
                        <i class="fas fa-chevron-down" style="font-size: 0.8em; margin-left: 5px;"></i>
                    </a>
                    <div class="dropdown-content">
                        <a href="physical_tools.php">
                            <i class="fas fa-dumbbell"></i>Physical Tools
                        </a>
                        <a href="mental_tools.php">
                            <i class="fas fa-brain"></i>Mental Tools
                        </a>
                        <a href="tools.php?tool=planner">
                            <i class="fas fa-smile"></i>Emotional Tools
                        </a>
                        <a href="tools.php?tool=reminder">
                            <i class="fas fa-coins"></i>Financial Tools
                        </a>
                        <a href="tools.php?tool=reminder">
                            <i class="fas fa-users"></i>Social Tools
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
    <div class="header-right">
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