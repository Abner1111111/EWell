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
    <title>EWell - Tools</title>
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/variables.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/tools.css">
    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'includes/header.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="tools-container">
                <h2 class="section-title">Wellness Tools</h2>
                <div class="tools-grid">
                    <!-- BMI Calculator -->
                    <div class="tool-card">
                        <h3><i class="fas fa-calculator"></i> BMI Calculator</h3>
                        <div class="calculator-container">
                            <div class="calculator-input">
                                <input type="number" id="weight" placeholder="Weight (kg)" min="0" step="0.1">
                                <input type="number" id="height" placeholder="Height (cm)" min="0" step="0.1">
                            </div>
                            <button class="timer-btn" onclick="calculateBMI()">Calculate BMI</button>
                            <div class="calculator-result" id="bmi-result"></div>
                        </div>
                    </div>

                    <!-- Meditation Timer -->
                    <div class="tool-card">
                        <h3><i class="fas fa-clock"></i> Meditation Timer</h3>
                        <div class="timer-container">
                            <div class="timer-display" id="timer">00:00</div>
                            <div class="timer-controls">
                                <button class="timer-btn" onclick="startTimer(5)">5 min</button>
                                <button class="timer-btn" onclick="startTimer(10)">10 min</button>
                                <button class="timer-btn" onclick="startTimer(15)">15 min</button>
                                <button class="timer-btn" onclick="stopTimer()">Stop</button>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Notes -->
                    <div class="tool-card">
                        <h3><i class="fas fa-sticky-note"></i> Quick Notes</h3>
                        <div class="notes-container">
                            <ul class="notes-list" id="notes-list">
                                <li class="note-item">
                                    <span>Remember to drink water</span>
                                    <div class="note-actions">
                                        <button class="note-btn" onclick="editNote(this)"><i
                                                class="fas fa-edit"></i></button>
                                        <button class="note-btn" onclick="deleteNote(this)"><i
                                                class="fas fa-trash"></i></button>
                                    </div>
                                </li>
                            </ul>
                            <button class="add-note-btn" onclick="addNote()">Add Note</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // BMI Calculator
        function calculateBMI() {
            const weight = parseFloat(document.getElementById('weight').value);
            const height = parseFloat(document.getElementById('height').value) / 100; // Convert cm to m
            const result = document.getElementById('bmi-result');

            if (weight && height && weight > 0 && height > 0) {
                const bmi = weight / (height * height);
                let category = '';

                if (bmi < 18.5) category = 'Underweight';
                else if (bmi < 25) category = 'Normal weight';
                else if (bmi < 30) category = 'Overweight';
                else category = 'Obese';

                result.innerHTML = `
                    <p>Your BMI: <strong>${bmi.toFixed(1)}</strong></p>
                    <p>Category: <strong>${category}</strong></p>
                `;
            } else {
                result.textContent = 'Please enter valid weight and height values';
            }
        }

        // Timer
        let timerInterval;
        let timeLeft;

        function startTimer(minutes) {
            clearInterval(timerInterval);
            timeLeft = minutes * 60;
            updateTimerDisplay();

            timerInterval = setInterval(() => {
                timeLeft--;
                updateTimerDisplay();

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    playTimerSound();
                    alert('Time is up!');
                }
            }, 1000);
        }

        function stopTimer() {
            clearInterval(timerInterval);
            timeLeft = 0;
            updateTimerDisplay();
        }

        function updateTimerDisplay() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            document.getElementById('timer').textContent =
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }

        function playTimerSound() {
            const audio = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-alarm-digital-clock-beep-989.mp3');
            audio.play();
        }

        // Notes
        function addNote() {
            const note = prompt('Enter your note:');
            if (note && note.trim()) {
                const notesList = document.getElementById('notes-list');
                const noteItem = document.createElement('li');
                noteItem.className = 'note-item';
                noteItem.innerHTML = `
                    <span>${note.trim()}</span>
                    <div class="note-actions">
                        <button class="note-btn" onclick="editNote(this)"><i class="fas fa-edit"></i></button>
                        <button class="note-btn" onclick="deleteNote(this)"><i class="fas fa-trash"></i></button>
                    </div>
                `;
                notesList.appendChild(noteItem);
                saveNotes();
            }
        }

        function editNote(button) {
            const noteItem = button.closest('.note-item');
            const noteText = noteItem.querySelector('span').textContent;
            const newNote = prompt('Edit your note:', noteText);

            if (newNote && newNote.trim()) {
                noteItem.querySelector('span').textContent = newNote.trim();
                saveNotes();
            }
        }

        function deleteNote(button) {
            if (confirm('Are you sure you want to delete this note?')) {
                button.closest('.note-item').remove();
                saveNotes();
            }
        }

        function saveNotes() {
            const notes = Array.from(document.querySelectorAll('.note-item span')).map(span => span.textContent);
            localStorage.setItem('wellnessNotes', JSON.stringify(notes));
        }

        function loadNotes() {
            const savedNotes = localStorage.getItem('wellnessNotes');
            if (savedNotes) {
                const notes = JSON.parse(savedNotes);
                const notesList = document.getElementById('notes-list');
                notesList.innerHTML = notes.map(note => `
                    <li class="note-item">
                        <span>${note}</span>
                        <div class="note-actions">
                            <button class="note-btn" onclick="editNote(this)"><i class="fas fa-edit"></i></button>
                            <button class="note-btn" onclick="deleteNote(this)"><i class="fas fa-trash"></i></button>
                        </div>
                    </li>
                `).join('');
            }
        }

        // Load saved notes when page loads
        document.addEventListener('DOMContentLoaded', loadNotes);
    </script>
</body>

</html>