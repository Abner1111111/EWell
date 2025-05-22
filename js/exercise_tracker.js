document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    const exerciseForm = document.getElementById('exerciseForm');
    const filterType = document.getElementById('filterType');
    const filterDate = document.getElementById('filterDate');
    const caloriesElement = document.querySelector('.calories');
    const durationElement = document.querySelector('.duration');
    const exerciseCountElement = document.querySelector('.exercise-count');

    // Exercise Form Submission
    exerciseForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            type: document.getElementById('exerciseType').value,
            duration: parseInt(document.getElementById('duration').value),
            intensity: document.getElementById('intensity').value,
            notes: document.getElementById('notes').value,
            date: new Date().toISOString()
        };

        // Calculate calories based on exercise type and duration
        formData.calories = calculateCalories(formData.type, formData.duration, formData.intensity);

        // Send to server
        saveExercise(formData);
    });

    // Save Exercise
    function saveExercise(exercise) {
        fetch('../back_end/exercise_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(exercise)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new entry to the list
                addExerciseToList(exercise);
                // Update summary
                updateSummary();
                // Reset form
                exerciseForm.reset();
                // Show success message
                showNotification('Exercise logged successfully!', 'success');
            } else {
                showNotification('Error logging exercise', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error logging exercise', 'error');
        });
    }

    // Add Exercise to List
    function addExerciseToList(exercise) {
        const historyList = document.querySelector('.history-list');
        const exerciseElement = document.createElement('article');
        exerciseElement.className = 'exercise-entry';
        
        exerciseElement.innerHTML = `
            <div class="entry-header">
                <h3>${capitalizeFirstLetter(exercise.type)}</h3>
                <span class="entry-date">${formatDate(exercise.date)}</span>
            </div>
            <div class="entry-details">
                <div class="detail">
                    <i class="fas fa-clock"></i>
                    <span>${exercise.duration} minutes</span>
                </div>
                <div class="detail">
                    <i class="fas fa-fire"></i>
                    <span>${exercise.calories} kcal</span>
                </div>
                <div class="detail">
                    <i class="fas fa-bolt"></i>
                    <span>${capitalizeFirstLetter(exercise.intensity)} Intensity</span>
                </div>
            </div>
            <p class="entry-notes">${exercise.notes || 'No notes'}</p>
            <div class="entry-actions">
                <button class="action-btn edit-btn" data-id="${exercise.id}">
                    <i class="fas fa-edit"></i>
                    Edit
                </button>
                <button class="action-btn delete-btn" data-id="${exercise.id}">
                    <i class="fas fa-trash"></i>
                    Delete
                </button>
            </div>
        `;

        // Add event listeners to buttons
        addExerciseButtonListeners(exerciseElement);
        
        // Add to the beginning of the list
        historyList.insertBefore(exerciseElement, historyList.firstChild);
    }

    // Add Event Listeners to Exercise Buttons
    function addExerciseButtonListeners(exerciseElement) {
        exerciseElement.querySelector('.edit-btn').addEventListener('click', function() {
            const exerciseId = this.dataset.id;
            editExercise(exerciseId);
        });

        exerciseElement.querySelector('.delete-btn').addEventListener('click', function() {
            const exerciseId = this.dataset.id;
            deleteExercise(exerciseId);
        });
    }

    // Exercise Actions
    function editExercise(exerciseId) {
        // Implement edit functionality
        console.log('Editing exercise:', exerciseId);
    }

    function deleteExercise(exerciseId) {
        if (confirm('Are you sure you want to delete this exercise?')) {
            fetch(`../back_end/exercise_handler.php?id=${exerciseId}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove exercise from DOM
                    document.querySelector(`[data-id="${exerciseId}"]`).closest('.exercise-entry').remove();
                    // Update summary
                    updateSummary();
                    showNotification('Exercise deleted successfully!', 'success');
                } else {
                    showNotification('Error deleting exercise', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error deleting exercise', 'error');
            });
        }
    }

    // Filter and Search
    filterType.addEventListener('change', filterExercises);
    filterDate.addEventListener('change', filterExercises);

    function filterExercises() {
        const typeFilter = filterType.value.toLowerCase();
        const dateFilter = filterDate.value;
        const exercises = document.querySelectorAll('.exercise-entry');

        exercises.forEach(exercise => {
            const exerciseType = exercise.querySelector('h3').textContent.toLowerCase();
            const exerciseDate = exercise.querySelector('.entry-date').textContent;

            const matchesType = !typeFilter || exerciseType === typeFilter;
            const matchesDate = !dateFilter || exerciseDate.includes(dateFilter);

            exercise.style.display = matchesType && matchesDate ? 'block' : 'none';
        });
    }

    // Update Summary
    function updateSummary() {
        fetch('../back_end/exercise_handler.php?summary=true')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    caloriesElement.textContent = `${data.calories} kcal`;
                    durationElement.textContent = `${data.duration} min`;
                    exerciseCountElement.textContent = data.count;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Calculate Calories
    function calculateCalories(type, duration, intensity) {
        const intensityMultiplier = {
            low: 1,
            medium: 1.5,
            high: 2
        };

        const baseCaloriesPerMinute = {
            running: 10,
            walking: 4,
            cycling: 8,
            swimming: 7,
            yoga: 3,
            strength: 6,
            other: 5
        };

        const baseCalories = baseCaloriesPerMinute[type] || baseCaloriesPerMinute.other;
        return Math.round(baseCalories * duration * intensityMultiplier[intensity]);
    }

    // Utility Functions
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString(undefined, options);
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Load initial exercises and update summary
    loadExercises();
    updateSummary();
});

// Load Exercises
function loadExercises() {
    fetch('../back_end/exercise_handler.php')
        .then(response => response.json())
        .then(exercises => {
            const historyList = document.querySelector('.history-list');
            historyList.innerHTML = ''; // Clear existing exercises
            
            exercises.forEach(exercise => {
                addExerciseToList(exercise);
            });
        })
        .catch(error => {
            console.error('Error loading exercises:', error);
            showNotification('Error loading exercises', 'error');
        });
} 