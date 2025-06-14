<?php
require_once('db.php');
require_once('session_check.php');

error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

$this_user_id = $_SESSION['ID'];

$input = json_decode(file_get_contents('php://input'), true);

$user_id = null;
if (empty($input['user_id'])) {
    $user_id = $this_user_id;
} else {
    $user_id = (int)$input['user_id'];
}

try {
    $sql = "
  SELECT
    f.frage_id,
    f.frage,
    f.antwort_vorschlag,
    f.input_type,
    uf.antwort
  FROM frage AS f
  LEFT JOIN nutzer_frage AS uf
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
    echo json_encode('DB-Fehler');
}