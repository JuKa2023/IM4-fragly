<?php
// api/save_answers.php
require_once('db.php');
require_once('session_check.php');
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['ID'])) {
  http_response_code(401);
  echo json_encode(['error'=>'Nicht eingeloggt']);
  exit;
}
$user_id = $_SESSION['ID'];

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['answers']) || !is_array($input['answers'])) {
  http_response_code(400);
  echo json_encode(['error'=>'Ungültige Nutzlast']);
  exit;
}

try {
  $pdo->beginTransaction();
  $upsert = $pdo->prepare("
    INSERT INTO Nutzer_hat_Frage (user_id, frage_id, antwort)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE antwort = VALUES(antwort)
  ");
  foreach ($input['answers'] as $a) {
    $upsert->execute([
      $user_id,
      $a['frage_id'],
      $a['antwort']
    ]);
  }
  $pdo->commit();
  echo json_encode(['success'=>true]);
} catch (PDOException $e) {
  $pdo->rollBack();
  http_response_code(500);
  echo json_encode(['error'=>'DB-Fehler: '.$e->getMessage()]);
}