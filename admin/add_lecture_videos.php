<?php
session_start();
include '../db_connection/database.php';
include '../back_end/session.php';
include '../back_end/youtube_helper.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $video_url = $_POST['videoUrl'];
    $category = $_POST['videoCategory'];
    $description = $_POST['videoDescription'];
    
    // Get video details
    $video_details = getYoutubeVideoDetails($video_url);
    
    if ($video_details['success']) {
        $data = $video_details['data'];
        
        // Insert into database
        $sql = "INSERT INTO lecture_videos (title, description, video_url, category, thumbnail_url) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", 
            $data['title'],
            $description,
            $data['video_url'],
            $category,
            $data['thumbnail_url']
        );
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Video added successfully!";
        } else {
            $_SESSION['error_message'] = "Error adding video: " . $conn->error;
        }
        
        $stmt->close();
    } else {
        $_SESSION['error_message'] = $video_details['message'];
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EWell Admin - Add Lecture Videos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/add_lecture_videos.css">
    <link rel="stylesheet" href="../css/variables.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/admin_sidebar.css">
    <link rel="stylesheet" href="../css/admin_create_quiz.css">
    <link rel="stylesheet" href="../css/admin_manage_user.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <?php include 'include/sidebar.php'; ?>
        <main class="main-content">
            <?php include 'include/header.php'; ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="video-input-section">
                            <div class="form-header">
                                <h2><i class="fas fa-link"></i> Add YouTube Video</h2>
                            </div>
                            
                            <div class="form-content">
                                <form id="addVideoForm" method="POST">
                                    <div class="url-input-group">
                                        <input type="url" id="videoUrl" name="videoUrl" class="form-control" 
                                               placeholder="Paste YouTube video URL here" required>
                                        <button type="button" class="fetch-btn" id="fetchDetails">
                                            <i class="fas fa-sync-alt"></i> Fetch Details
                                        </button>
                                    </div>
                                    <div class="url-example">
                                        Example: https://www.youtube.com/watch?v=VIDEO_ID or https://youtu.be/VIDEO_ID
                                    </div>

                                    <div class="error-container" id="errorMessage">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span></span>
                                    </div>

                                    <div id="videoPreview" class="video-preview-section">
                                        <div class="preview-content">
                                            <h2 id="titlePreview" class="preview-title"></h2>
                                            <div class="preview-embed" id="embedPreview">
                                                <iframe src="" allowfullscreen></iframe>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="videoDescription" class="form-label">Description</label>
                                        <textarea id="videoDescription" name="videoDescription" class="form-control" 
                                                rows="4" placeholder="Enter a detailed description of the video content" required></textarea>
                                        <div class="form-text">
                                            Provide a clear and informative description to help users understand the video content
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="videoCategory" class="form-label">Category</label>
                                        <select id="videoCategory" name="videoCategory" class="form-select" required>
                                            <option value="">Select Category</option>
                                            <option value="Physical Health">Physical Health</option>
                                            <option value="Mental Health">Mental Health</option>
                                            <option value="Nutrition">Nutrition</option>
                                            <option value="Exercise">Exercise</option>
                                            <option value="Wellness">Wellness</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="submit-btn" id="submitButton" disabled>
                                        <i class="fas fa-plus"></i>Add Video
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Videos List -->
                    <div class="col-md-4">
                        <div class="video-list">
                            <div class="card-header">
                                <h5><i class="fas fa-list"></i> Recent Videos</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM lecture_videos ORDER BY created_at DESC LIMIT 10";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                echo "<tr data-video-id='" . $row['id'] . "'>";
                                                echo "<td class='text-truncate' style='max-width: 200px;'>" . htmlspecialchars($row['title']) . "</td>";
                                                echo "<td><span class='badge bg-primary'>" . htmlspecialchars($row['category']) . "</span></td>";
                                                echo "<td class='text-end'>
                                                        <button class='btn btn-sm btn-danger delete-video' 
                                                                data-video-id='" . $row['id'] . "'
                                                                data-video-title='" . htmlspecialchars($row['title'], ENT_QUOTES) . "'>
                                                            <i class='fas fa-trash-alt'></i>
                                                        </button>
                                                      </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='3' class='text-center py-3'>
                                                    <div class='empty-state'>
                                                        <i class='fas fa-video'></i>
                                                        <p>No videos added yet</p>
                                                    </div>
                                                  </td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-trash-alt text-danger me-2"></i>
                        Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete "<strong><span id="deleteVideoTitle"></span></strong>"?</p>
                    <small class="text-danger">This action cannot be undone.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <i class="fas fa-trash-alt me-2"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i>
                    Video deleted successfully
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin_sidebar.js"></script>
    <script>
        const videoUrlInput = document.getElementById('videoUrl');
        const fetchButton = document.getElementById('fetchDetails');
        const submitButton = document.getElementById('submitButton');
        const errorMessage = document.getElementById('errorMessage');
        const videoPreview = document.getElementById('videoPreview');
        let currentVideoData = null;

        function showError(message) {
            errorMessage.querySelector('span').textContent = message;
            errorMessage.classList.add('show');
            videoPreview.classList.remove('active');
            submitButton.disabled = true;
        }

        function clearError() {
            errorMessage.querySelector('span').textContent = '';
            errorMessage.classList.remove('show');
        }

        function fetchVideoDetails() {
            const url = videoUrlInput.value.trim();
            if (!url) {
                showError('Please enter a YouTube video URL');
                return;
            }

            // Show loading state
            fetchButton.disabled = true;
            fetchButton.classList.add('loading');
            fetchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Fetching...';
            clearError();
            
            // Fetch video details
            fetch('../back_end/youtube_helper.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=fetch_video_details&url=' + encodeURIComponent(url)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Store the current video data
                    currentVideoData = data.data;
                    
                    // Update preview
                    document.getElementById('titlePreview').textContent = data.data.title;
                    
                    // Update embed preview
                    const embedPreview = document.getElementById('embedPreview');
                    embedPreview.querySelector('iframe').src = data.data.video_url;
                    
                    videoPreview.classList.add('active');
                    submitButton.disabled = false;
                    clearError();
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Failed to fetch video details. Please try again.');
            })
            .finally(() => {
                // Reset button state
                fetchButton.disabled = false;
                fetchButton.classList.remove('loading');
                fetchButton.innerHTML = '<i class="fas fa-sync-alt"></i> Fetch Details';
            });
        }

        // Event listeners
        fetchButton.addEventListener('click', fetchVideoDetails);
        videoUrlInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                fetchVideoDetails();
            }
        });

        // Form validation
        document.getElementById('addVideoForm').addEventListener('submit', function(e) {
            if (!currentVideoData) {
            e.preventDefault();
                showError('Please fetch video details first');
            }
        });

        // Delete video functionality
        const deleteButtons = document.querySelectorAll('.delete-video');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const successToast = new bootstrap.Toast(document.getElementById('successToast'), {
            delay: 3000
        });

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const videoId = this.dataset.videoId;
                const videoTitle = this.dataset.videoTitle;
                
                // Update modal content
                document.getElementById('deleteVideoTitle').textContent = videoTitle;
                
                // Set up confirm delete handler
                const confirmButton = document.getElementById('confirmDelete');
                confirmButton.onclick = function() {
                    // Show loading state
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Deleting...';
                    
                    // Send delete request
                    fetch('../back_end/delete_video.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'video_id=' + encodeURIComponent(videoId)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the row from the table
                            const row = document.querySelector(`tr[data-video-id="${videoId}"]`);
                            row.remove();
                            
                            // Show success toast
                            successToast.show();
                            
                            // Close modal
                            deleteModal.hide();
                            
                            // Check if table is empty
                            const tbody = document.querySelector('.table tbody');
                            if (tbody.children.length === 0) {
                                tbody.innerHTML = `
                                    <tr>
                                        <td colspan="3" class="text-center py-3">
                                            <div class="empty-state">
                                                <i class="fas fa-video"></i>
                                                <p>No videos added yet</p>
                                            </div>
                                        </td>
                                    </tr>`;
                            }
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error deleting video: ' + error.message);
                    })
                    .finally(() => {
                        // Reset button state
                        confirmButton.disabled = false;
                        confirmButton.innerHTML = '<i class="fas fa-trash-alt me-2"></i>Delete';
                    });
                };
                
                deleteModal.show();
            });
        });
    </script>
</body>
</html> 