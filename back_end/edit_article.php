<?php
session_start();
include '../db_connection/database.php';
include 'session.php';

// Get article data
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM news_articles WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$article = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Redirect if article not found
if (!$article) {
    $_SESSION['error_message'] = "Article not found.";
    header("Location: ../admin/News.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article - EWell Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_sidebar.css">
    <link rel="stylesheet" href="../css/admin_create_quiz.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../admin/include/sidebar.php'; ?>

        <main class="main-content">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <a class="navbar-brand" href="../admin/index.php">
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

            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Edit Article</h5>
                                <a href="../admin/News.php" class="btn-close" aria-label="Close"></a>
                            </div>
                            <div class="card-body">
                                <form action="./process_article.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="<?php echo $article['id']; ?>">

                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Category</label>
                                            <select class="form-select" name="category" required>
                                                <option value="World News" <?php echo $article['category'] === 'World News' ? 'selected' : ''; ?>>World News</option>
                                                <option value="Local News" <?php echo $article['category'] === 'Local News' ? 'selected' : ''; ?>>Local News</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Author</label>
                                            <input type="text" class="form-control" name="author" value="<?php echo htmlspecialchars($article['author']); ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">News Link</label>
                                        <input type="url" class="form-control" name="news_link" value="<?php echo htmlspecialchars($article['news_link']); ?>" placeholder="https://example.com/news">
                                        <small class="text-muted">Optional: Add the original source link of the news</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Content</label>
                                        <textarea class="form-control" name="content" rows="10" required><?php echo htmlspecialchars($article['content']); ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Summary</label>
                                        <textarea class="form-control" name="summary" rows="3" required><?php echo htmlspecialchars($article['summary']); ?></textarea>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Current Image</label>
                                            <?php if($article['image_url']): ?>
                                                <div class="mb-2">
                                                    <img src="../<?php echo htmlspecialchars($article['image_url']); ?>" class="img-fluid" style="max-height: 200px;" alt="Current Image">
                                                </div>
                                            <?php else: ?>
                                                <p class="text-muted">No image uploaded</p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Upload New Image</label>
                                            <input type="file" class="form-control" name="image">
                                            <small class="text-muted">Leave empty to keep current image</small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Publication Date</label>
                                            <input type="date" class="form-control" name="publication_date" value="<?php echo $article['publication_date']; ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Status</label>
                                            <select class="form-select" name="status" required>
                                                <option value="Draft" <?php echo $article['status'] === 'Draft' ? 'selected' : ''; ?>>Draft</option>
                                                <option value="Active" <?php echo $article['status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
                                                <option value="Inactive" <?php echo $article['status'] === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                                                <option value="Archived" <?php echo $article['status'] === 'Archived' ? 'selected' : ''; ?>>Archived</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin_sidebar.js"></script>
</body>
</html> 