-- Create the Health Assessment Quiz
INSERT INTO `quizzes` (`title`, `description`, `created_at`) VALUES
('Health Assessment', 'A comprehensive assessment of your physical, mental, and lifestyle health.', NOW());

-- Get the quiz_id of the newly created quiz
SET @quiz_id = LAST_INSERT_ID();

-- Physical Health Questions
INSERT INTO `questions` (`quiz_id`, `question`, `points`, `created_at`) VALUES
(@quiz_id, 'How often do you engage in physical exercise?', 10, NOW()),
(@quiz_id, 'How many hours of sleep do you get on average?', 10, NOW()),
(@quiz_id, 'How many glasses of water do you drink daily?', 10, NOW()),
(@quiz_id, 'How often do you eat fruits and vegetables?', 10, NOW());

-- Mental Health Questions
INSERT INTO `questions` (`quiz_id`, `question`, `points`, `created_at`) VALUES
(@quiz_id, 'How often do you feel stressed or anxious?', 10, NOW()),
(@quiz_id, 'How often do you practice mindfulness or meditation?', 10, NOW()),
(@quiz_id, 'How would you rate your overall mood?', 10, NOW()),
(@quiz_id, 'How often do you take breaks during work?', 10, NOW());

-- Lifestyle Questions
INSERT INTO `questions` (`quiz_id`, `question`, `points`, `created_at`) VALUES
(@quiz_id, 'How would you rate your work-life balance?', 10, NOW()),
(@quiz_id, 'How often do you engage in hobbies or leisure activities?', 10, NOW()),
(@quiz_id, 'How often do you take vacations or time off?', 10, NOW()),
(@quiz_id, 'How often do you maintain social connections?', 10, NOW());

-- Get all question IDs
SET @q1 = (SELECT id FROM questions WHERE quiz_id = @quiz_id AND question = 'How often do you engage in physical exercise?');
SET @q2 = (SELECT id FROM questions WHERE quiz_id = @quiz_id AND question = 'How many hours of sleep do you get on average?');
SET @q3 = (SELECT id FROM questions WHERE quiz_id = @quiz_id AND question = 'How many glasses of water do you drink daily?');
SET @q4 = (SELECT id FROM questions WHERE quiz_id = @quiz_id AND question = 'How often do you eat fruits and vegetables?');
SET @q5 = (SELECT id FROM questions WHERE quiz_id = @quiz_id AND question = 'How often do you feel stressed or anxious?');
SET @q6 = (SELECT id FROM questions WHERE quiz_id = @quiz_id AND question = 'How often do you practice mindfulness or meditation?');
SET @q7 = (SELECT id FROM questions WHERE quiz_id = @quiz_id AND question = 'How would you rate your overall mood?');
SET @q8 = (SELECT id FROM questions WHERE quiz_id = @quiz_id AND question = 'How often do you take breaks during work?');
SET @q9 = (SELECT id FROM questions WHERE quiz_id = @quiz_id AND question = 'How would you rate your work-life balance?');
SET @q10 = (SELECT id FROM questions WHERE quiz_id = @quiz_id AND question = 'How often do you engage in hobbies or leisure activities?');
SET @q11 = (SELECT id FROM questions WHERE quiz_id = @quiz_id AND question = 'How often do you take vacations or time off?');
SET @q12 = (SELECT id FROM questions WHERE quiz_id = @quiz_id AND question = 'How often do you maintain social connections?');

-- Add answers for each question
-- Physical Health Questions
INSERT INTO `answers` (`question_id`, `answer`, `is_correct`, `created_at`) VALUES
(@q1, 'Daily', '1', NOW()),
(@q1, '3-4 times a week', '0', NOW()),
(@q1, '1-2 times a week', '0', NOW()),
(@q1, 'Rarely or never', '0', NOW()),

(@q2, '7-8 hours', '1', NOW()),
(@q2, '6 hours', '0', NOW()),
(@q2, '5 hours or less', '0', NOW()),
(@q2, 'More than 8 hours', '0', NOW()),

(@q3, '8 or more glasses', '1', NOW()),
(@q3, '6-7 glasses', '0', NOW()),
(@q3, '4-5 glasses', '0', NOW()),
(@q3, 'Less than 4 glasses', '0', NOW()),

(@q4, 'Daily', '1', NOW()),
(@q4, '4-6 times a week', '0', NOW()),
(@q4, '1-3 times a week', '0', NOW()),
(@q4, 'Rarely or never', '0', NOW());

-- Mental Health Questions
INSERT INTO `answers` (`question_id`, `answer`, `is_correct`, `created_at`) VALUES
(@q5, 'Rarely or never', '1', NOW()),
(@q5, 'Sometimes', '0', NOW()),
(@q5, 'Often', '0', NOW()),
(@q5, 'Very often', '0', NOW()),

(@q6, 'Daily', '1', NOW()),
(@q6, '3-4 times a week', '0', NOW()),
(@q6, '1-2 times a week', '0', NOW()),
(@q6, 'Rarely or never', '0', NOW()),

(@q7, 'Very good', '1', NOW()),
(@q7, 'Good', '0', NOW()),
(@q7, 'Fair', '0', NOW()),
(@q7, 'Poor', '0', NOW()),

(@q8, 'Every hour', '1', NOW()),
(@q8, 'Every 2 hours', '0', NOW()),
(@q8, 'Every 3-4 hours', '0', NOW()),
(@q8, 'Rarely or never', '0', NOW());

-- Lifestyle Questions
INSERT INTO `answers` (`question_id`, `answer`, `is_correct`, `created_at`) VALUES
(@q9, 'Excellent', '1', NOW()),
(@q9, 'Good', '0', NOW()),
(@q9, 'Fair', '0', NOW()),
(@q9, 'Poor', '0', NOW()),

(@q10, 'Daily', '1', NOW()),
(@q10, '3-4 times a week', '0', NOW()),
(@q10, '1-2 times a week', '0', NOW()),
(@q10, 'Rarely or never', '0', NOW()),

(@q11, 'Every 3-4 months', '1', NOW()),
(@q11, 'Every 6 months', '0', NOW()),
(@q11, 'Once a year', '0', NOW()),
(@q11, 'Rarely or never', '0', NOW()),

(@q12, 'Daily', '1', NOW()),
(@q12, '3-4 times a week', '0', NOW()),
(@q12, '1-2 times a week', '0', NOW()),
(@q12, 'Rarely or never', '0', NOW()); 