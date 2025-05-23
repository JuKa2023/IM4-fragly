<?php
// api/save_answers.php

require_once('db.php');               // your PDO setup
require_once('session_check.php');    // starts session and checks login
header('Content-Type: application/json; charset=utf-8');

// 1) Sicherstellen, dass der User eingeloggt ist
if (!isset($_SESSION['ID'])) {
  http_response_code(401);
  echo json_encode(['error'=>'Nicht eingeloggt']);
  exit;
}
$user_id = $_SESSION['ID'];

// 2) Payload einlesen
$input = json_decode(file_get_contents('php://input'), true);
$updates = $input['update'] ?? [];
$deletes = $input['delete'] ?? [];

try {
  $pdo->beginTransaction();

  // 3) Alle Antworten löschen, die der User geleert hat
  if (!empty($deletes)) {
    // Platzhalter-Liste: "?, ?, ?" je nach Anzahl der deletes
    $in  = implode(',', array_fill(0, count($deletes), '?'));
    $stmt = $pdo->prepare("
      DELETE FROM Nutzer_hat_Frage
      WHERE user_id = ?
        AND frage_id IN ($in)
    ");
    $stmt->execute(array_merge([$user_id], $deletes));
  }

  // 4) Alle neuen bzw. geänderten Antworten upserten
  if (!empty($updates)) {
    $stmt = $pdo->prepare("
      INSERT INTO Nutzer_hat_Frage (user_id, frage_id, antwort)
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