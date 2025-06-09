<?php
require_once('db.php');
require_once('session_check.php');

error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');


$input = json_decode(file_get_contents('php://input'), true);

if ($input === null || !isset($input['user_id'])) {
    $this_user_id = $_SESSION['ID'];
} else {
    $this_user_id = $input['user_id'];
}

try {
    $stmt = $pdo->prepare('SELECT User_ID, Nutzer, Profilbild_URL, Email FROM Nutzer WHERE User_ID = :uid LIMIT 1');
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

