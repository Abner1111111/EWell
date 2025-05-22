-- Create exercises table
CREATE TABLE IF NOT EXISTS exercises (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    duration INT NOT NULL COMMENT 'Duration in minutes',
    intensity ENUM('low', 'medium', 'high') NOT NULL,
    calories INT NOT NULL,
    notes TEXT,
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create indexes for better performance
CREATE INDEX idx_exercises_user_id ON exercises(user_id);
CREATE INDEX idx_exercises_created_at ON exercises(created_at);
CREATE INDEX idx_exercises_type ON exercises(type); 