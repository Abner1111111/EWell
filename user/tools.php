<?php
function calculateBMI($weight, $height)
{
    if ($height > 0) {
        return round($weight / (($height / 100) ** 2), 2);
    }
    return 0;
}

function calculateCalories($age, $gender, $weight, $height, $activity)
{
    // Mifflin-St Jeor Equation
    $bmr = ($gender == 'male') ?
        (10 * $weight + 6.25 * $height - 5 * $age + 5) :
        (10 * $weight + 6.25 * $height - 5 * $age - 161);

    $multipliers = [
        'sedentary' => 1.2,
        'light' => 1.375,
        'moderate' => 1.55,
        'active' => 1.725,
        'very_active' => 1.9
    ];

    return round($bmr * $multipliers[$activity]);
}

// Results
$bmi = null;
$calories = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['calculate_bmi'])) {
        $bmi = calculateBMI($_POST['weight'], $_POST['height']);
    }

    if (isset($_POST['calculate_calories'])) {
        $calories = calculateCalories($_POST['age'], $_POST['gender'], $_POST['weight'], $_POST['height'], $_POST['activity']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tools - Ewell</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/User_dashboard.css">
    <link rel="stylesheet" href="../css/User_header.css">
    <style>
    .dashboard-content {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .dashboard-content h2 {
        color: #2c3e50;
        margin-bottom: 2rem;
        font-size: 2rem;
        font-weight: 600;
    }

    .tools-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .tool-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        /* or center if it's just icon + button */
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        width: 200px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .tool-icon {
        font-size: 2.5rem;
        color: #8CB369;
        margin-bottom: 15px;
    }

    .tool-card button {
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        background-color: #8CB369;
        color: white;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .tool-card button:hover {
        background-color: #A0C878;
    }


    .tool-card button i {
        font-size: 1.1rem;
    }

    .result-box {
        margin-top: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #8CB369;
    }

    .result-box strong {
        color: #2c3e50;
        font-size: 1.1rem;
    }

    .tool-icon {
        width: 40px;
        height: 40px;
        background: #f0f4e8;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .tool-icon i {
        color: #8CB369;
        font-size: 1.2rem;
        align-items: center;
    }

    .coming-soon {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        color: #6c757d;
    }

    .coming-soon i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: #8CB369;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        padding-top: 60px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fff;
        margin: auto;
        padding: 20px;
        border-radius: 8px;
        position: relative;
        max-width: 30%;
        width: fit-content;
        height: auto;
    }


    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover {
        color: black;
    }

    input[type="number"] {
        width: 100%;
        padding: 12px 15px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
        transition: 0.3s ease;
        font-size: 16px;
    }

    button {
        width: 100%;
        padding: 12px 15px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
        transition: 0.3s ease;
        font-size: 16px;
        background-color: #8CB369;
        color: white;
    }

    select {
        width: 100%;
        padding: 12px 15px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
        transition: 0.3s ease;
        font-size: 16px;
        color: gray;
    }
    </style>
</head>

<body>
    <div class="dashboard-container">

        <!-- Header Navigation -->
        <?php include 'includes/header.php' ?>

        <main class="main-content">

            <!-- Tool Sections -->
            <div class="dashboard-content">
                <h2><i class="fas fa-tools"></i> Health & Wellness Tools</h2>

                <div class="tools-grid">
                    <div class="tool-card">
                        <div class="tool-icon">
                            <i class="fas fa-weight"></i>
                        </div>
                        <button id="openBMIModal">BMI Calculator</button>
                    </div>

                    <!-- BMI Modal -->
                    <div id="bmiModal" class="modal">
                        <div class="modal-content">
                            <span class="close" id="closeBMIModal">&times;</span>
                            <h3>BMI Calculator</h3>
                            <form method="POST">
                                <input type="number" name="weight" placeholder="Weight (kg)" required
                                    value="<?= isset($_POST['weight']) ? htmlspecialchars($_POST['weight']) : '' ?>">
                                <input type="number" name="height" placeholder="Height (cm)" required
                                    value="<?= isset($_POST['height']) ? htmlspecialchars($_POST['height']) : '' ?>">

                                <button type="submit" name="calculate_bmi">
                                    <i class="fas fa-calculator"></i> Calculate BMI
                                </button>
                            </form>

                            <?php if (isset($bmi)): ?>
                            <div class="result-box">
                                <p>Your BMI is: <strong><?= $bmi ?></strong></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>


                    <div class="tool-card">
                        <div class="tool-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        <button id="openCalorieModal">Calorie Counter</button>
                    </div>

                    <!-- calorie modal -->
                    <div id="calorieModal" class="modal">
                        <div class="modal-content">
                            <span class="close" id="closeCalorieModal">&times;</span>
                            <h3>Calorie Counter</h3>
                            <form method="POST">
                                <input type="number" name="age" placeholder="Age" required>
                                <select name="gender" required>
                                    <option selected disabled value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <input type="number" name="weight" placeholder="Weight (kg)" required>
                                <input type="number" name="height" placeholder="Height (cm)" required>
                                <select name="activity" required>
                                    <option selected disabled value="">Select Activity Level</option>
                                    <option value="sedentary">Sedentary</option>
                                    <option value="light">Light Activity</option>
                                    <option value="moderate">Moderate Activity</option>
                                    <option value="active">Very Active</option>
                                    <option value="very_active">Extra Active</option>
                                </select>
                                <button type="submit" name="calculate_calories">
                                    <i class="fas fa-calculator"></i> Estimate Calories
                                </button>
                            </form>
                            <?php if ($calories !== null): ?>
                            <div class="result-box">
                                <p>Your daily caloric need is: <strong><?= $calories ?> kcal</strong></p>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div class="tool-card">
                        <div class="tool-icon">
                            <i class="fas fa-glass-water"></i>
                        </div>
                        <button id="openCalorieModal">Hydration Tracker</button>
                    </div>

                    <!-- hydration modal -->
                    <div id="hydrationModal" class="modal">
                        <div class="modal-content">
                            <span class="close" id="closeHydrationModal">&times;</span>
                            <h3>Hydration Tracker</h3>
                            <form method="POST">
                                <input type="number" step="0.1" name="water_intake" placeholder="Water intake (L)"
                                    required>
                                <button type="submit">Log Water</button>
                            </form>

                            <?php if (isset($hydration)): ?>
                            <div class="result-box">
                                <p><?= htmlspecialchars($hydration) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>


                </div>
            </div>

        </main>
    </div>

    <!-- bmi -->

    <script>
    const openBMIModal = document.getElementById("openBMIModal");
    const bmiModal = document.getElementById("bmiModal");
    const closeBMIModal = document.getElementById("closeBMIModal");

    openBMIModal.onclick = () => {
        bmiModal.style.display = "block";
    };

    closeBMIModal.onclick = () => {
        bmiModal.style.display = "none";
    };

    window.onclick = (event) => {
        if (event.target === bmiModal) {
            bmiModal.style.display = "none";
        }
    };
    </script>
    <script>
    // result
    <?php if ($bmi !== null): ?>
    window.onload = () => {
        document.getElementById("bmiModal").style.display = "block";
    };
    <?php endif; ?>
    </script>

    <!-- calorie counter -->
    <script>
    const openCalorieModal = document.getElementById("openCalorieModal");
    const calorieModal = document.getElementById("calorieModal");
    const closeCalorieModal = document.getElementById("closeCalorieModal");

    openCalorieModal.onclick = () => {
        calorieModal.style.display = "block";
    };

    closeCalorieModal.onclick = () => {
        calorieModal.style.display = "none";
    };

    window.onclick = (event) => {
        if (event.target === calorieModal) {
            calorieModal.style.display = "none";
        }
    };
    </script>
    <script>
    <?php if ($calories !== null): ?>
    window.onload = () => {
        document.getElementById("calorieModal").style.display = "block";
    };
    <?php endif; ?>
    </script>

    <!-- hydration script -->
    <script>
    const openHydrationModal = document.getElementById("openHydrationModal");
    const hydrationModal = document.getElementById("hydrationModal");
    const closeHydrationModal = document.getElementById("closeHydrationModal");

    if (openHydrationModal) {
        openHydrationModal.onclick = () => {
            hydrationModal.style.display = "block";
        };
    }

    closeHydrationModal.onclick = () => {
        hydrationModal.style.display = "none";
    };

    window.onclick = (event) => {
        if (event.target === hydrationModal) {
            hydrationModal.style.display = "none";
        }
    };
    </script>

    <script>
    <?php if (isset($hydration)): ?>
    window.onload = () => {
        document.getElementById("hydrationModal").style.display = "block";
    };
    <?php endif; ?>
    </script>



    <script src="../js/User_header.js"></script>
    <script src="../js/relaxation.js"></script>
</body>

</html>