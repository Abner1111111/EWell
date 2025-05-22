document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    let selectedMood = null;
    let selectedTags = new Set();
    const journalForm = document.getElementById('journalForm');
    const tagInput = document.getElementById('tagInput');
    const selectedTagsContainer = document.querySelector('.selected-tags');
    const moodButtons = document.querySelectorAll('.mood-btn');
    const filterMood = document.getElementById('filterMood');
    const searchInput = document.getElementById('searchEntries');

    // Mood Selection
    moodButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove selected class from all buttons
            moodButtons.forEach(btn => btn.classList.remove('selected'));
            // Add selected class to clicked button
            this.classList.add('selected');
            selectedMood = this.dataset.mood;
        });
    });

    // Tag Management
    tagInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && this.value.trim()) {
            e.preventDefault();
            const tag = this.value.trim().toLowerCase();
            
            if (!selectedTags.has(tag)) {
                selectedTags.add(tag);
                addTagToContainer(tag);
            }
            
            this.value = '';
        }
    });

    function addTagToContainer(tag) {
        const tagElement = document.createElement('span');
        tagElement.className = 'tag';
        tagElement.innerHTML = `
            ${tag}
            <span class="remove-tag" data-tag="${tag}">&times;</span>
        `;
        
        tagElement.querySelector('.remove-tag').addEventListener('click', function() {
            selectedTags.delete(tag);
            tagElement.remove();
        });
        
        selectedTagsContainer.appendChild(tagElement);
    }

    // Form Submission
    journalForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!selectedMood) {
            alert('Please select your mood');
            return;
        }

        const formData = {
            title: document.getElementById('entryTitle').value,
            content: document.getElementById('entryContent').value,
            mood: selectedMood,
            tags: Array.from(selectedTags),
            date: new Date().toISOString()
        };

        // Send to server
        saveJournalEntry(formData);
    });

    // Save Journal Entry
    function saveJournalEntry(entry) {
        fetch('../back_end/journal_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(entry)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new entry to the list
                addEntryToList(entry);
                // Reset form
                resetForm();
                // Show success message
                showNotification('Entry saved successfully!', 'success');
            } else {
                showNotification('Error saving entry', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error saving entry', 'error');
        });
    }

    // Add Entry to List
    function addEntryToList(entry) {
        const entriesList = document.querySelector('.entries-list');
        const entryElement = document.createElement('article');
        entryElement.className = 'journal-entry';
        
        entryElement.innerHTML = `
            <div class="entry-header">
                <h3>${entry.title}</h3>
                <span class="entry-date">${formatDate(entry.date)}</span>
            </div>
            <div class="entry-mood">
                <span class="mood-icon">${getMoodEmoji(entry.mood)}</span>
                <span class="mood-text">${capitalizeFirstLetter(entry.mood)}</span>
            </div>
            <p class="entry-preview">${entry.content.substring(0, 100)}${entry.content.length > 100 ? '...' : ''}</p>
            <div class="entry-tags">
                ${entry.tags.map(tag => `<span class="tag">${tag}</span>`).join('')}
            </div>
            <div class="entry-actions">
                <button class="action-btn view-btn" data-id="${entry.id}">
                    <i class="fas fa-eye"></i>
                    View
                </button>
                <button class="action-btn edit-btn" data-id="${entry.id}">
                    <i class="fas fa-edit"></i>
                    Edit
                </button>
                <button class="action-btn delete-btn" data-id="${entry.id}">
                    <i class="fas fa-trash"></i>
                    Delete
                </button>
            </div>
        `;

        // Add event listeners to buttons
        addEntryButtonListeners(entryElement);
        
        // Add to the beginning of the list
        entriesList.insertBefore(entryElement, entriesList.firstChild);
    }

    // Add Event Listeners to Entry Buttons
    function addEntryButtonListeners(entryElement) {
        entryElement.querySelector('.view-btn').addEventListener('click', function() {
            const entryId = this.dataset.id;
            viewEntry(entryId);
        });

        entryElement.querySelector('.edit-btn').addEventListener('click', function() {
            const entryId = this.dataset.id;
            editEntry(entryId);
        });

        entryElement.querySelector('.delete-btn').addEventListener('click', function() {
            const entryId = this.dataset.id;
            deleteEntry(entryId);
        });
    }

    // Entry Actions
    function viewEntry(entryId) {
        // Implement view functionality
        console.log('Viewing entry:', entryId);
    }

    function editEntry(entryId) {
        // Implement edit functionality
        console.log('Editing entry:', entryId);
    }

    function deleteEntry(entryId) {
        if (confirm('Are you sure you want to delete this entry?')) {
            fetch(`../back_end/journal_handler.php?id=${entryId}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove entry from DOM
                    document.querySelector(`[data-id="${entryId}"]`).closest('.journal-entry').remove();
                    showNotification('Entry deleted successfully!', 'success');
                } else {
                    showNotification('Error deleting entry', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error deleting entry', 'error');
            });
        }
    }

    // Filter and Search
    filterMood.addEventListener('change', filterEntries);
    searchInput.addEventListener('input', filterEntries);

    function filterEntries() {
        const moodFilter = filterMood.value.toLowerCase();
        const searchTerm = searchInput.value.toLowerCase();
        const entries = document.querySelectorAll('.journal-entry');

        entries.forEach(entry => {
            const entryMood = entry.querySelector('.mood-text').textContent.toLowerCase();
            const entryContent = entry.querySelector('.entry-preview').textContent.toLowerCase();
            const entryTitle = entry.querySelector('h3').textContent.toLowerCase();

            const matchesMood = !moodFilter || entryMood === moodFilter;
            const matchesSearch = !searchTerm || 
                                entryContent.includes(searchTerm) || 
                                entryTitle.includes(searchTerm);

            entry.style.display = matchesMood && matchesSearch ? 'block' : 'none';
        });
    }

    // Utility Functions
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString(undefined, options);
    }

    function getMoodEmoji(mood) {
        const emojis = {
            happy: '😊',
            calm: '😌',
            sad: '😢',
            angry: '😠',
            anxious: '😰'
        };
        return emojis[mood] || '😐';
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function resetForm() {
        journalForm.reset();
        selectedMood = null;
        selectedTags.clear();
        selectedTagsContainer.innerHTML = '';
        moodButtons.forEach(btn => btn.classList.remove('selected'));
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

    // Load initial entries
    loadEntries();
});

// Load Journal Entries
function loadEntries() {
    fetch('../back_end/journal_handler.php')
        .then(response => response.json())
        .then(entries => {
            const entriesList = document.querySelector('.entries-list');
            entriesList.innerHTML = ''; // Clear existing entries
            
            entries.forEach(entry => {
                addEntryToList(entry);
            });
        })
        .catch(error => {
            console.error('Error loading entries:', error);
            showNotification('Error loading entries', 'error');
        });
} 