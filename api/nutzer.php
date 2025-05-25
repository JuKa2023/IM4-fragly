<?php
require_once('db.php');
require_once('session_check.php');

error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

$this_user_id = $_SESSION['ID'];

try {
    $stmt = $pdo->prepare('SELECT * FROM Nutzer WHERE User_ID = :uid LIMIT 1');
    $stmt->execute([':uid' => $this_user_id]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        throw new RuntimeException('User not found.');
    }

    echo json_encode($user);
} catch (RuntimeException $e) {
    echo json_encode($e->getMessage());
} catch (Exception $e) {
    echo json_encode('Unexpected server error.');
}

