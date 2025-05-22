<?php
  // Include required files
  include '../db_connection/database.php';

  // Use the $connection variable from database.php
  $conn = $connection;

  // Check if database connection exists
  if (!isset($conn) || $conn === null) {
      die("Database connection failed. Please check your database configuration.");
  }

  // Initialize variables
  $first_name = $last_name = $username = $role = "";
  $errors = array();
  $success_message = "";

  // Check if the form is submitted
  if (isset($_POST['register'])) {
      // Sanitize and validate form inputs
      $first_name = mysqli_real_escape_string($conn, $_POST['first_name'] ?? '');
      $last_name = mysqli_real_escape_string($conn, $_POST['last_name'] ?? '');
      $username = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
      $password = mysqli_real_escape_string($conn, $_POST['password'] ?? '');
      $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword'] ?? '');

      // Validate required fields
      if (empty($first_name)) {
          $errors[] = "First name is required";
      }
      if (empty($last_name)) {
          $errors[] = "Last name is required";
      }
      if (empty($username)) {
          $errors[] = "Username is required";
      }
      if (empty($password)) {
          $errors[] = "Password is required";
      }

      // Check if username already exists (only if username is provided)
      if (!empty($username)) {
          $stmt = mysqli_prepare($conn, "SELECT username FROM users WHERE username = ?");
          if ($stmt) {
              mysqli_stmt_bind_param($stmt, "s", $username);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
              
              if (mysqli_num_rows($result) > 0) {   
                  $errors[] = "Username already exists";
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
          // Hash password using password_hash
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
          
          $stmt = mysqli_prepare($conn, "INSERT INTO users (first_name, last_name, username, password) VALUES (?, ?, ?, ?)");
          if ($stmt) {
              mysqli_stmt_bind_param($stmt, "ssss", $first_name, $last_name, $username, $hashedPassword);
              $result = mysqli_stmt_execute($stmt);
              
              if ($result) {
                  $success_message = "Registration completed successfully!";
                  // Reset form fields after successful registration
                  $first_name = $last_name = $username = $role = "";
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
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
        }
        .success-message {
            color: #198754;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Manage Users</h2>
        
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
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                    <div class="invalid-feedback">Please provide a first name.</div>
                </div>
                <div class="col-md-6">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                    <div class="invalid-feedback">Please provide a last name.</div>
                </div>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                <div class="invalid-feedback">Please choose a username.</div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required minlength="8">
                <div class="form-text">Password must be at least 8 characters long</div>
                <div class="invalid-feedback">Password must be at least 8 characters long.</div>
            </div>

            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                <div class="invalid-feedback">Please confirm your password.</div>
            </div>

            <button type="submit" name="register" class="btn btn-primary">Register User</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    var password = document.getElementById('password').value
                    var confirmPassword = document.getElementById('confirmPassword').value
                    
                    // Check if passwords match
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