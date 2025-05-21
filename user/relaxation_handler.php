<?php
session_start();
require_once '../config/database.php';

class RelaxationHandler {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Save mood entry
    public function saveMood($userId, $mood, $date) {
        $query = "INSERT INTO user_moods (user_id, mood, date) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$userId, $mood, $date]);
    }

    // Save journal entry
    public function saveJournalEntry($userId, $entry, $date) {
        $query = "INSERT INTO user_journal (user_id, entry, date) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$userId, $entry, $date]);
    }

    // Get mood history
    public function getMoodHistory($userId, $limit = 30) {
        $query = "SELECT mood, date FROM user_moods WHERE user_id = ? ORDER BY date DESC LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get journal entries
    public function getJournalEntries($userId, $limit = 10) {
        $query = "SELECT entry, date FROM user_journal WHERE user_id = ? ORDER BY date DESC LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Save meditation session
    public function saveMeditationSession($userId, $duration, $type) {
        $query = "INSERT INTO meditation_sessions (user_id, duration, type, date) VALUES (?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$userId, $duration, $type]);
    }

    // Get meditation history
    public function getMeditationHistory($userId, $limit = 10) {
        $query = "SELECT duration, type, date FROM meditation_sessions WHERE user_id = ? ORDER BY date DESC LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $handler = new RelaxationHandler($conn);
    $response = ['success' => false, 'message' => ''];

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'save_mood':
                if (isset($_POST['mood']) && isset($_POST['date'])) {
                    $success = $handler->saveMood($_SESSION['user_id'], $_POST['mood'], $_POST['date']);
                    $response['success'] = $success;
                    $response['message'] = $success ? 'Mood saved successfully' : 'Failed to save mood';
                }
                break;

            case 'save_journal':
                if (isset($_POST['entry']) && isset($_POST['date'])) {
                    $success = $handler->saveJournalEntry($_SESSION['user_id'], $_POST['entry'], $_POST['date']);
                    $response['success'] = $success;
                    $response['message'] = $success ? 'Journal entry saved successfully' : 'Failed to save journal entry';
                }
                break;

            case 'save_meditation':
                if (isset($_POST['duration']) && isset($_POST['type'])) {
                    $success = $handler->saveMeditationSession($_SESSION['user_id'], $_POST['duration'], $_POST['type']);
                    $response['success'] = $success;
                    $response['message'] = $success ? 'Meditation session saved successfully' : 'Failed to save meditation session';
                }
                break;

            case 'get_mood_history':
                $response['data'] = $handler->getMoodHistory($_SESSION['user_id']);
                $response['success'] = true;
                break;

            case 'get_journal_entries':
                $response['data'] = $handler->getJournalEntries($_SESSION['user_id']);
                $response['success'] = true;
                break;

            case 'get_meditation_history':
                $response['data'] = $handler->getMeditationHistory($_SESSION['user_id']);
                $response['success'] = true;
                break;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?> 