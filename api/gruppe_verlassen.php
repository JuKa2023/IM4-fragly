<?php
require_once('session_check.php');
require_once('db.php');

$user_id = $_SESSION['ID'];
$body = json_decode(file_get_contents('php://input'), true);
$gruppe_id = $body['gruppe_id'];

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM nutzer_gruppe
        WHERE gruppe_id = ? AND user_id != ?
    ");
    $stmt->execute([$gruppe_id, $user_id]);
    $remainingMembers = (int)$stmt->fetchColumn();

    $stmt = $pdo->prepare("
        DELETE FROM nutzer_gruppe
        WHERE user_id = ? AND gruppe_id = ? 
        LIMIT 1
    ");
    $stmt->execute([$user_id, $gruppe_id]);

    if ($remainingMembers === 0) {
        $stmt = $pdo->prepare("DELETE FROM gruppe WHERE gruppe_id = ? LIMIT 1");
        $stmt->execute([$gruppe_id]);
    }

    $pdo->commit();

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => $remainingMembers === 0 
            ? 'Gruppe verlassen und gelöscht (war letztes Mitglied)' 
            : 'Gruppe verlassen'
    ]);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Fehler beim Verlassen der Gruppe'
    ]);
}