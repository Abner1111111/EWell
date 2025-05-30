<?php
session_start();
include '../db_connection/database.php';
include '../back_end/session.php';

// Get news statistics
$totalQuery = "SELECT COUNT(*) as total FROM news_articles";
$activeQuery = "SELECT COUNT(*) as active FROM news_articles WHERE status = 'Active'";
$inactiveQuery = "SELECT COUNT(*) as inactive FROM news_articles WHERE status = 'Inactive'";
$draftQuery = "SELECT COUNT(*) as drafts FROM news_articles WHERE status = 'Draft'";
$archivedQuery = "SELECT COUNT(*) as archived FROM news_articles WHERE status = 'Archived'";

$totalResult = mysqli_query($conn, $totalQuery);
$activeResult = mysqli_query($conn, $activeQuery);
$inactiveResult = mysqli_query($conn, $inactiveQuery);
$draftResult = mysqli_query($conn, $draftQuery);
$archivedResult = mysqli_query($conn, $archivedQuery);

$totalArticles = mysqli_fetch_assoc($totalResult)['total'];
$activeArticles = mysqli_fetch_assoc($activeResult)['active'];
$inactiveArticles = mysqli_fetch_assoc($inactiveResult)['inactive'];
$draftArticles = mysqli_fetch_assoc($draftResult)['drafts'];
$archivedArticles = mysqli_fetch_assoc($archivedResult)['archived'];

// Get all articles with custom sorting (archived last)
$query = "SELECT * FROM news_articles 
          ORDER BY 
            CASE 
                WHEN status = 'Archived' THEN 1 
                ELSE 0 
            END ASC,
            publication_date DESC";
$articles = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage News - EWell Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_sidebar.css">
    <link rel="stylesheet" href="../css/admin_create_quiz.css">
    <link rel="stylesheet" href="../css/admin_news.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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

            <div class="container-fluid mt-4">
                <!-- Loading Overlay -->
                <div class="loading-overlay">
                    <div class="loading-spinner"></div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3">News Management</h1>
                    <button class="btn btn-add-article text-white" data-bs-toggle="modal" data-bs-target="#newArticleModal">
                        <i class="fas fa-plus"></i> New Article
                    </button>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col">
                        <div class="stats-card text-center">
                            <h3><?php echo $totalArticles; ?></h3>
                            <p class="text-muted mb-0">Total Articles</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stats-card text-center">
                            <h3><?php echo $activeArticles; ?></h3>
                            <p class="text-muted mb-0">Active</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stats-card text-center">
                            <h3><?php echo $inactiveArticles; ?></h3>
                            <p class="text-muted mb-0">Inactive</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stats-card text-center">
                            <h3><?php echo $draftArticles; ?></h3>
                            <p class="text-muted mb-0">Drafts</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stats-card text-center">
                            <h3><?php echo $archivedArticles; ?></h3>
                            <p class="text-muted mb-0">Archived</p>
                        </div>
                    </div>
                </div>

                <!-- Articles Grid -->
                <div class="row">
                    <?php while($article = mysqli_fetch_assoc($articles)): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="article-card status-<?php echo strtolower($article['status']); ?>" data-article-id="<?php echo $article['id']; ?>" onclick="showArticleDetail(this)">
                            <span class="status-badge status-<?php echo strtolower($article['status']); ?>">
                                <i class="fas <?php
                                    switch($article['status']) {
                                        case 'Active':
                                            echo 'fa-check-circle';
                                            break;
                                        case 'Inactive':
                                            echo 'fa-times-circle';
                                            break;
                                        case 'Draft':
                                            echo 'fa-pencil-alt';
                                            break;
                                        case 'Archived':
                                            echo 'fa-archive';
                                            break;
                                    }
                                ?>"></i>
                                <?php echo $article['status']; ?>
                            </span>
                            <div class="image-container">
                                <?php if($article['image_url']): ?>
                                    <img src="../<?php echo htmlspecialchars($article['image_url']); ?>" alt="News Image">
                                <?php else: ?>
                                    <div class="text-center text-muted py-4 bg-light rounded">
                                        <i class="fas fa-image fa-3x"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h5 class="title"><?php echo htmlspecialchars($article['title']); ?></h5>
                            <div class="meta-info">
                                <span><i class="fas fa-folder me-2"></i><?php echo htmlspecialchars($article['category']); ?></span>
                                <span><i class="fas fa-calendar me-2"></i><?php echo date('M d, Y', strtotime($article['publication_date'])); ?></span>
                            </div>
                            <p class="summary"><?php echo htmlspecialchars(substr($article['summary'], 0, 100)) . '...'; ?></p>
                            <div class="d-flex justify-content-end">
                                <div class="btn-group">
                                    <?php if($article['status'] !== 'Draft'): ?>
                                        <?php if($article['status'] === 'Inactive' || $article['status'] === 'Archived'): ?>
                                            <button class="btn btn-success btn-sm modal-activate">Activate</button>
                                        <?php elseif($article['status'] === 'Active'): ?>
                                            <button class="btn btn-warning btn-sm modal-deactivate">Deactivate</button>
                                        <?php endif; ?>
                                        
                                        <?php if($article['status'] !== 'Archived'): ?>
                                            <button class="btn btn-secondary btn-sm modal-archive">Archive</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <button class="btn btn-primary btn-sm">Edit</button>
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success position-fixed top-0 end-0 m-3" style="z-index: 1050;">
            <?php 
            echo $_SESSION['success_message'];
            unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger position-fixed top-0 end-0 m-3" style="z-index: 1050;">
            <?php 
            echo $_SESSION['error_message'];
            unset($_SESSION['error_message']);
            ?>
        </div>
    <?php endif; ?>

    <!-- New Article Modal -->
    <div class="modal fade" id="newArticleModal" tabindex="-1" aria-labelledby="newArticleModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="newArticleForm" action="../back_end/process_article.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newArticleModalLabel">Create New Article</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category" required>
                                    <option value="World News">World News</option>
                                    <option value="Local News">Local News</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Author</label>
                                <input type="text" class="form-control" name="author">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">News Link</label>
                            <input type="url" class="form-control" name="news_link" placeholder="https://example.com/news">
                            <small class="text-muted">Optional: Add the original source link of the news</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea class="form-control" name="content" rows="5" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Summary</label>
                            <textarea class="form-control" name="summary" rows="2" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Featured Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Publication Date</label>
                                <input type="date" class="form-control" name="publication_date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="Draft">Draft</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button> -->
                        <button type="submit" class="btn btn-primary">Create Article</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Article Detail Modal -->
    <div class="modal fade article-detail-modal" id="articleDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body position-relative">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 1050;"></button>
                    <button type="button" class="expand-image-btn" onclick="toggleImageHeight(this)">
                        <i class="fas fa-expand"></i>
                    </button>
                    <img src="" alt="Article Image" class="article-image" id="modalArticleImage">
                    <div class="article-content">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="status-badge" id="modalArticleStatus"></span>
                            <div class="btn-group">
                                <button class="btn btn-success btn-sm modal-activate" style="display: none;">Activate</button>
                                <button class="btn btn-warning btn-sm modal-deactivate" style="display: none;">Deactivate</button>
                                <button class="btn btn-secondary btn-sm modal-archive" style="display: none;">Archive</button>
                                <button class="btn btn-primary btn-sm modal-edit">Edit</button>
                                <button class="btn btn-danger btn-sm modal-delete">Delete</button>
                            </div>
                        </div>
                        <h2 class="article-title" id="modalArticleTitle"></h2>
                        <div class="article-meta">
                            <span class="me-3"><i class="fas fa-folder me-1"></i><span id="modalArticleCategory"></span></span>
                            <span><i class="fas fa-calendar me-1"></i><span id="modalArticleDate"></span></span>
                        </div>
                        <p class="article-summary" id="modalArticleSummary"></p>
                        <div class="article-full-content" id="modalArticleContent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin_sidebar.js"></script>
    <script>
    // Article management functionality
    document.querySelectorAll('.article-card').forEach(card => {
        const articleId = card.dataset.articleId;
        if (!articleId) return;

        // Activate/Deactivate/Archive buttons
        const activateBtn = card.querySelector('.modal-activate');
        const deactivateBtn = card.querySelector('.modal-deactivate');
        const archiveBtn = card.querySelector('.modal-archive');
        
        if (activateBtn) {
            activateBtn.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent modal from opening
                handleAction('activate', articleId);
            });
        }

        if (deactivateBtn) {
            deactivateBtn.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent modal from opening
                handleAction('deactivate', articleId);
            });
        }

        if (archiveBtn) {
            archiveBtn.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent modal from opening
                handleAction('archive', articleId);
            });
        }

        // Edit button
        const editBtn = card.querySelector('.btn-primary');
        if (editBtn) {
            editBtn.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent modal from opening
                handleEdit(articleId);
            });
        }

        // Delete button
        const deleteBtn = card.querySelector('.btn-danger');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent modal from opening
                handleDelete(articleId);
            });
        }
    });

    // Handle success/error messages from PHP session
    <?php if (isset($_SESSION['success_message'])): ?>
        Swal.fire({
            title: 'Success!',
            text: '<?php echo $_SESSION['success_message']; ?>',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        Swal.fire({
            title: 'Error!',
            text: '<?php echo $_SESSION['error_message']; ?>',
            icon: 'error'
        });
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    // Prevent form submission on enter key
    document.getElementById('newArticleForm').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    // Prevent select dropdowns from submitting form
    document.querySelectorAll('#newArticleForm select').forEach(select => {
        select.addEventListener('change', function(event) {
            event.preventDefault();
            event.stopPropagation();
        });
    });

    // Initialize modal with options
    const modal = new bootstrap.Modal(document.getElementById('newArticleModal'), {
        backdrop: 'static',
        keyboard: false
    });

    // Function to show article detail modal
    function showArticleDetail(cardElement) {
        const articleId = cardElement.dataset.articleId;
        
        // Prevent triggering when clicking buttons
        if (event.target.closest('.btn-group')) {
            return;
        }

        // Show loading state
        Swal.fire({
            title: 'Loading...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Fetch article details
        fetch(`../back_end/get_article_detail.php?id=${articleId}`)
            .then(response => response.json())
            .then(article => {
                Swal.close();
                // Update modal content
                document.getElementById('modalArticleImage').src = article.image_url ? `../${article.image_url}` : '../images/placeholder.jpg';
                document.getElementById('modalArticleTitle').textContent = article.title;
                document.getElementById('modalArticleCategory').textContent = article.category;
                document.getElementById('modalArticleDate').textContent = new Date(article.publication_date).toLocaleDateString();
                document.getElementById('modalArticleSummary').textContent = article.summary;
                document.getElementById('modalArticleContent').innerHTML = article.content;
                
                // Update status badge
                const statusBadge = document.getElementById('modalArticleStatus');
                statusBadge.textContent = article.status;
                statusBadge.className = `status-badge status-${article.status.toLowerCase()}`;

                // Show/hide appropriate buttons based on status
                const modal = document.getElementById('articleDetailModal');
                const activateBtn = modal.querySelector('.modal-activate');
                const deactivateBtn = modal.querySelector('.modal-deactivate');
                const archiveBtn = modal.querySelector('.modal-archive');
                
                // Hide all status-related buttons first
                activateBtn.style.display = 'none';
                deactivateBtn.style.display = 'none';
                archiveBtn.style.display = 'none';

                // Show appropriate buttons based on status
                if (article.status !== 'Draft') {
                    if (article.status === 'Inactive' || article.status === 'Archived') {
                        activateBtn.style.display = 'inline-block';
                    } else if (article.status === 'Active') {
                        deactivateBtn.style.display = 'inline-block';
                    }

                    if (article.status !== 'Archived') {
                        archiveBtn.style.display = 'inline-block';
                    }
                }

                // Update button actions
                activateBtn.onclick = () => handleAction('activate', articleId);
                deactivateBtn.onclick = () => handleAction('deactivate', articleId);
                archiveBtn.onclick = () => handleAction('archive', articleId);
                modal.querySelector('.modal-edit').onclick = () => handleEdit(articleId);
                modal.querySelector('.modal-delete').onclick = () => handleDelete(articleId);

                // Show modal
                const modalInstance = new bootstrap.Modal(modal);
                modalInstance.show();
            })
            .catch(error => {
                console.error('Error fetching article details:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Error loading article details',
                    icon: 'error'
                });
            });
    }

    // Generic action handler for activate/deactivate/archive
    function handleAction(action, articleId) {
        const titles = {
            activate: 'Activate Article',
            deactivate: 'Deactivate Article',
            archive: 'Archive Article'
        };
        const texts = {
            activate: 'Are you sure you want to activate this article?',
            deactivate: 'Are you sure you want to deactivate this article?',
            archive: 'Are you sure you want to archive this article? This will make the article inactive.'
        };
        const icons = {
            activate: 'question',
            deactivate: 'warning',
            archive: 'warning'
        };
        const colors = {
            activate: '#28a745',
            deactivate: '#dc3545',
            archive: '#6c757d'
        };

        Swal.fire({
            title: titles[action],
            text: texts[action],
            icon: icons[action],
            showCancelButton: true,
            confirmButtonColor: colors[action],
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${action} it!`
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                fetch('../back_end/process_article.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=${action}&id=${articleId}`
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: `Article ${action}d successfully!`,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.error || `Error ${action}ing article`,
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: `Error ${action}ing article`,
                        icon: 'error'
                    });
                });
            }
        });
    }

    // Handle Edit action
    function handleEdit(articleId) {
        window.location.href = `../back_end/edit_article.php?id=${articleId}`;
    }

    // Handle Delete action
    function handleDelete(articleId) {
        Swal.fire({
            title: 'Delete Article',
            text: 'Are you sure you want to delete this article? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                fetch('../back_end/process_article.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=delete&id=${articleId}`
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The article has been deleted.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            const card = document.querySelector(`.article-card[data-article-id="${articleId}"]`);
                            if (card) {
                                card.closest('.col-md-6').remove();
                            }
                            // Close the modal if it's open
                            const modal = document.getElementById('articleDetailModal');
                            const modalInstance = bootstrap.Modal.getInstance(modal);
                            if (modalInstance) {
                                modalInstance.hide();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Error deleting article',
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error deleting article',
                        icon: 'error'
                    });
                });
            }
        });
    }

    // Show/Hide loading overlay with SweetAlert2
    function showLoading() {
        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
    }

    function hideLoading() {
        Swal.close();
    }

    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Function to toggle image height
    function toggleImageHeight(button) {
        const image = document.getElementById('modalArticleImage');
        const icon = button.querySelector('i');
        
        if (image.classList.contains('full-height')) {
            image.classList.remove('full-height');
            icon.classList.remove('fa-compress');
            icon.classList.add('fa-expand');
        } else {
            image.classList.add('full-height');
            icon.classList.remove('fa-expand');
            icon.classList.add('fa-compress');
        }
    }
    </script>
</body>
</html> 