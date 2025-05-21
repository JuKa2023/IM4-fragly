<?php
// api/save_answers.php
require_once __DIR__.'/db.php';
require_once __DIR__.'/session_check.php';
header('Content-Type: application/json; charset=utf-8');

session_start();
if (!isset($_SESSION['ID'])) {
  http_response_code(401);
  echo json_encode(['success'=>false,'error'=>'Nicht eingeloggt']);
  exit;
}
$user_id = $_SESSION['ID'];

// Expect payload as JSON: { answers: { [frage_id]: antwort, … } }
$input = json_decode(file_get_contents('php://input'), true);
$answers = $input['answers'] ?? [];

try {
  $pdo->beginTransaction();
  $upsert = $pdo->prepare("
    INSERT INTO Nutzer_hat_Frage (frage_id, user_id, antwort, erstelltdatum)
    VALUES (?, ?, ?, CURRENT_DATE())
    ON DUPLICATE KEY UPDATE
      antwort = VALUES(antwort),
      erstelltdatum = CURRENT_DATE()
  ");

  foreach ($answers as $fid => $text) {
    // skip empty
    if (trim($text)==='') continue;
    $upsert->execute([(int)$fid, $user_id, $text]);
  }

  $pdo->commit();
  echo json_encode(['success'=>true]);
} catch (PDOException $e) {
  $pdo->rollBack();
  http_response_code(500);
  echo json_encode(['success'=>false,'error'=>$e->getMessage()]);
}