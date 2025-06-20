<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ewell</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="login-container">
        <div class="login-content">
            <div class="login-header">
                <a href="../index.php" class="logo">E<span>well</span></a>
                <h1>Welcome Back</h1>
                <p>Sign in to continue to your wellness journey</p>
            </div>
            <?php
            session_start();
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
                unset($_SESSION['error']);
            }
            ?>
            <form class="login-form" method="POST" action="../back_end/login_handler.php">
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                </div>
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
                </div>
                <button type="submit" class="login-btn">Sign In</button>
            </form>
            <div class="signup-link">
                Don't have an account? <a href="signup.php">Create Account</a>
            </div>
        </div>
        <div class="login-image">
            <div class="image-overlay">
                <h2>Your Wellness Journey Starts Here</h2>
                <p>Join our community and take the first step towards a healthier lifestyle</p>
            </div>
        </div>
    </div>
    <script src="../js/login.js"></script>
</body>

</html>