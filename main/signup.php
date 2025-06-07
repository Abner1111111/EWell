<?php
require_once '../config/database.php';

// Start session
session_start();

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $firstName = sanitize_input($_POST['firstName']);
    $lastName = sanitize_input($_POST['lastName']);
    $birthDate = sanitize_input($_POST['birth_date']);
    $gender = sanitize_input($_POST['gender']);
    $email = sanitize_input($_POST['email']);
    $phoneNo = sanitize_input($_POST['phone_no']);
    $height = sanitize_input($_POST['height']);
    $weight = sanitize_input($_POST['weight']);
    $activityLevel = sanitize_input($_POST['activity_level']);
    $healthGoals = sanitize_input($_POST['health_goals']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Handle dietary preferences and notifications
    $dietaryPreferences = isset($_POST['dietary']) ? implode(',', $_POST['dietary']) : '';
    $notifications = isset($_POST['notifications']) ? implode(',', $_POST['notifications']) : '';

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
    }
    // Validate phone number
    if (!preg_match('/^\d{11}$/', $phoneNo)) {
        $_SESSION['error'] = "Phone number must be exactly 11 digits.";
    }
    // Check if passwords match
    else if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match";
    }
    // Check if terms checkbox is checked
    else if (!isset($_POST['terms'])) {
        $_SESSION['error'] = "You must agree to the Terms of Service and Privacy Policy";
    }
    else {
        // Check if email already exists
        $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $result = $checkEmail->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error'] = "Email already exists";
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare and execute the insert statement
            // Note: Changed 'birth_date' to 'birhdate' to match your table structure
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, birthdate, gender, email, phone_no, height, weight, activity_level, health_goals, password, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'User', NOW())");
            $stmt->bind_param("ssssssddsss", $firstName, $lastName, $birthDate, $gender, $email, $phoneNo, $height, $weight, $activityLevel, $healthGoals, $hashedPassword);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Account created successfully! Please login.";
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['error'] = "Registration failed. Please try again. Error: " . $stmt->error;
            }

            $stmt->close();
        }
        $checkEmail->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Ewell</title>
    <link rel="stylesheet" href="../css/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            text-align: center;
            border: 1px solid #ef9a9a;
        }
        .success-message {
            background-color: #e8f5e8;
            color: #2e7d32;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            text-align: center;
            border: 1px solid #81c784;
        }
        .input-group input.error,
        .input-group select.error {
            border-color: #f44336;
            background-color: #ffebee;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-content">
            <div class="signup-header">
                <a href="index.php" class="logo">E<span>well</span></a>
                <h1>Create Your Account</h1>
                <p>Join our wellness community and start your journey</p>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error-message">
                        <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="success-message">
                        <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Progress Tracker -->
            <div class="progress-tracker">
                <div class="progress-step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">Personal Info</div>
                </div>
                <div class="progress-step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Account Setup</div>
                </div>
                <div class="progress-step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label">Health Profile</div>
                </div>
                <div class="progress-step" data-step="4">
                    <div class="step-number">4</div>
                    <div class="step-label">Preferences</div>
                </div>
            </div>

            <!-- Multi-step Form -->
            <form class="signup-form" id="signupForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <!-- Step 1: Personal Information -->
                <div class="form-step active" data-step="1">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" id="firstName" name="firstName" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" id="lastName" name="lastName" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <div class="input-group">
                            <i class="fas fa-calendar"></i>
                            <input type="date" id="dob" name="birth_date" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <div class="input-group">
                            <i class="fas fa-venus-mars"></i>
                            <select id="gender" name="gender" required>
                                <option value="">Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                                <option value="prefer-not">Prefer not to say</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Account Setup -->
                <div class="form-step" data-step="2">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <div class="input-group">
                        <i class="fas fa-phone"></i>
                            <input 
                                type="tel" id="phone" name="phone_no" required pattern="^\d{11}$" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="e.g., 09123456789">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" required>
                            <i class="fas fa-eye toggle-password"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="confirmPassword" name="confirmPassword" required>
                            <i class="fas fa-eye toggle-password"></i>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Health Profile -->
                <div class="form-step" data-step="3">
                    <div class="form-group">
                        <label for="height">Height (cm)</label>
                        <div class="input-group">
                            <i class="fas fa-ruler-vertical"></i>
                            <input type="number" id="height" name="height" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="weight">Weight (kg)</label>
                        <div class="input-group">
                            <i class="fas fa-weight"></i>
                            <input type="number" id="weight" name="weight" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="activityLevel">Activity Level</label>
                        <div class="input-group">
                            <i class="fas fa-running"></i>
                            <select id="activityLevel" name="activity_level" required>
                                <option value="">Select activity level</option>
                                <option value="Sedentary">Sedentary</option>
                                <option value="Lightly Active">Lightly Active</option>
                                <option value="Moderately Active">Moderately Active</option>
                                <option value="Very Active">Very Active</option>
                                <option value="Extra Active">Extra Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="healthGoals">Health Goals</label>
                        <div class="input-group">
                            <i class="fas fa-bullseye"></i>
                            <select id="healthGoals" name="health_goals" required>
                                <option value="">Select primary goal</option>
                                <option value="Weight Loss">Weight Loss</option>
                                <option value="Muscle Gain">Muscle Gain</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="General Fitness">General Fitness</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Preferences -->
                <div class="form-step" data-step="4">
                    <div class="form-group">
                        <label>Dietary Preferences</label>
                        <div class="checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="dietary[]" value="vegetarian">
                                Vegetarian
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="dietary[]" value="vegan">
                                Vegan
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="dietary[]" value="gluten-free">
                                Gluten-Free
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="dietary[]" value="dairy-free">
                                Dairy-Free
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Notifications</label>
                        <div class="checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="notifications[]" value="email" checked>
                                Email Updates
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="notifications[]" value="sms" checked>
                                SMS Updates
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label terms">
                            <input type="checkbox" name="terms" required>
                            I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="form-navigation">
                    <button type="button" class="btn prev-btn" style="display: none;">
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    <button type="button" class="btn next-btn">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="submit" class="btn submit-btn" style="display: none;">
                        Create Account
                    </button>
                </div>
            </form>

            <div class="login-link">
                Already have an account? <a href="login.php">Sign In</a>
            </div>
        </div>
    </div>
    <script src="../js/signup.js"></script>
</body>
</html>