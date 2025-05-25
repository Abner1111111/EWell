-- Insert health quiz
INSERT INTO quizzes (title, description) VALUES 
('Health Knowledge Quiz', 'Test your knowledge about health, nutrition, and wellness');

-- Insert health quiz questions
INSERT INTO questions (quiz_id, question_text) VALUES
(2, 'What is the recommended daily water intake for adults?'),
(2, 'Which of these is NOT a benefit of regular exercise?'),
(2, 'What is the main function of protein in the body?'),
(2, 'Which vitamin is primarily obtained from sunlight?'),
(2, 'What is the recommended amount of sleep for adults per night?'),
(2, 'Which of these is a good source of omega-3 fatty acids?'),
(2, 'What is the main purpose of stretching before exercise?'),
(2, 'Which of these is NOT a sign of good mental health?'),
(2, 'What is the recommended daily intake of fruits and vegetables?'),
(2, 'Which of these is the best way to maintain a healthy weight?');

-- Insert options for each question
-- Question 1
INSERT INTO options (question_id, option_text, is_correct) VALUES
(3, '8 glasses (2 liters)', 1),
(3, '4 glasses (1 liter)', 0),
(3, '12 glasses (3 liters)', 0),
(3, '6 glasses (1.5 liters)', 0);

-- Question 2
INSERT INTO options (question_id, option_text, is_correct) VALUES
(4, 'Improved mood', 0),
(4, 'Better sleep', 0),
(4, 'Increased stress levels', 1),
(4, 'Stronger muscles', 0);

-- Question 3
INSERT INTO options (question_id, option_text, is_correct) VALUES
(5, 'Building and repairing tissues', 1),
(5, 'Providing energy', 0),
(5, 'Storing fat', 0),
(5, 'Regulating body temperature', 0);

-- Question 4
INSERT INTO options (question_id, option_text, is_correct) VALUES
(6, 'Vitamin D', 1),
(6, 'Vitamin C', 0),
(6, 'Vitamin B12', 0),
(6, 'Vitamin A', 0);

-- Question 5
INSERT INTO options (question_id, option_text, is_correct) VALUES
(7, '7-9 hours', 1),
(7, '4-6 hours', 0),
(7, '10-12 hours', 0),
(7, '5-7 hours', 0);

-- Question 6
INSERT INTO options (question_id, option_text, is_correct) VALUES
(8, 'Salmon', 1),
(8, 'Chicken', 0),
(8, 'Beef', 0),
(8, 'Pork', 0);

-- Question 7
INSERT INTO options (question_id, option_text, is_correct) VALUES
(9, 'Prevent injury and improve flexibility', 1),
(9, 'Build muscle mass', 0),
(9, 'Burn calories', 0),
(9, 'Increase heart rate', 0);

-- Question 8
INSERT INTO options (question_id, option_text, is_correct) VALUES
(10, 'Feeling overwhelmed by daily tasks', 1),
(10, 'Having good relationships', 0),
(10, 'Being able to cope with stress', 0),
(10, 'Maintaining a positive outlook', 0);

-- Question 9
INSERT INTO options (question_id, option_text, is_correct) VALUES
(11, '5 servings', 1),
(11, '2 servings', 0),
(11, '3 servings', 0),
(11, '4 servings', 0);

-- Question 10
INSERT INTO options (question_id, option_text, is_correct) VALUES
(12, 'Balanced diet and regular exercise', 1),
(12, 'Strict dieting', 0),
(12, 'Skipping meals', 0),
(12, 'Taking weight loss supplements', 0); 