-- Insert Health Assessment Quiz
INSERT INTO quizzes (title, description, created_at) 
VALUES ('Health Assessment', 'Take this comprehensive quiz to evaluate your physical, mental, and lifestyle health. Your answers will help us provide personalized recommendations for your wellness journey.', NOW());

-- Get the quiz_id
SET @quiz_id = LAST_INSERT_ID();

-- Insert Questions
INSERT INTO questions (quiz_id, question) VALUES
(@quiz_id, 'How often do you exercise?'),
(@quiz_id, 'How many hours of sleep do you get on average?'),
(@quiz_id, 'How would you rate your stress levels?'),
(@quiz_id, 'How often do you eat fruits and vegetables?'),
(@quiz_id, 'How often do you feel anxious or worried?'),
(@quiz_id, 'How much water do you drink daily?'),
(@quiz_id, 'How often do you take breaks during work?'),
(@quiz_id, 'How would you rate your work-life balance?'),
(@quiz_id, 'How often do you practice mindfulness or meditation?'),
(@quiz_id, 'How often do you eat processed foods?'),
(@quiz_id, 'How often do you socialize with friends and family?'),
(@quiz_id, 'How often do you take time for hobbies?'),
(@quiz_id, 'How would you rate your energy levels?'),
(@quiz_id, 'How often do you feel overwhelmed?'),
(@quiz_id, 'How often do you take vacations or time off?');

-- Insert Answers for each question
-- Question 1: Exercise
INSERT INTO answers (question_id, answer, is_correct) VALUES
(LAST_INSERT_ID(), 'Never', 0),
(LAST_INSERT_ID(), '1-2 times per week', 0),
(LAST_INSERT_ID(), '3-4 times per week', 0),
(LAST_INSERT_ID(), '5 or more times per week', 1);

-- Question 2: Sleep
INSERT INTO answers (question_id, answer, is_correct) VALUES
(LAST_INSERT_ID(), 'Less than 6 hours', 0),
(LAST_INSERT_ID(), '6-7 hours', 0),
(LAST_INSERT_ID(), '7-8 hours', 1),
(LAST_INSERT_ID(), 'More than 8 hours', 0);

-- Question 3: Stress
INSERT INTO answers (question_id, answer, is_correct) VALUES
(LAST_INSERT_ID(), 'Very high', 0),
(LAST_INSERT_ID(), 'Moderate to high', 0),
(LAST_INSERT_ID(), 'Moderate', 0),
(LAST_INSERT_ID(), 'Low', 1);

-- Question 4: Fruits and Vegetables
INSERT INTO answers (question_id, answer, is_correct) VALUES
(LAST_INSERT_ID(), 'Rarely', 0),
(LAST_INSERT_ID(), '1-2 servings per day', 0),
(LAST_INSERT_ID(), '3-4 servings per day', 0),
(LAST_INSERT_ID(), '5 or more servings per day', 1);

-- Question 5: Anxiety
INSERT INTO answers (question_id, answer, is_correct) VALUES
(LAST_INSERT_ID(), 'Almost always', 0),
(LAST_INSERT_ID(), 'Often', 0),
(LAST_INSERT_ID(), 'Sometimes', 0),
(LAST_INSERT_ID(), 'Rarely', 1);

-- Question 6: Water Intake
INSERT INTO answers (question_id, answer, is_correct) VALUES
(LAST_INSERT_ID(), 'Less than 4 glasses', 0),
(LAST_INSERT_ID(), '4-6 glasses', 0),
(LAST_INSERT_ID(), '7-8 glasses', 1),
(LAST_INSERT_ID(), 'More than 8 glasses', 0);

-- Question 7: Work Breaks
INSERT INTO answers (question_id, answer, is_correct) VALUES
(LAST_INSERT_ID(), 'Never', 0),
(LAST_INSERT_ID(), 'Rarely', 0),
(LAST_INSERT_ID(), 'Sometimes', 0),
(LAST_INSERT_ID(), 'Regularly', 1);

-- Question 8: Work-Life Balance
INSERT INTO answers (question_id, answer, is_correct) VALUES
(LAST_INSERT_ID(), 'Poor', 0),
(LAST_INSERT_ID(), 'Fair', 0),
(LAST_INSERT_ID(), 'Good', 0),
(LAST_INSERT_ID(), 'Excellent', 1);

-- Question 9: Mindfulness
INSERT INTO answers (question_id, answer, is_correct) VALUES
(LAST_INSERT_ID(), 'Never', 0),
(LAST_INSERT_ID(), 'Rarely', 0),
(LAST_INSERT_ID(), 'Sometimes', 0),
(LAST_INSERT_ID(), 'Regularly', 1);

-- Question 10: Processed Foods
INSERT INTO answers (question_id, answer, is_correct) VALUES
(LAST_INSERT_ID(), 'Daily', 0),
(LAST_INSERT_ID(), '3-4 times per week', 0),
(LAST_INSERT_ID(), '1-2 times per week', 0),
(LAST_INSERT_ID(), 'Rarely', 1);

-- Question 11: Socialization
INSERT INTO answers (question_id, answer, is_correct) VALUES
(LAST_INSERT_ID(), 'Rarely', 0),
(LAST_INSERT_ID(), 'Monthly', 0),
(LAST_INSERT_ID(), 'Weekly', 0),
(LAST_INSERT_ID(), 'Daily', 1);

-- Question 12: Hobbies
INSERT INTO answers (question_id, answer, is_correct) VALUES
(LAST_INSERT_ID(), 'Never', 0),
(LAST_INSERT_ID(), 'Rarely', 0),
(LAST_INSERT_ID(), 'Sometimes', 0),
(LAST_INSERT_ID(), 'Regularly', 1);

-- Question 13: Energy Levels
INSERT INTO answers (question_id, answer, is_correct) VALUES
(LAST_INSERT_ID(), 'Very low', 0),
(LAST_INSERT_ID(), 'Low', 0),
(LAST_INSERT_ID(), 'Moderate', 0),
(LAST_INSERT_ID(), 'High', 1);

-- Question 14: Feeling Overwhelmed
INSERT INTO answers (question_id, answer, is_correct) VALUES
(LAST_INSERT_ID(), 'Almost always', 0),
(LAST_INSERT_ID(), 'Often', 0),
(LAST_INSERT_ID(), 'Sometimes', 0),
(LAST_INSERT_ID(), 'Rarely', 1);

-- Question 15: Vacations
INSERT INTO answers (question_id, answer, is_correct) VALUES
(LAST_INSERT_ID(), 'Never', 0),
(LAST_INSERT_ID(), 'Rarely', 0),
(LAST_INSERT_ID(), 'Sometimes', 0),
(LAST_INSERT_ID(), 'Regularly', 1); 