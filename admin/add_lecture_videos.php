<?php
session_start();
include '../db_connection/database.php';
include '../back_end/session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EWell Admin - Add Lecture Videos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                        <div class="card video-form">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-video me-2"></i>Add Lecture Video
                                </h5>
                            </div>
                            <div class="card-body">
                                <form class="video-form" id="addVideoForm">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="videoTitle" class="form-label">Video Title</label>
                                            <input type="text" id="videoTitle" class="form-control" placeholder="Enter video title" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="videoDescription" class="form-label">Description</label>
                                            <textarea id="videoDescription" class="form-control" placeholder="Enter video description" required></textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="videoUrl" class="form-label">Video URL</label>
                                            <input type="url" id="videoUrl" class="form-control" placeholder="Enter video URL (YouTube/Vimeo)" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="videoCategory" class="form-label">Category</label>
                                            <select id="videoCategory" class="form-select" required>
                                                <option value="">Select Category</option>
                                                <option value="Physical Health">Physical Health</option>
                                                <option value="Mental Health">Mental Health</option>
                                                <option value="Nutrition">Nutrition</option>
                                                <option value="Exercise">Exercise</option>
                                                <option value="Wellness">Wellness</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="videoThumbnail" class="form-label">Thumbnail Image</label>
                                            <input type="file" id="videoThumbnail" class="form-control" accept="image/*" required>
                                            <div class="thumbnail-preview mt-2">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label class="form-label">Video Duration</label>
                                            <div class="duration-input d-flex gap-2">
                                                <input type="number" id="durationMinutes" class="form-control" placeholder="Minutes" min="0" max="59" required>
                                                <input type="number" id="durationSeconds" class="form-control" placeholder="Seconds" min="0" max="59" required>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Add Video
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Videos List -->
                    <div class="col-md-4">
                        <div class="card video-list">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-list me-2"></i>Recent Videos
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Category</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- This will be populated dynamically -->
                                            <tr>
                                                <td colspan="3" class="text-center">No videos added yet</td>
                                            </tr>
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
        // Thumbnail preview
        const thumbnailInput = document.getElementById('videoThumbnail');
        const thumbnailPreview = document.querySelector('.thumbnail-preview');

        thumbnailInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    thumbnailPreview.innerHTML = `<img src="${e.target.result}" alt="Thumbnail Preview" class="img-fluid">`;
                }
                reader.readAsDataURL(file);
            }
        });

        // Form submission
        const form = document.getElementById('addVideoForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            // Here you would typically handle the form submission
            alert('Video added successfully! (This is a static demo)');
            form.reset();
            thumbnailPreview.innerHTML = '<i class="fas fa-image"></i>';
        });
    </script>
</body>
</html> 