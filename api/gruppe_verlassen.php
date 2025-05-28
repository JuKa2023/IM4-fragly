<?php
require_once('session_check.php');
require_once('db.php');

$user_id = $_SESSION['ID'];

$body = json_decode(file_get_contents('php://input'), true);
$gruppe_id = $body['gruppe_id'];

try {
    $stmt = $pdo->prepare("DELETE FROM Nutzer_hat_Gruppe WHERE user_id = ? AND gruppe_id = ? LIMIT 1");
    $stmt->execute([$user_id, $gruppe_id]);

    http_response_code(200);
    echo json_encode([
      'success' => true,
      'message' => 'Gruppe verlassen'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
      'success' => false,
      'message' => 'Fehler beim Verlassen der Gruppe'
    ]);
    exit;
}