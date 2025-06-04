<?php
session_start();
include '../db_connection/database.php';
include '../back_end/session.php';

// Initialize variables
$title = $content = $status = "";
$errors = array();
$success_message = "";

// Handle announcement update
if (isset($_POST['update_announcement'])) {
    $announcement_id = mysqli_real_escape_string($conn, $_POST['announcement_id'] ?? '');
    $title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
    $content = mysqli_real_escape_string($conn, $_POST['content'] ?? '');
    $status = mysqli_real_escape_string($conn, $_POST['status'] ?? 'draft');

    // Validate required fields
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    if (empty($content)) {
        $errors[] = "Content is required";
    }

    // If no errors, proceed with announcement update
    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "UPDATE announcements SET title = ?, content = ?, status = ? WHERE announ_id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssi", $title, $content, $status, $announcement_id);
            $result = mysqli_stmt_execute($stmt);
            
            if ($result) {
                $success_message = "Announcement updated successfully!";
            } else {
                $errors[] = "Update failed: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }
}

// Check if the form is submitted for creation
if (isset($_POST['create_announcement'])) {
    // Sanitize and validate form inputs
    $title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
    $content = mysqli_real_escape_string($conn, $_POST['content'] ?? '');
    $status = mysqli_real_escape_string($conn, $_POST['status'] ?? 'draft');

    // Validate required fields
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    if (empty($content)) {
        $errors[] = "Content is required";
    }

    // If no errors, proceed with announcement creation
    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO announcements (title, content, status, created_by, created_at) VALUES (?, ?, ?, ?, NOW())");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssi", $title, $content, $status, $_SESSION['user_id']);
            $result = mysqli_stmt_execute($stmt);
            
            if ($result) {
                $success_message = "Announcement created successfully!";
                // Reset form fields
                $title = $content = "";
                $status = "draft";
            } else {
                $errors[] = "Creation failed: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $announ_id = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM announcements WHERE announ_id = ?");
    $stmt->bind_param("i", $announ_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Announcement deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting the announcement.']);
    }

    $stmt->close();
    exit;
}

// Fetch existing announcements
$announcements_query = "SELECT a.*, u.first_name, u.last_name 
                       FROM announcements a 
                       LEFT JOIN users u ON a.created_by = u.id 
                       ORDER BY a.created_at DESC";
$announcements_result = mysqli_query($conn, $announcements_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements - EWell Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_sidebar.css">
    <link rel="stylesheet" href="../css/admin_create_quiz.css">
    <style>
        .announcement-form {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .announcement-list {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        .status-draft {
            background-color: #ffc107;
            color: #000;
        }
        .status-published {
            background-color: #28a745;
            color: #fff;
        }
        .status-archived {
            background-color: #6c757d;
            color: #fff;
        }
    </style>
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
                    <!-- Announcement Creation Form -->
                    <div class="col-md-6">
                        <div class="card announcement-form">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-bullhorn me-2"></i>Create New Announcement
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
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" 
                                               value="<?php echo htmlspecialchars($title); ?>" required>
                                        <div class="invalid-feedback">Please provide a title.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="content" class="form-label">Content</label>
                                        <textarea class="form-control" id="content" name="content" rows="5" 
                                                  required><?php echo htmlspecialchars($content); ?></textarea>
                                        <div class="invalid-feedback">Please provide content.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                            <option value="published" <?php echo $status === 'published' ? 'selected' : ''; ?>>Published</option>
                                            <option value="archived" <?php echo $status === 'archived' ? 'selected' : ''; ?>>Archived</option>
                                        </select>
                                    </div>

                                    <button type="submit" name="create_announcement" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create Announcement
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Announcement List -->
                    <div class="col-md-6">
                        <div class="card announcement-list">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-list me-2"></i>Existing Announcements
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Status</th>
                                                <th>Created By</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($announcement = mysqli_fetch_assoc($announcements_result)): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($announcement['title']); ?></td>
                                                <td>
                                                    <span class="status-badge status-<?php echo $announcement['status']; ?>">
                                                        <?php echo ucfirst($announcement['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($announcement['first_name'] . ' ' . $announcement['last_name']); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($announcement['created_at'])); ?></td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <button class="btn btn-sm btn-outline-primary btn-edit me-1" title="Edit" data-id="<?php echo $announcement['announ_id']; ?>" data-content="<?php echo htmlspecialchars($announcement['content']); ?>" data-status="<?php echo $announcement['status']; ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger btn-delete" title="Delete" data-id="<?php echo $announcement['announ_id']; ?>">
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

    <!-- Edit Announcement Modal -->
    <div class="modal fade" id="editAnnouncementModal" tabindex="-1" aria-labelledby="editAnnouncementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAnnouncementModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Announcement
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="" class="needs-validation" novalidate>
                    <div class="modal-body">
                        <input type="hidden" id="edit_announcement_id" name="announcement_id">
                        
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                            <div class="invalid-feedback">Please provide a title.</div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_content" class="form-label">Content</label>
                            <textarea class="form-control" id="edit_content" name="content" rows="5" required></textarea>
                            <div class="invalid-feedback">Please provide content.</div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_announcement" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        // Edit modal functionality
        const editModal = new bootstrap.Modal(document.getElementById('editAnnouncementModal'));
        
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const announcementId = this.getAttribute('data-id');
                const title = row.querySelector('td:first-child').textContent;
                const content = this.getAttribute('data-content');
                const status = this.getAttribute('data-status');

                // Set values in the modal
                document.getElementById('edit_announcement_id').value = announcementId;
                document.getElementById('edit_title').value = title;
                document.getElementById('edit_content').value = content;
                document.getElementById('edit_status').value = status;

                // Show the modal
                editModal.show();
            });
        });

        // Delete functionality
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const row = this.closest('tr');
                const title = row.querySelector('td:first-child').textContent;
                
                if (confirm(`Are you sure you want to delete the announcement: ${title}?`)) {
                    fetch('Announcement.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${id}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            row.remove(); // Remove the row from the table
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the announcement.');
                    });
                }
            });
        });

    </script>
</body>
</html> 