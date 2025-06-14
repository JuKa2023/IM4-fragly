<?php
require_once('db.php');
require_once('session_check.php');
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['ID'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Nicht eingeloggt']);
    exit;
}
$user_id = $_SESSION['ID'];

$input = json_decode(file_get_contents('php://input'), true);
$updates = $input['update'] ?? [];
$deletes = $input['delete'] ?? [];

try {
    $pdo->beginTransaction();

    if (!empty($deletes)) {
        $in = implode(',', array_fill(0, count($deletes), '?'));
        $stmt = $pdo->prepare("
      DELETE FROM nutzer_frage
      WHERE user_id = ?
        AND frage_id IN ($in)
    ");
        $stmt->execute(array_merge([$user_id], $deletes));
    }

    if (!empty($updates)) {
        $stmt = $pdo->prepare("
      INSERT INTO nutzer_frage (user_id, frage_id, antwort)
      VALUES (?, ?, ?)
      ON DUPLICATE KEY UPDATE antwort = VALUES(antwort)
    ");
        foreach ($updates as $u) {
            $stmt->execute([
                $user_id,
                $u['frage_id'],
                $u['antwort']
            ]);
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'DB-Fehler: ' . $e->getMessage()]);
}