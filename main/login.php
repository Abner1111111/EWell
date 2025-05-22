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
                <a href="index.html" class="logo">E<span>well</span></a>
                <h1>Welcome Back</h1>
                <p>Sign in to continue to your wellness journey</p>
            </div>
            <form class="login-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Enter your email" autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Enter your password"
                            autocomplete="off">
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                </div>
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
                <button type="button" class="login-btn" onclick="window.location.href='../user/dashboard.php'">Sign
                    In</button>

                <!-- <div class="divider">
                    <span>or continue with</span>
                </div>
                <div class="social-login">
                    <button type="button" class="social-btn google">
                        <i class="fab fa-google"></i>
                        Google
                    </button>
                    <button type="button" class="social-btn facebook">
                        <i class="fab fa-facebook-f"></i>
                        Facebook
                    </button>
                </div> -->
            </form>
            <div class="signup-link">
                Don't have an account? <a href="signup.html">Create Account</a>
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