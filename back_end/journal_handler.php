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
        // Get all entries for the user
        try {
            $stmt = $pdo->prepare("
                SELECT * FROM journal_entries 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC
            ");
            $stmt->execute(['user_id' => $_SESSION['user_id']]);
            $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode($entries);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error retrieving entries']);
        }
        break;

    case 'POST':
        // Create new entry
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
        }

        try {
            $pdo->beginTransaction();

            // Insert journal entry
            $stmt = $pdo->prepare("
                INSERT INTO journal_entries (
                    user_id, title, content, mood, created_at
                ) VALUES (
                    :user_id, :title, :content, :mood, NOW()
                )
            ");

            $stmt->execute([
                'user_id' => $_SESSION['user_id'],
                'title' => $data['title'],
                'content' => $data['content'],
                'mood' => $data['mood']
            ]);

            $entryId = $pdo->lastInsertId();

            // Insert tags
            if (!empty($data['tags'])) {
                $stmt = $pdo->prepare("
                    INSERT INTO journal_tags (entry_id, tag_name)
                    VALUES (:entry_id, :tag_name)
                ");

                foreach ($data['tags'] as $tag) {
                    $stmt->execute([
                        'entry_id' => $entryId,
                        'tag_name' => $tag
                    ]);
                }
            }

            $pdo->commit();
            
            echo json_encode([
                'success' => true,
                'message' => 'Entry created successfully',
                'entry_id' => $entryId
            ]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error creating entry']);
        }
        break;

    case 'PUT':
        // Update existing entry
        $data = json_decode(file_get_contents('php://input'), true);
        $entryId = $_GET['id'] ?? null;

        if (!$entryId || !$data) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
        }

        try {
            $pdo->beginTransaction();

            // Update journal entry
            $stmt = $pdo->prepare("
                UPDATE journal_entries 
                SET title = :title, content = :content, mood = :mood, updated_at = NOW()
                WHERE id = :entry_id AND user_id = :user_id
            ");

            $stmt->execute([
                'title' => $data['title'],
                'content' => $data['content'],
                'mood' => $data['mood'],
                'entry_id' => $entryId,
                'user_id' => $_SESSION['user_id']
            ]);

            // Delete existing tags
            $stmt = $pdo->prepare("DELETE FROM journal_tags WHERE entry_id = :entry_id");
            $stmt->execute(['entry_id' => $entryId]);

            // Insert new tags
            if (!empty($data['tags'])) {
                $stmt = $pdo->prepare("
                    INSERT INTO journal_tags (entry_id, tag_name)
                    VALUES (:entry_id, :tag_name)
                ");

                foreach ($data['tags'] as $tag) {
                    $stmt->execute([
                        'entry_id' => $entryId,
                        'tag_name' => $tag
                    ]);
                }
            }

            $pdo->commit();
            
            echo json_encode([
                'success' => true,
                'message' => 'Entry updated successfully'
            ]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error updating entry']);
        }
        break;

    case 'DELETE':
        // Delete entry
        $entryId = $_GET['id'] ?? null;

        if (!$entryId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid entry ID']);
            exit;
        }

        try {
            $pdo->beginTransaction();

            // Delete tags first (due to foreign key constraint)
            $stmt = $pdo->prepare("DELETE FROM journal_tags WHERE entry_id = :entry_id");
            $stmt->execute(['entry_id' => $entryId]);

            // Delete journal entry
            $stmt = $pdo->prepare("
                DELETE FROM journal_entries 
                WHERE id = :entry_id AND user_id = :user_id
            ");
            
            $stmt->execute([
                'entry_id' => $entryId,
                'user_id' => $_SESSION['user_id']
            ]);

            $pdo->commit();
            
            echo json_encode([
                'success' => true,
                'message' => 'Entry deleted successfully'
            ]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error deleting entry']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
} 