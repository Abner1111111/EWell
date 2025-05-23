<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/relaxation.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/User_dashboard.css">
    <link rel="stylesheet" href="../css/User_header.css">
</head>
</head>

<body>
    <div class="dashboard-container">

        <!-- Header Navigation -->
        <?php include 'includes/header.php' ?>

        <main class="main-content">
            <header class="content-header">
                <h1>Welcome to EWell</h1>
            </header>

            <section class="feature-grid">
                <div class="feature-card" id="breathing-exercises">
                    <h3>Breathing Exercises</h3>
                    <div class="breathing-circle"></div>
                    <button class="start-btn">Start Session</button>
                </div>
            </section>
        </main>
    </div>


    <script src="../js/User_header.js"></script>
    <script src="../js/relaxation.js"></script>
</body>

</html>