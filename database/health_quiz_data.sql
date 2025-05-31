-- Insert health quiz
INSERT INTO quizzes (title, description) VALUES 
('Health Assessment', 'A comprehensive assessment of your physical, mental, and lifestyle health.');

-- Insert health quiz questions
INSERT INTO questions (quiz_id, question, points) VALUES
(1, 'How often do you engage in physical exercise?', 10),
(1, 'How many hours of sleep do you get on average?', 10),
(1, 'How many glasses of water do you drink daily?', 10),
(1, 'How often do you eat fruits and vegetables?', 10),
(1, 'How often do you feel stressed or anxious?', 10),
(1, 'How often do you practice mindfulness or meditation?', 10),
(1, 'How would you rate your overall mood?', 10),
(1, 'How often do you take breaks during work?', 10),
(1, 'How would you rate your work-life balance?', 10),
(1, 'How often do you engage in hobbies or leisure activities?', 10);

-- Insert options for each question
-- Question 1
INSERT INTO answers (question_id, answer, is_correct) VALUES
(1, 'Daily', 1),
(1, '3-4 times a week', 0),
(1, '1-2 times a week', 0),
(1, 'Rarely or never', 0);

-- Question 2
INSERT INTO answers (question_id, answer, is_correct) VALUES
(2, '7-8 hours', 1),
(2, '6 hours', 0),
(2, '5 hours or less', 0),
(2, 'More than 8 hours', 0);

-- Question 3
INSERT INTO answers (question_id, answer, is_correct) VALUES
(3, '8 or more glasses', 1),
(3, '6-7 glasses', 0),
(3, '4-5 glasses', 0),
(3, 'Less than 4 glasses', 0);

-- Question 4
INSERT INTO answers (question_id, answer, is_correct) VALUES
(4, 'Daily', 1),
(4, '4-6 times a week', 0),
(4, '1-3 times a week', 0),
(4, 'Rarely or never', 0);

-- Question 5
INSERT INTO answers (question_id, answer, is_correct) VALUES
(5, 'Rarely or never', 1),
(5, 'Sometimes', 0),
(5, 'Often', 0),
(5, 'Very often', 0);

-- Question 6
INSERT INTO answers (question_id, answer, is_correct) VALUES
(6, 'Daily', 1),
(6, '3-4 times a week', 0),
(6, '1-2 times a week', 0),
(6, 'Rarely or never', 0);

-- Question 7
INSERT INTO answers (question_id, answer, is_correct) VALUES
(7, 'Very good', 1),
(7, 'Good', 0),
(7, 'Fair', 0),
(7, 'Poor', 0);

-- Question 8
INSERT INTO answers (question_id, answer, is_correct) VALUES
(8, 'Every hour', 1),
(8, 'Every 2 hours', 0),
(8, 'Every 3-4 hours', 0),
(8, 'Rarely or never', 0);

-- Question 9
INSERT INTO answers (question_id, answer, is_correct) VALUES
(9, 'Excellent', 1),
(9, 'Good', 0),
(9, 'Fair', 0),
(9, 'Poor', 0);

-- Question 10
INSERT INTO answers (question_id, answer, is_correct) VALUES
(10, 'Daily', 1),
(10, '3-4 times a week', 0),
(10, '1-2 times a week', 0),
(10, 'Rarely or never', 0); 