<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EWell - Journal</title>
    <link rel="stylesheet" href="../css/journal.css">
    <link rel="stylesheet" href="../css/header.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="journal-container">
        <!-- Sidebar Navigation -->
      <?php include('includes/header.php'); ?>  

        <!-- Main Content -->
        <main class="main-content">
       

            <div class="journal-grid">
                <!-- New Entry Section -->
                <section class="new-entry-section">
                    <h2>New Journal Entry</h2>
                    <form id="journalForm" class="journal-form">
                        <div class="form-group">
                            <label for="entryTitle">Title</label>
                            <input type="text" id="entryTitle" name="title" placeholder="Give your entry a title" required>
                        </div>
                        <div class="form-group">
                            <label for="entryContent">Your Thoughts</label>
                            <textarea id="entryContent" name="content" rows="10" placeholder="Write your thoughts here..." required></textarea>
                        </div>
                        <div class="form-group mood-selector">
                            <label>How are you feeling today?</label>
                            <div class="mood-options">
                                <button type="button" class="mood-btn" data-mood="happy">😊</button>
                                <button type="button" class="mood-btn" data-mood="calm">😌</button>
                                <button type="button" class="mood-btn" data-mood="sad">😢</button>
                                <button type="button" class="mood-btn" data-mood="angry">😠</button>
                                <button type="button" class="mood-btn" data-mood="anxious">😰</button>
                            </div>
                        </div>
                        <div class="form-group tags-group">
                            <label>Tags</label>
                            <div class="tags-container">
                                <input type="text" id="tagInput" placeholder="Add tags (press Enter)">
                                <div class="selected-tags"></div>
                            </div>
                        </div>
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-save"></i>
                            Save Entry
                        </button>
                    </form>
                </section>

                <!-- Journal Entries Section -->
                <section class="entries-section">
                    <div class="entries-header">
                        <h2>Previous Entries</h2>
                        <div class="entries-filter">
                            <select id="filterMood">
                                <option value="">All Moods</option>
                                <option value="happy">Happy</option>
                                <option value="calm">Calm</option>
                                <option value="sad">Sad</option>
                                <option value="angry">Angry</option>
                                <option value="anxious">Anxious</option>
                            </select>
                            <input type="text" id="searchEntries" placeholder="Search entries...">
                        </div>
                    </div>
                    <div class="entries-list">
                        <!-- Sample Entry -->
                        <article class="journal-entry">
                            <div class="entry-header">
                                <h3>Morning Reflection</h3>
                                <span class="entry-date">March 15, 2024</span>
                            </div>
                            <div class="entry-mood">
                                <span class="mood-icon">😊</span>
                                <span class="mood-text">Happy</span>
                            </div>
                            <p class="entry-preview">Started the day with a peaceful meditation session. Feeling grateful for the beautiful weather...</p>
                            <div class="entry-tags">
                                <span class="tag">meditation</span>
                                <span class="tag">gratitude</span>
                            </div>
                            <div class="entry-actions">
                                <button class="action-btn view-btn">
                                    <i class="fas fa-eye"></i>
                                    View
                                </button>
                                <button class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </button>
                                <button class="action-btn delete-btn">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </div>
                        </article>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script src="../js/journal.js"></script>
</body>
</html> 