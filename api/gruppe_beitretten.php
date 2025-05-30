<?php
header('Content-Type: application/json; charset=utf-8');

require_once('session_check.php');  // ⬅ verifies the user is logged-in
require_once('db.php');

$userId = $_SESSION['ID'];          // comes from session_check.php
$code   = $_POST['code'] ?? '';

if ($code === '') {
  http_response_code(400);
  echo json_encode(['success'=>false,'message'=>'Code fehlt']);
  exit;
}

try {
  $pdo->beginTransaction();

  // ── 1  lock the target group row
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

  // ── 2  add the user to the group (IGNORE = no error if already a member)
  $pdo->prepare("
    INSERT IGNORE INTO Nutzer_hat_Gruppe (user_id, gruppe_id, erstelldatum)
    VALUES (:uid, :gid, CURDATE())
  ")->execute(['uid'=>$userId, 'gid'=>$grp['Gruppe_ID']]);

  $pdo->commit();

  echo json_encode([
    'success'   => true,
    'gruppeId'  => (int)$grp['Gruppe_ID']
  ]);

} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  http_response_code($e->getCode() ?: 500);
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
}