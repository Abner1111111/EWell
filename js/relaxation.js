document.addEventListener('DOMContentLoaded', () => {
    // Initialize all features
    initBreathingExercise();
    initMoodTracker();
    initZenGarden();
    initQuoteSystem();
    initBreakReminder();
    initJournal();
});

// Breathing Exercise
function initBreathingExercise() {
    const breathingCircle = document.querySelector('.breathing-circle');
    const startBtn = document.querySelector('#breathing-exercises .start-btn');
    let isBreathing = false;
    let breathInterval;

    startBtn.addEventListener('click', () => {
        if (!isBreathing) {
            isBreathing = true;
            startBtn.textContent = 'Stop Session';
            breathInterval = setInterval(() => {
                breathingCircle.style.transform = 'scale(1.5)';
                setTimeout(() => {
                    breathingCircle.style.transform = 'scale(1)';
                }, 4000);
            }, 8000);
        } else {
            isBreathing = false;
            startBtn.textContent = 'Start Session';
            clearInterval(breathInterval);
            breathingCircle.style.transform = 'scale(1)';
        }
    });
}

// Mood Tracker
function initMoodTracker() {
    const moodButtons = document.querySelectorAll('.mood-btn');
    
    moodButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const mood = btn.dataset.mood;
            saveMood(mood);
            highlightSelectedMood(btn);
        });
    });
}

function saveMood(mood) {
    const date = new Date().toISOString().split('T')[0];
    const moodData = {
        date,
        mood,
        timestamp: new Date().toISOString()
    };

    // Save to localStorage for demo purposes
    let moods = JSON.parse(localStorage.getItem('moods') || '[]');
    moods.push(moodData);
    localStorage.setItem('moods', JSON.stringify(moods));
}

function highlightSelectedMood(selectedBtn) {
    document.querySelectorAll('.mood-btn').forEach(btn => {
        btn.style.transform = 'scale(1)';
    });
    selectedBtn.style.transform = 'scale(1.2)';
}

// Zen Garden
function initZenGarden() {
    const canvas = document.getElementById('zenCanvas');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;
    let currentTool = 'rake';

    // Set canvas size and handle resize
    function resizeCanvas() {
        const container = canvas.parentElement;
        canvas.width = container.clientWidth;
        canvas.height = 200; // Fixed height for better control
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
    }

    // Initial resize
    resizeCanvas();

    // Handle window resize
    window.addEventListener('resize', resizeCanvas);

    // Drawing tools
    const tools = document.querySelectorAll('.zen-tools .tool-btn');
    tools.forEach(tool => {
        tool.addEventListener('click', () => {
            currentTool = tool.textContent.toLowerCase();
            tools.forEach(t => t.classList.remove('active'));
            tool.classList.add('active');
        });
    });

    // Drawing functions
    function getMousePos(canvas, e) {
        const rect = canvas.getBoundingClientRect();
        return {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
    }

    function startDrawing(e) {
        isDrawing = true;
        const pos = getMousePos(canvas, e);
        [lastX, lastY] = [pos.x, pos.y];
    }

    function draw(e) {
        if (!isDrawing) return;

        const pos = getMousePos(canvas, e);
        
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(pos.x, pos.y);
        
        // Different styles for different tools
        if (currentTool === 'rake') {
            ctx.strokeStyle = '#7C9A92';
            ctx.lineWidth = 2;
            // Add some randomness to rake lines
            ctx.shadowColor = 'rgba(124, 154, 146, 0.3)';
            ctx.shadowBlur = 2;
        } else if (currentTool === 'stones') {
            ctx.strokeStyle = '#2C3E50';
            ctx.lineWidth = 20;
            ctx.shadowColor = 'rgba(44, 62, 80, 0.3)';
            ctx.shadowBlur = 4;
        }
        
        ctx.stroke();
        ctx.shadowBlur = 0; // Reset shadow

        [lastX, lastY] = [pos.x, pos.y];
    }

    function stopDrawing() {
        isDrawing = false;
    }

    // Event listeners
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);

    // Touch support
    canvas.addEventListener('touchstart', (e) => {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousedown', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    });

    canvas.addEventListener('touchmove', (e) => {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousemove', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    });

    canvas.addEventListener('touchend', (e) => {
        e.preventDefault();
        const mouseEvent = new MouseEvent('mouseup', {});
        canvas.dispatchEvent(mouseEvent);
    });

    // Clear canvas
    document.querySelector('.zen-tools .tool-btn:last-child').addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    });

    // Add sand texture
    function addSandTexture() {
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const data = imageData.data;
        
        for (let i = 0; i < data.length; i += 4) {
            if (data[i + 3] === 0) { // If pixel is transparent
                data[i] = 245; // R
                data[i + 1] = 245; // G
                data[i + 2] = 245; // B
                data[i + 3] = 255; // A
            }
        }
        
        ctx.putImageData(imageData, 0, 0);
    }

    // Initial sand texture
    addSandTexture();
}

// Quote System
function initQuoteSystem() {
    const quotes = [
        "Take a deep breath. You've got this.",
        "Inhale peace, exhale tension.",
        "Every moment is a fresh beginning.",
        "You are stronger than you think.",
        "Find peace in the present moment.",
        "Your calm mind is the ultimate weapon against challenges.",
        "Breathe in possibility, breathe out doubt."
    ];

    const quoteDisplay = document.getElementById('daily-quote');
    const refreshBtn = document.querySelector('.refresh-quote');

    function updateQuote() {
        const randomQuote = quotes[Math.floor(Math.random() * quotes.length)];
        quoteDisplay.textContent = randomQuote;
    }

    refreshBtn.addEventListener('click', updateQuote);
    updateQuote(); // Initial quote
}

// Break Reminder
function initBreakReminder() {
    const reminderBtn = document.querySelector('.reminder-btn');
    let reminderInterval;

    function startReminder() {
        reminderInterval = setInterval(() => {
            showReminder();
        }, 3600000); // Remind every hour
    }

    function showReminder() {
        const reminder = document.querySelector('.break-reminder');
        reminder.style.display = 'flex';
        reminder.style.animation = 'fadeIn 0.5s ease-in';
    }

    reminderBtn.addEventListener('click', () => {
        document.querySelector('.break-reminder').style.display = 'none';
    });

    startReminder();
}

// Journal
function initJournal() {
    const journalTextarea = document.querySelector('#journal textarea');
    const saveBtn = document.querySelector('#journal .save-btn');

    saveBtn.addEventListener('click', () => {
        const entry = journalTextarea.value;
        if (entry.trim()) {
            saveJournalEntry(entry);
            journalTextarea.value = '';
            showSaveConfirmation();
        }
    });
}

function saveJournalEntry(entry) {
    const date = new Date().toISOString();
    const journalData = {
        date,
        entry,
        timestamp: new Date().toISOString()
    };

    // Save to localStorage for demo purposes
    let entries = JSON.parse(localStorage.getItem('journalEntries') || '[]');
    entries.push(journalData);
    localStorage.setItem('journalEntries', JSON.stringify(entries));
}

function showSaveConfirmation() {
    const saveBtn = document.querySelector('#journal .save-btn');
    const originalText = saveBtn.textContent;
    saveBtn.textContent = 'Saved!';
    saveBtn.style.backgroundColor = '#4CAF50';
    
    setTimeout(() => {
        saveBtn.textContent = originalText;
        saveBtn.style.backgroundColor = '';
    }, 2000);
} 