<?php
// api/get_questions.php
require_once('db.php');
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

session_start();
if (!isset($_SESSION['ID'])) {
  http_response_code(401);
  echo json_encode(['error'=>'Nicht eingeloggt']);
  exit;
}
$user_id = $_SESSION['ID'];

try {
  // left-join master questions with any existing user answers
  $sql = "
    SELECT
      f.frage_id,
      f.frage,
      f.antwort_vorschlag,
      uf.antwort
    FROM Frage AS f
    LEFT JOIN Nutzer_hat_Frage AS uf
      ON f.frage_id = uf.frage_id
     AND uf.user_id = ?
    ORDER BY f.reihenfolge
  ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$user_id]);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($rows);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error'=>'DB-Fehler: '.$e->getMessage()]);
}