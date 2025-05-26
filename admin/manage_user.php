<?php
session_start();
include '../db_connection/database.php';

// Initialize variables
$first_name = $last_name = $email = $role = "";
$errors = array();
$success_message = "";

// Check if the form is submitted
if (isset($_POST['register'])) {
    // Sanitize and validate form inputs
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name'] ?? '');
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['Email'] ?? '');
    $password = mysqli_real_escape_string($conn, $_POST['password'] ?? '');
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword'] ?? '');

    // Validate required fields
    if (empty($first_name)) {
        $errors[] = "First name is required";
    }
    if (empty($last_name)) {
        $errors[] = "Last name is required";
    }
    if (empty($email)) {
        $errors[] = "Email is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    // Check if username already exists
    if (!empty($email)) {
        $stmt = mysqli_prepare($conn, "SELECT Email FROM users WHERE Email = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {   
                $errors[] = "Email already exists";
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }
    
    // Password validation
    if (!empty($password)) {
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters";
        }
        
        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match";
        }
    }
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = mysqli_prepare($conn, "INSERT INTO users (first_name, last_name, Email, password) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssss", $first_name, $last_name, $email, $hashedPassword);
            $result = mysqli_stmt_execute($stmt);
            
            if ($result) {
                $success_message = "Registration completed successfully!";
                // Reset form fields
                $first_name = $last_name = $email = $role = "";
            } else {
                $errors[] = "Registration failed: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - EWell Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_sidebar.css">
    <link rel="stylesheet" href="../css/admin_create_quiz.css">
    <link rel="stylesheet" href="../css/admin_manage_user.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include 'include/sidebar.php'; ?>

        <main class="main-content">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <a class="navbar-brand" href="index.php">
                            <i class="fas fa-heartbeat me-2"></i>EWell Admin
                        </a>
                        <button class="btn btn-link d-md-none" id="sidebarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="../index.php">
                                    <i class="fas fa-external-link-alt me-1"></i>View Site
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../logout.php">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid">
                <div class="row">
                    <!-- User Registration Form -->
                    <div class="col-md-6">
                        <div class="card user-form">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user-plus me-2"></i>Register New User
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($errors)): ?>
                                    <div class="alert alert-danger">
                                        <?php foreach ($errors as $error): ?>
                                            <p class="mb-1"><?php echo htmlspecialchars($error); ?></p>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($success_message)): ?>
                                    <div class="alert alert-success">
                                        <p class="mb-0"><?php echo htmlspecialchars($success_message); ?></p>
                                    </div>
                                <?php endif; ?>

                                <form method="POST" action="" class="needs-validation" novalidate>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="first_name" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" 
                                                   value="<?php echo htmlspecialchars($first_name); ?>" required>
                                            <div class="invalid-feedback">Please provide a first name.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" 
                                                   value="<?php echo htmlspecialchars($last_name); ?>" required>
                                            <div class="invalid-feedback">Please provide a last name.</div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="Email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="Email" name="Email" 
                                               value="<?php echo htmlspecialchars($email); ?>" required>
                                        <div class="invalid-feedback">Please choose a Email.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" 
                                               required minlength="8">
                                        <div class="form-text">Password must be at least 8 characters long</div>
                                        <div class="invalid-feedback">Password must be at least 8 characters long.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirmPassword" 
                                               name="confirmPassword" required>
                                        <div class="invalid-feedback">Please confirm your password.</div>
                                    </div>

                                    <button type="submit" name="register" class="btn btn-primary">
                                        <i class="fas fa-user-plus me-2"></i>Register User
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- User List -->
                    <div class="col-md-6">
                        <div class="card user-list">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-users me-2"></i>Existing Users
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT id, first_name, last_name, Email FROM users ORDER BY first_name";
                                            $result = mysqli_query($conn, $query);
                                            while ($user = mysqli_fetch_assoc($result)):
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($user['Email']); ?></td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <button class="btn btn-sm btn-outline-primary btn-edit me-1">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger btn-delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin_sidebar.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    var password = document.getElementById('password').value
                    var confirmPassword = document.getElementById('confirmPassword').value
                    
                    if (password !== confirmPassword) {
                        document.getElementById('confirmPassword').setCustomValidity('Passwords do not match')
                    } else {
                        document.getElementById('confirmPassword').setCustomValidity('')
                    }
                    
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
        
        // Real-time password confirmation check
        document.getElementById('confirmPassword').addEventListener('input', function() {
            var password = document.getElementById('password').value
            var confirmPassword = this.value
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Passwords do not match')
            } else {
                this.setCustomValidity('')
            }
        })
    </script>
</body>
</html>