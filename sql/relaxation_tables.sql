-- Create table for user moods
CREATE TABLE IF NOT EXISTS user_moods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    mood VARCHAR(50) NOT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create table for user journal entries
CREATE TABLE IF NOT EXISTS user_journal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    entry TEXT NOT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create table for meditation sessions
CREATE TABLE IF NOT EXISTS meditation_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    duration INT NOT NULL, -- Duration in minutes
    type VARCHAR(50) NOT NULL, -- Type of meditation (e.g., 'focus', 'calm', 'deep')
    date DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create indexes for better performance
CREATE INDEX idx_user_moods_user_date ON user_moods(user_id, date);
CREATE INDEX idx_user_journal_user_date ON user_journal(user_id, date);
CREATE INDEX idx_meditation_sessions_user_date ON meditation_sessions(user_id, date); 