<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EWell - Dashboard</title>
    <link rel="stylesheet" href="../css/variables.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            padding: 25px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .ranks-section {
            grid-column: 1;
        }

        .wellness-overview {
            grid-column: 2;
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .locations-section {
            grid-column: 1 / -1;
        }

        .rankings-list {
            margin: 20px 0;
        }

        .rank-item {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 15px;
            background: #f8f9fa;
            border-radius: 12px;
            transition: transform 0.3s ease;
        }

        .rank-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .rank-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 15px;
        }

        .rank-1 .rank-number {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #fff;
        }

        .rank-2 .rank-number {
            background: linear-gradient(135deg, #C0C0C0, #A9A9A9);
            color: #fff;
        }

        .rank-3 .rank-number {
            background: linear-gradient(135deg, #CD7F32, #8B4513);
            color: #fff;
        }

        .user-info {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
            border: 3px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-details h3 {
            margin: 0;
            font-size: 1rem;
            color: #333;
        }

        .user-details p {
            margin: 5px 0 0;
            font-size: 0.9rem;
            color: #666;
        }

        .rank-score {
            font-weight: bold;
            font-size: 1.1rem;
            color: var(--primary-color);
            padding: 5px 15px;
            background: rgba(var(--primary-color-rgb), 0.1);
            border-radius: 20px;
        }

        .wellness-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .stat-card i {
            font-size: 2rem;
            color: var(--primary-color);
            background: rgba(var(--primary-color-rgb), 0.1);
            padding: 15px;
            border-radius: 12px;
        }

        .stat-info h3 {
            font-size: 1rem;
            margin: 0;
            color: #666;
        }

        .stat-info p {
            margin: 5px 0 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }

        .locations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin: 25px 0;
        }

        .location-card {
            background: #f8f9fa;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .location-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .location-image {
            height: 200px;
            overflow: hidden;
        }

        .location-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .location-card:hover .location-image img {
            transform: scale(1.05);
        }

        .location-content {
            padding: 20px;
        }

        .location-content h3 {
            margin: 0 0 10px 0;
            font-size: 1.2rem;
            color: #333;
        }

        .location-content p {
            margin: 0 0 15px 0;
            font-size: 0.9rem;
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .location-content p i {
            color: var(--primary-color);
        }

        .upcoming-event {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .event-date {
            font-size: 0.9rem;
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .event-name {
            font-size: 0.9rem;
            color: var(--primary-color);
            font-weight: 500;
            background: rgba(var(--primary-color-rgb), 0.1);
            padding: 5px 10px;
            border-radius: 15px;
        }

        .map-container {
            margin-top: 25px;
        }

        .map {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .action-btn {
            display: inline-block;
            padding: 12px 25px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-align: center;
        }

        .action-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
                padding: 15px;
                gap: 20px;
            }

            .ranks-section,
            .wellness-overview {
                grid-column: 1;
            }

            .wellness-stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .podium-container-modern {
                gap: 12px;
            }

            .podium-modern {
                width: 100px;
                min-height: 120px;
            }

            .podium-1 {
                min-height: 160px;
            }

            .podium-2 {
                min-height: 100px;
            }

            .podium-3 {
                min-height: 80px;
            }
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                padding: 10px;
                gap: 15px;
            }

            .wellness-stats {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-card i {
                font-size: 1.5rem;
                padding: 12px;
            }

            .stat-info p {
                font-size: 1.2rem;
            }

            .podium-container-modern {
                flex-direction: column;
                align-items: center;
                gap: 20px;
            }

            .podium-modern {
                width: 100%;
                max-width: 280px;
                min-height: 100px;
                margin-bottom: 10px;
            }

            .podium-1 {
                min-height: 120px;
                order: -1;
            }

            .podium-2 {
                min-height: 100px;
            }

            .podium-3 {
                min-height: 100px;
            }

            .trophy-badge {
                top: -25px;
                font-size: 1.8rem;
            }

            .rank-badge {
                top: -15px;
                width: 28px;
                height: 28px;
                font-size: 1rem;
            }

            .avatar-badge {
                width: 40px;
                height: 40px;
                margin-top: 8px;
            }

            .avatar-badge img {
                width: 34px;
                height: 34px;
            }

            .podium-name {
                font-size: 0.9rem;
            }

            .podium-role {
                font-size: 0.8rem;
            }

            .podium-score {
                font-size: 0.85rem;
                padding: 3px 10px;
            }

            .locations-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .location-card {
                max-width: 100%;
            }

            .location-image {
                height: 180px;
            }

            .location-content {
                padding: 15px;
            }

            .location-content h3 {
                font-size: 1.1rem;
            }

            .location-content p {
                font-size: 0.85rem;
            }

            .upcoming-event {
                margin-top: 10px;
                padding-top: 10px;
            }

            .event-date,
            .event-name {
                font-size: 0.85rem;
            }

            .map-container {
                margin-top: 15px;
            }

            .map iframe {
                height: 180px;
            }

            .action-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .dashboard-grid {
                padding: 8px;
                gap: 12px;
            }

            .wellness-stats {
                gap: 10px;
            }

            .stat-card {
                padding: 12px;
            }

            .stat-card i {
                font-size: 1.3rem;
                padding: 10px;
            }

            .stat-info h3 {
                font-size: 0.9rem;
            }

            .stat-info p {
                font-size: 1.1rem;
            }

            .podium-modern {
                max-width: 240px;
            }

            .location-image {
                height: 160px;
            }

            .map iframe {
                height: 160px;
            }
        }

        /* Modern Podium Styles for Top Quiz Performers */
        .podium-container-modern {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 18px;
            margin-top: 28px;
        }
        .podium-modern {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            border-radius: 16px 16px 10px 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: relative;
            width: 120px;
            min-height: 140px;
            padding: 18px 6px 14px 6px;
        }
        .podium-1 {
            background: linear-gradient(180deg, #ffe066 0%, #ffd700 100%);
            z-index: 2;
            min-height: 180px;
        }
        .podium-2 {
            background: linear-gradient(180deg, #f3f3f3 0%, #bdbdbd 100%);
            z-index: 1;
            min-height: 120px;
        }
        .podium-3 {
            background: linear-gradient(180deg, #f7e3d0 0%, #cd7f32 100%);
            z-index: 1;
            min-height: 100px;
        }
        .rank-badge {
            position: absolute;
            top: -18px;
            left: 50%;
            transform: translateX(-50%);
            width: 32px;
            height: 32px;
            background: #e0e0e0;
            color: #888;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: bold;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            border: 2px solid #fff;
            z-index: 2;
        }
        .trophy-badge {
            position: absolute;
            top: -32px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.5rem;
            color: #ffd700;
            filter: drop-shadow(0 1px 3px rgba(0,0,0,0.10));
            z-index: 3;
        }
        .avatar-badge {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.10);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            margin-top: 12px;
            overflow: hidden;
        }
        .avatar-badge img {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border-radius: 50%;
            display: block;
        }
        .podium-name {
            font-weight: 700;
            color: #222;
            margin-bottom: 1px;
            font-size: 0.92rem;
            text-align: center;
        }
        .podium-role {
            font-size: 0.82rem;
            color: #888;
            margin-bottom: 5px;
            text-align: center;
        }
        .podium-score {
            font-size: 0.88rem;
            font-weight: 500;
            color: #222;
            background: #fff;
            border-radius: 12px;
            padding: 4px 12px;
            margin-top: 4px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            letter-spacing: 0.3px;
        }
        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            .ranks-section,
            .wellness-overview {
                grid-column: 1;
            }
            .podium-container-modern {
                gap: 15px;
            }
        }
        @media (max-width: 768px) {
            .podium-container-modern {
                flex-direction: column-reverse;
                align-items: center;
            }
            .podium-modern {
                width: 100%;
                max-width: 260px;
                margin-bottom: 0;
            }
            .trophy-badge {
                top: -35px;
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <?php include('includes/header.php'); ?>

        <!-- Main Content -->
        <main class="main-content">
         
            <div class="dashboard-grid">
                <!-- Left Column: Top Quiz Performers as Podium -->
                <section class="dashboard-card ranks-section">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3>Top Quiz Performers</h3>
                    </div>
                    <div class="podium-container-modern">
                        <!-- 2nd Place -->
                        <div class="podium-modern podium-2">
                            <div class="rank-badge">2</div>
                            <div class="avatar-badge">
                                <img src="../main/image/3.png" alt="Second User">
                            </div>
                            <div class="podium-name">Neil Morala</div>
                            <div class="podium-role">Chief Accountant</div>
                            <div class="podium-score">82 pts</div>
                        </div>
                        <!-- 1st Place -->
                        <div class="podium-modern podium-1">
                            <div class="trophy-badge"><i class="fas fa-trophy"></i></div>
                            <div class="rank-badge">1</div>
                            <div class="avatar-badge">
                                <img src="../main/image/pic/11.png" alt="Top User">
                            </div>
                            <div class="podium-name">Hazel E. Hautea</div>
                            <div class="podium-role">Chief Admin Officer</div>
                            <div class="podium-score">95 pts</div>
                        </div>
                        <!-- 3rd Place -->
                        <div class="podium-modern podium-3">
                            <div class="rank-badge">3</div>
                            <div class="avatar-badge">
                                <img src="../main/image/4.png" alt="Third User">
                            </div>
                            <div class="podium-name">Jinnard Lubaton</div>
                            <div class="podium-role">Bookkeeper</div>
                            <div class="podium-score">78 pts</div>
                        </div>
                    </div>
                    <div class="ranks-cta" style="text-align:center; margin-top:20px;">
                        <a href="quiz.html" class="action-btn">Start Quiz</a>
                    </div>
                </section>

                <!-- Right Column: Wellness Journey -->
                <section class="wellness-overview">
                    <h2>Your Wellness Journey</h2>
                    <div class="wellness-stats">
                        <div class="stat-card">
                            <i class="fas fa-book"></i>
                            <div class="stat-info">
                                <h3>Journal Entries</h3>
                                <p>0</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-spa"></i>
                            <div class="stat-info">
                                <h3>Relaxation Sessions</h3>
                                <p>0</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-lightbulb"></i>
                            <div class="stat-info">
                                <h3>Quiz Points</h3>
                                <p>0</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-utensils"></i>
                            <div class="stat-info">
                                <h3>Nutrition Logs</h3>
                                <p>0</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Full Width: Locations Section -->
                <section class="dashboard-card locations-section">
                    <div class="card-header">
                    <div class="card-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>Upcoming Events</h3>
                    </div>
                    <div class="locations-grid">
                        <div class="location-card">
                            <div class="location-image">
                                <img src="../main/image/pic/8.jpg" alt="MADZ Badminton Court">
                </div>
                            <div class="location-content">
                                <h3>MADZ Badminton Court</h3>
                                <p><i class="fas fa-map-marker-alt"></i> Zone 3, Koronadal City, 9506 South Cotabato</p>
                                <div class="upcoming-event">
                                    <span class="event-date">May 6, 2025</span>
                                    <span class="event-name">Wellness Day</span>
                    </div>
                        </div>
                        </div>
                        <div class="location-card">
                            <div class="location-image">
                                <img src="../main/image/pic/10.jpg" alt="EMR Center">
                    </div>
                            <div class="location-content">
                                <h3>EMR Center</h3>
                                <p><i class="fas fa-map-marker-alt"></i>GR5M+V76, Gensan Dr, Koronadal City, South Cotabato</p>
                                <div class="upcoming-event">
                                    <span class="event-date">May 6, 2025</span>
                                    <span class="event-name">Wellness Day</span>
                    </div>
                </div>
                        </div>
                        </div>
                    <div class="map-container">
                        <div class="map">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63330.17341775837!2d124.81625243372904!3d6.497395599999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32f81891a5db47b7%3A0xbc86d7213a5db1c8!2sKoronadal%20City%2C%20South%20Cotabato%2C%20Philippines!5e0!3m2!1sen!2s!4v1683101234567!5m2!1sen!2s"
                                width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script src="../js/dashboard.js"></script>
</body>

</html>