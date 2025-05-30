<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ../main/login.php');
    exit;
}
?>