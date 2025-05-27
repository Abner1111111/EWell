<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EWell - Relaxation & Mindfulness</title>
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/variables.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/tools.css">
    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/relaxation.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>

    <div class="dashboard-container">
        <?php include 'includes/header.php'; ?>

        <div class="relaxation-container">
            <header class="relaxation-header">
                <h1>Relaxation & Mindfulness</h1>
                <div class="mood-tracker">
                    <h3>How are you feeling today?</h3>
                    <div class="mood-options">
                        <button class="mood-btn" data-mood="happy">😊</button>
                        <button class="mood-btn" data-mood="calm">😌</button>
                        <button class="mood-btn" data-mood="stressed">😰</button>
                        <button class="mood-btn" data-mood="tired">😴</button>
                    </div>
                </div>
            </header>

            <main class="relaxation-content">
                <section class="feature-grid">
                    <div class="feature-card" id="breathing-exercises">
                        <h3>Breathing Exercises</h3>
                        <div class="breathing-circle"></div>
                        <button class="start-btn">Start Session</button>
                    </div>

                    <div class="feature-card" id="meditation">
                        <h3>Meditation Sessions</h3>
                        <div class="session-list">
                            <button class="session-btn">5-Minute Focus</button>
                            <button class="session-btn">10-Minute Calm</button>
                            <button class="session-btn">15-Minute Deep</button>
                        </div>
                    </div>

                    <div class="feature-card" id="yoga">
                        <h3>Gentle Yoga</h3>
                        <div class="yoga-routines">
                            <button class="routine-btn">Morning Stretch</button>
                            <button class="routine-btn">Stress Relief</button>
                            <button class="routine-btn">Evening Wind Down</button>
                        </div>
                    </div>

                    <div class="feature-card" id="journal">
                        <h3>Mood Journal</h3>
                        <textarea placeholder="How are you feeling today?"></textarea>
                        <button class="save-btn">Save Entry</button>
                    </div>

                    <div class="feature-card" id="zen-garden">
                        <h3>Virtual Zen Garden</h3>
                        <canvas id="zenCanvas"></canvas>
                        <div class="zen-tools">
                            <button class="tool-btn">Rake</button>
                            <button class="tool-btn">Stones</button>
                            <button class="tool-btn">Clear</button>
                        </div>
                    </div>

                    <div class="feature-card" id="quotes">
                        <h3>Daily Inspiration</h3>
                        <div class="quote-display">
                            <p id="daily-quote">Loading...</p>
                        </div>
                        <button class="refresh-quote">New Quote</button>
                    </div>
                </section>
            </main>

            <div class="break-reminder">
                <p>Time for a mindful break?</p>
                <button class="reminder-btn">Take a Break</button>
            </div>
        </div>
    </div>

    <script src="../js/relaxation.js"></script>
</body>

</html>