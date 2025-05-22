<?php
  // Include required files
  include '../db_connection/database.php';

  // Use the $connection variable from database.php
  $conn = $connection;

  // Initialize variables
  $first_name = $last_name = $username = $role = "";
  $errors = array();
  $success_message = "";
  $user_id = "";

  // Check if user is logged in and get their information
  session_start();
  if (isset($_SESSION['user_id'])) {
      $user_id = $_SESSION['user_id'];
      $stmt = mysqli_prepare($conn, "SELECT first_name, last_name, username FROM users WHERE id = ?");
      mysqli_stmt_bind_param($stmt, "i", $user_id);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      
      if ($row = mysqli_fetch_assoc($result)) {
          $first_name = $row['first_name'];
          $last_name = $row['last_name'];
          $username = $row['username'];
      }
  }

  // Check if the form is submitted for registration
  if (isset($_POST['register'])) {
      // Sanitize and validate form inputs
      $first_name = mysqli_real_escape_string($conn, $_POST['first_name'] ?? '');
      $last_name = mysqli_real_escape_string($conn, $_POST['last_name'] ?? '');
      $username = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
      $password = mysqli_real_escape_string($conn, $_POST['password'] ?? '');
      $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword'] ?? '');

      // Check if username already exists
      $stmt = mysqli_prepare($conn, "SELECT username FROM users WHERE username = ? AND id != ?");
      mysqli_stmt_bind_param($stmt, "si", $username, $user_id);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      
      if (mysqli_num_rows($result) > 0) {
          $errors[] = "Username already exists";
      }
      
      // Password validation
      if (strlen($password) < 8) {
          $errors[] = "Password must be at least 8 characters";
      }
      
      if ($password !== $confirmPassword) {
          $errors[] = "Passwords do not match";
      }
      
      // If no errors, proceed with registration
      if (empty($errors)) {
          // Hash password using password_hash
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
          
          $stmt = mysqli_prepare($conn, "INSERT INTO users (first_name, last_name, username, password) VALUES (?, ?, ?, ?)");
          mysqli_stmt_bind_param($stmt, "ssss", $first_name, $last_name, $username, $hashedPassword);
          $result = mysqli_stmt_execute($stmt);
          
          if ($result) {
              $success_message = "Registration completed successfully!";
              // Reset form fields after successful registration
              $first_name = $last_name = $username = $role = "";
          } else {
              $errors[] = "Registration failed: " . mysqli_error($conn);
          }
      }
  }

  // Check if the form is submitted for update
  if (isset($_POST['update'])) {
      // Sanitize and validate form inputs
      $first_name = mysqli_real_escape_string($conn, $_POST['first_name'] ?? '');
      $last_name = mysqli_real_escape_string($conn, $_POST['last_name'] ?? '');
      $username = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
      $password = mysqli_real_escape_string($conn, $_POST['password'] ?? '');
      $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword'] ?? '');

      // Check if username already exists (excluding current user)
      $stmt = mysqli_prepare($conn, "SELECT username FROM users WHERE username = ? AND id != ?");
      mysqli_stmt_bind_param($stmt, "si", $username, $user_id);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      
      if (mysqli_num_rows($result) > 0) {
          $errors[] = "Username already exists";
      }

      // If password is provided, validate it
      if (!empty($password)) {
          if (strlen($password) < 8) {
              $errors[] = "Password must be at least 8 characters";
          }
          
          if ($password !== $confirmPassword) {
              $errors[] = "Passwords do not match";
          }
      }
      
      // If no errors, proceed with update
      if (empty($errors)) {
          if (!empty($password)) {
              // Update with new password
              $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
              $stmt = mysqli_prepare($conn, "UPDATE users SET first_name = ?, last_name = ?, username = ?, password = ? WHERE id = ?");
              mysqli_stmt_bind_param($stmt, "ssssi", $first_name, $last_name, $username, $hashedPassword, $user_id);
          } else {
              // Update without changing password
              $stmt = mysqli_prepare($conn, "UPDATE users SET first_name = ?, last_name = ?, username = ? WHERE id = ?");
              mysqli_stmt_bind_param($stmt, "sssi", $first_name, $last_name, $username, $user_id);
          }
          
          $result = mysqli_stmt_execute($stmt);
          
          if ($result) {
              $success_message = "Profile updated successfully!";
          } else {
              $errors[] = "Update failed: " . mysqli_error($conn);
          }
      }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 600px;
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
        <h2 class="mb-4"><?php echo isset($_SESSION['user_id']) ? 'Update Account' : 'Account Registration'; ?></h2>
        
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <p><?php echo htmlspecialchars($success_message); ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" <?php echo !isset($_SESSION['user_id']) ? 'required' : ''; ?>>
                <div class="form-text">Password must be at least 8 characters long. <?php echo isset($_SESSION['user_id']) ? 'Leave blank to keep current password.' : ''; ?></div>
            </div>

            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" <?php echo !isset($_SESSION['user_id']) ? 'required' : ''; ?>>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <button type="submit" name="update" class="btn btn-primary">Update Profile</button>
            <?php else: ?>
                <button type="submit" name="register" class="btn btn-primary">Register</button>
            <?php endif; ?>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


