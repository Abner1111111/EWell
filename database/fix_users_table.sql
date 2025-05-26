-- Fix users table structure
ALTER TABLE `users` 
    CHANGE `Email` `email` varchar(100) NOT NULL,
    MODIFY `password` varchar(255) NOT NULL;

-- Create a test admin user with proper password hash
INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `role`) VALUES
('Admin', 'User', 'admin@ewell.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin');

-- Create a test regular user with proper password hash
INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `role`) VALUES
('Test', 'User', 'test@ewell.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'User'); 