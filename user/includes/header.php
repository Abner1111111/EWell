<!-- Header Navigation -->
<link rel="stylesheet" href="../../css/variables.css">
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
                <li><a href="relaxation.php" <?php echo basename($_SERVER['PHP_SELF']) == 'relaxation.php' ? 'class="active"' : ''; ?>>
                    <i class="fas fa-spa"></i> Relaxation
                </a></li>
             
                <li><a href="journal.php" <?php echo basename($_SERVER['PHP_SELF']) == 'journal.php' ? 'class="active"' : ''; ?>>
                    <i class="fas fa-book"></i> Journal
                </a></li>
                <li><a href="nutrition.php" <?php echo basename($_SERVER['PHP_SELF']) == 'nutrition.php' ? 'class="active"' : ''; ?>>
                    <i class="fas fa-utensils"></i> Nutrition
                </a></li>
                <li><a href="health_quiz.php" <?php echo basename($_SERVER['PHP_SELF']) == 'health_quiz.php' ? 'class="active"' : ''; ?>>
                    <i class="fas fa-question-circle"></i> Health Quiz
                </a></li>
                <li><a href="tools.php" <?php echo basename($_SERVER['PHP_SELF']) == 'tools.php' ? 'class="active"' : ''; ?>>
                    <i class="fas fa-tools"></i> Tools
                </a></li>
               
            </ul>
        </nav>
    </div>
    <div class="header-right">
        <div class="user-info">
            <div class="user-profile">
                <i class="fas fa-user-circle"></i>
                <span class="user-name"><?php echo $_SESSION['user_name'] ?? 'User'; ?></span>
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

    mobileMenuBtn.addEventListener('click', function() {
        isMenuOpen = !isMenuOpen;
        navMenu.classList.toggle('active');
    
        this.style.transform = isMenuOpen ? 'rotate(90deg)' : 'rotate(0)';
    });

    document.addEventListener('click', function(event) {
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
</script>