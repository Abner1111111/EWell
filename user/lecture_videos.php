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
        }

        .video-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .video-description {
            color: var(--text-dark);
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .video-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-light);
            font-size: 0.85rem;
        }

        .video-duration {
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--primary-color);
            color: white;
        }

        @media (max-width: 768px) {
            .lecture-videos {
                padding: 1rem;
            }

            .videos-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1rem;
            }

            .video-info {
                padding: 1rem;
            }

            .video-title {
                font-size: 1rem;
            }

            .video-description {
                font-size: 0.85rem;
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
                    <button class="filter-btn active">All</button>
                    <button class="filter-btn">Physical Health</button>
                    <button class="filter-btn">Mental Health</button>
                    <button class="filter-btn">Nutrition</button>
                    <button class="filter-btn">Exercise</button>
                </div>

                <div class="videos-grid">

                         <!---->
                    <!-- NOTE: -->
            <!-- instead na (/watch?v=8BvLZbgb9CA)  i change to (embed/8BvLZbgb9CA)-->
              <!-------------------------------------------------------------->



                    <div class="video-card" data-video-url="https://www.youtube.com/embed/4WAgAxLx2WU">
                        <div class="video-thumbnail">
                            <img src="https://img.youtube.com/vi/4WAgAxLx2WU/maxresdefault.jpg" alt="Video thumbnail">
                            <div class="play-button">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-info">
                            <h3 class="video-title">THE BATTLE OF GODS!🔥 - GEN vs T1 GAME 1 LCK SPRING 2025 W7D5 GENG ESPORTS vs T1 G1 LCK 2025</h3>
                            <p class="video-description">GENG ESPORTS vs T1 GAME 1 LCK SPRING 2025 W7D5</p>
                            <div class="video-meta">
                                <div class="video-duration">
                                    <i class="far fa-clock"></i>
                                    <span>15:30</span>
                                </div>
                                <span class="video-category">Mental Health</span>
                            </div>
                        </div>
                    </div>

                    <div class="video-card" data-video-url="https://www.youtube.com/embed/8BvLZbgb9CA">
                        <div class="video-thumbnail">
                            <img src="https://img.youtube.com/vi/8BvLZbgb9CA/maxresdefault.jpg" alt="Video thumbnail">
                            <div class="play-button">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-info">
                            <h3 class="video-title">PALOAP</h3>
                            <p class="video-description">This vlog is also for you Boss Keng.</p>
                            <div class="video-meta">
                                <div class="video-duration">
                                    <i class="far fa-clock"></i>
                                    <span>20:15</span>
                                </div>
                                <span class="video-category">Nutrition</span>
                            </div>
                        </div>
                    </div>

                    <div class="video-card" data-video-url="https://www.youtube.com/embed/8mbKSTQRbRg">
                        <div class="video-thumbnail">
                            <img src="https://img.youtube.com/vi/8mbKSTQRbRg/maxresdefault.jpg" alt="Video thumbnail">
                            <div class="play-button">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-info">
                            <h3 class="video-title">LIFE AFTER COLLEGE 2 (Work Experience) | Pinoy Animation</h3>
                            <p class="video-description">HAPPY NEW YEARRRRR!!! Kumusta ang pagsalubong nyo sa bagong taon? Ako eto, ok naman. Stressed kasi andaming aberya bago ko naiupload tong video.... Hayst!! Pero buti ok na.</p>
                            <div class="video-meta">
                                <div class="video-duration">
                                    <i class="far fa-clock"></i>
                                    <span>18:45</span>
                                </div>
                                <span class="video-category">Exercise</span>
                            </div>
                        </div>
                    </div>

                    <div class="video-card" data-video-url="https://www.youtube.com/embed/VcxZL6wj2sA">
                        <div class="video-thumbnail">
                            <img src="https://img.youtube.com/vi/VcxZL6wj2sA/maxresdefault.jpg" alt="Video thumbnail">
                            <div class="play-button">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="video-info">
                            <h3 class="video-title">Cecilion Performs His Ever Best gameplay | TOP GLOBAL CECILION BEST BUILD AND EMBLEM</h3>
                            <p class="video-description">Cecilion Crushed enemies with 0 Death | BRUTAL DAMAGE | TOP GLOBAL CECILION BEST BUILD AND EMBLEM</p>
                            <div class="video-meta">
                                <div class="video-duration">
                                    <i class="far fa-clock"></i>
                                    <span>22:30</span>
                                </div>
                                <span class="video-category">Mental Health</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="videoModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000;">
        <div class="modal-content" style="position: relative; width: 80%; max-width: 1000px; margin: 50px auto; background: #000;">
            <span class="close-modal" style="position: absolute; right: -30px; top: -30px; color: white; font-size: 28px; cursor: pointer;">&times;</span>
            <div class="video-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                <iframe id="videoFrame" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <script>
        // Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const videoCards = document.querySelectorAll('.video-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                const category = button.textContent;
                
                videoCards.forEach(card => {
                    if (category === 'All') {
                        card.style.display = 'block';
                    } else {
                        const cardCategory = card.querySelector('.video-category').textContent;
                        card.style.display = cardCategory === category ? 'block' : 'none';
                    }
                });
            });
        });

        // Video Modal functionality
        const modal = document.getElementById('videoModal');
        const videoFrame = document.getElementById('videoFrame');
        const closeModal = document.querySelector('.close-modal');

        // Play button functionality
        const playButtons = document.querySelectorAll('.play-button');
        playButtons.forEach(button => {
            button.addEventListener('click', () => {
                const videoCard = button.closest('.video-card');
                const videoUrl = videoCard.dataset.videoUrl;
                videoFrame.src = `${videoUrl}?autoplay=1`;
                modal.style.display = 'block';
            });
        });

        // Close modal when clicking the close button
        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
            videoFrame.src = ''; // Stop the video
        });

        // Close modal when clicking outside the video
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
                videoFrame.src = ''; // Stop the video
            }
        });
    </script>
</body>
</html>