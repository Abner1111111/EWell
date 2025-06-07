<?php
session_start();
include '../db_connection/database.php';
include '../back_end/session.php';

// Get selected category from URL parameter
$selected_category = isset($_GET['category']) ? $_GET['category'] : 'All';

// Prepare the SQL query based on category selection
if ($selected_category !== 'All') {
    $sql = "SELECT * FROM lecture_videos WHERE category = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selected_category);
} else {
    $sql = "SELECT * FROM lecture_videos ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
$videos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EWell - Lecture Videos</title>
    <link rel="stylesheet" href="../css/variables.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .lecture-videos {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .videos-header {
            margin-bottom: 2rem;
        }

        .videos-header h1 {
            color: var(--dark-color);
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .videos-header p {
            color: var(--text-dark);
            font-size: 1rem;
            line-height: 1.6;
        }

        .videos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }

        .video-card {
            background: var(--bg-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .video-card:hover {
            transform: translateY(-5px);
        }

        .video-thumbnail {
            position: relative;
            width: 100%;
            padding-top: 56.25%; /* 16:9 Aspect Ratio */
            background: #f0f0f0;
            overflow: hidden;
        }

        .video-thumbnail img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .play-button i {
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .play-button:hover {
            background: var(--primary-color);
        }

        .play-button:hover i {
            color: white;
        }

        .video-info {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .video-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .video-description {
            color: var(--text-dark);
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1rem;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .video-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-light);
            font-size: 0.85rem;
            margin-top: auto;
        }

        .video-category {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
        }

        .filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            border: 1px solid var(--primary-color);
            border-radius: 50px;
            background: transparent;
            color: var(--primary-color);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--primary-color);
            color: white;
        }

        .no-videos {
            text-align: center;
            padding: 2rem;
            color: var(--text-dark);
            grid-column: 1 / -1;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 1000;
            overflow-y: auto;
        }

        .modal-content {
            position: relative;
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
            background: #000;
            border-radius: 12px;
            overflow: hidden;
        }

        .close-modal {
            position: absolute;
            right: 15px;
            top: 15px;
            color: white;
            font-size: 28px;
            cursor: pointer;
            z-index: 1001;
            width: 30px;
            height: 30px;
            background: rgba(0,0,0,0.5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        @media (max-width: 768px) {
            .lecture-videos {
                padding: 1rem;
            }

            .videos-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .modal-content {
                width: 95%;
                margin: 20px auto;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include 'includes/header.php'; ?>

        <main class="main-content">
            <div class="lecture-videos">
                <div class="videos-header">
                    <h1>Lecture Videos</h1>
                    <p>Access our comprehensive collection of health and wellness educational videos.</p>
                </div>

                <div class="filters">
                    <a href="?category=All" class="filter-btn <?php echo $selected_category === 'All' ? 'active' : ''; ?>">All</a>
                    <a href="?category=Physical Health" class="filter-btn <?php echo $selected_category === 'Physical Health' ? 'active' : ''; ?>">Physical Health</a>
                    <a href="?category=Mental Health" class="filter-btn <?php echo $selected_category === 'Mental Health' ? 'active' : ''; ?>">Mental Health</a>
                    <a href="?category=Nutrition" class="filter-btn <?php echo $selected_category === 'Nutrition' ? 'active' : ''; ?>">Nutrition</a>
                    <a href="?category=Exercise" class="filter-btn <?php echo $selected_category === 'Exercise' ? 'active' : ''; ?>">Exercise</a>
                    <a href="?category=Wellness" class="filter-btn <?php echo $selected_category === 'Wellness' ? 'active' : ''; ?>">Wellness</a>
                </div>

                <div class="videos-grid">
                    <?php if ($result->num_rows > 0): ?>
                        <?php foreach ($videos as $video): ?>
                            <div class="video-card" data-video-url="<?php echo htmlspecialchars($video['video_url']); ?>">
                                <div class="video-thumbnail">
                                    <img src="<?php echo htmlspecialchars($video['thumbnail_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($video['title']); ?> thumbnail">
                                    <div class="play-button">
                                        <i class="fas fa-play"></i>
                                    </div>
                                </div>
                                <div class="video-info">
                                    <h3 class="video-title"><?php echo htmlspecialchars($video['title']); ?></h3>
                                    <p class="video-description"><?php echo htmlspecialchars($video['description']); ?></p>
                                    <div class="video-meta">
                                        <span class="video-category"><?php echo htmlspecialchars($video['category']); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-videos">
                            <i class="fas fa-video fa-3x mb-3"></i>
                            <h3>No videos available</h3>
                            <p>There are no videos in this category yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <div id="videoModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="video-container">
                <iframe id="videoFrame" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Video Modal functionality
        const modal = document.getElementById('videoModal');
        const videoFrame = document.getElementById('videoFrame');
        const closeModal = document.querySelector('.close-modal');

        // Play button functionality
        document.querySelectorAll('.video-card').forEach(card => {
            card.addEventListener('click', () => {
                const videoUrl = card.dataset.videoUrl;
                videoFrame.src = `${videoUrl}?autoplay=1`;
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
            });
        });

        // Close modal functionality
        closeModal.addEventListener('click', () => {
            closeVideoModal();
        });

        // Close modal when clicking outside
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeVideoModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeVideoModal();
            }
        });

        function closeVideoModal() {
            modal.style.display = 'none';
            videoFrame.src = ''; // Stop the video
            document.body.style.overflow = ''; // Restore scrolling
        }
    </script>
</body>
</html>