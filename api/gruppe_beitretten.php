<?php
header('Content-Type: application/json; charset=utf-8');

require_once('session_check.php'); 
require_once('db.php');

$userId = $_SESSION['ID'];
$code   = $_POST['code'] ?? '';

if ($code === '') {
  http_response_code(400);
  echo json_encode(['success'=>false,'message'=>'Code fehlt']);
  exit;
}

try {
  $pdo->beginTransaction();

  $q = $pdo->prepare("
    SELECT Gruppe_ID
    FROM   Gruppe
    WHERE  Kuerzel = :code
    FOR UPDATE
  ");
  $q->execute(['code'=>$code]);
  $grp = $q->fetch(PDO::FETCH_ASSOC);

  if (!$grp) {
    throw new RuntimeException('Einladung ungültig oder abgelaufen', 410);
  }

  $gruppeId = (int)$grp['Gruppe_ID'];

  $alreadyStmt = $pdo->prepare("
      SELECT 1
      FROM   Nutzer_hat_Gruppe
      WHERE  user_id  = :uid
        AND  gruppe_id = :gid
      LIMIT 1
  ");
  $alreadyStmt->execute(['uid'=>$userId, 'gid'=>$gruppeId]);
  $alreadyMember = (bool)$alreadyStmt->fetchColumn();

  if (!$alreadyMember) {
    $insert = $pdo->prepare("
        INSERT INTO Nutzer_hat_Gruppe (user_id, gruppe_id, erstelldatum)
        VALUES (:uid, :gid, CURDATE())
    ");
    $insert->execute(['uid'=>$userId, 'gid'=>$gruppeId]);
}

  $pdo->commit();

  echo json_encode([
    'success'       => true,
    'gruppeId'      => $gruppeId,
    'alreadyMember' => $alreadyMember
  ]);

} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  http_response_code($e->getCode() ?: 500);
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
}