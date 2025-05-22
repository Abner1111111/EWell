<?php
session_start();
require_once '../includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Check if summary is requested
        if (isset($_GET['summary']) && $_GET['summary'] === 'true') {
            try {
                // Get today's summary
                $stmt = $pdo->prepare("
                    SELECT 
                        SUM(calories) as total_calories,
                        SUM(duration) as total_duration,
                        COUNT(*) as exercise_count
                    FROM exercises 
                    WHERE user_id = :user_id 
                    AND DATE(created_at) = CURDATE()
                ");
                $stmt->execute(['user_id' => $_SESSION['user_id']]);
                $summary = $stmt->fetch(PDO::FETCH_ASSOC);
                
                echo json_encode([
                    'success' => true,
                    'calories' => $summary['total_calories'] ?? 0,
                    'duration' => $summary['total_duration'] ?? 0,
                    'count' => $summary['exercise_count'] ?? 0
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error retrieving summary']);
            }
        } else {
            // Get all exercises for the user
            try {
                $stmt = $pdo->prepare("
                    SELECT * FROM exercises 
                    WHERE user_id = :user_id 
                    ORDER BY created_at DESC
                ");
                $stmt->execute(['user_id' => $_SESSION['user_id']]);
                $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode($exercises);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error retrieving exercises']);
            }
        }
        break;

    case 'POST':
        // Create new exercise
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("
                INSERT INTO exercises (
                    user_id, type, duration, intensity, calories, notes, created_at
                ) VALUES (
                    :user_id, :type, :duration, :intensity, :calories, :notes, NOW()
                )
            ");

            $stmt->execute([
                'user_id' => $_SESSION['user_id'],
                'type' => $data['type'],
                'duration' => $data['duration'],
                'intensity' => $data['intensity'],
                'calories' => $data['calories'],
                'notes' => $data['notes']
            ]);

            $exerciseId = $pdo->lastInsertId();
            
            echo json_encode([
                'success' => true,
                'message' => 'Exercise logged successfully',
                'exercise_id' => $exerciseId
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error logging exercise']);
        }
        break;

    case 'PUT':
        // Update existing exercise
        $data = json_decode(file_get_contents('php://input'), true);
        $exerciseId = $_GET['id'] ?? null;

        if (!$exerciseId || !$data) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("
                UPDATE exercises 
                SET type = :type, 
                    duration = :duration, 
                    intensity = :intensity, 
                    calories = :calories, 
                    notes = :notes, 
                    updated_at = NOW()
                WHERE id = :exercise_id AND user_id = :user_id
            ");

            $stmt->execute([
                'type' => $data['type'],
                'duration' => $data['duration'],
                'intensity' => $data['intensity'],
                'calories' => $data['calories'],
                'notes' => $data['notes'],
                'exercise_id' => $exerciseId,
                'user_id' => $_SESSION['user_id']
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Exercise updated successfully'
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error updating exercise']);
        }
        break;

    case 'DELETE':
        // Delete exercise
        $exerciseId = $_GET['id'] ?? null;

        if (!$exerciseId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid exercise ID']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("
                DELETE FROM exercises 
                WHERE id = :exercise_id AND user_id = :user_id
            ");
            
            $stmt->execute([
                'exercise_id' => $exerciseId,
                'user_id' => $_SESSION['user_id']
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Exercise deleted successfully'
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error deleting exercise']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
} 