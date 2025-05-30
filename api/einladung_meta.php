<?php
header('Content-Type: application/json; charset=utf-8');
require_once('db.php');


$code = $_GET['code'] ?? '';
if ($code === '') {
  http_response_code(400);
  echo json_encode(['message'=>'Code fehlt']);
  exit;
}

$stmt = $pdo->prepare("
  SELECT Gruppe_ID, Gruppe_Name
  FROM   Gruppe
  WHERE  Kuerzel = :code
  LIMIT  1
");
$stmt->execute(['code'=>$code]);
$invite = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$invite) {
  http_response_code(404);
  echo json_encode(['message'=>'Einladung ungültig oder abgelaufen']);
  exit;
}

echo json_encode([
  'gruppeId'   => (int)$invite['Gruppe_ID'],
  'familyName' => $invite['Gruppe_Name']
]);