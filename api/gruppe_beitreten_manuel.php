<?php
require_once 'session_check.php';
require_once 'db.php';

header('Content-Type: application/json; charset=utf-8');

$userId = $_SESSION['ID'] ?? 0;
$name   = trim($_POST['name'] ?? '');
$code   = trim($_POST['code'] ?? '');

if ($userId === 0) {
  http_response_code(401);
  echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt']);
  exit;
}

if ($name === '' || $code === '') {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Name und Kürzel fehlen']);
  exit;
}

try {
  $pdo->beginTransaction();

  $grpStmt = $pdo->prepare("
      SELECT Gruppe_ID
      FROM   Gruppe
      WHERE  Gruppe_Name = :name 
        AND  Kuerzel = :code
      LIMIT 1
      FOR UPDATE
  ");
  $grpStmt->execute(['name' => $name, 'code' => $code]);
  $grp = $grpStmt->fetch(PDO::FETCH_ASSOC);

  if (!$grp) {
    throw new RuntimeException('Gruppe nicht gefunden', 404);
  }

  $gruppeId = (int)$grp['Gruppe_ID'];

  $alreadyStmt = $pdo->prepare("
      SELECT 1
      FROM   Nutzer_hat_Gruppe
      WHERE  user_id = :uid
        AND  gruppe_id = :gid
      LIMIT 1
  ");
  $alreadyStmt->execute(['uid' => $userId, 'gid' => $gruppeId]);
  $alreadyMember = (bool)$alreadyStmt->fetchColumn();

  if (!$alreadyMember) {
    $insertStmt = $pdo->prepare("
        INSERT INTO Nutzer_hat_Gruppe (user_id, gruppe_id, erstelldatum)
        VALUES (:uid, :gid, CURDATE())
    ");
    $insertStmt->execute(['uid' => $userId, 'gid' => $gruppeId]);
  }

  $pdo->commit();

  echo json_encode([
    'success'       => true,
    'gruppeId'      => $gruppeId,
    'alreadyMember' => $alreadyMember
  ]);

} catch (Throwable $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }

  $code = (int)$e->getCode();
  http_response_code(($code >= 100 && $code <= 599) ? $code : 500);

  error_log("Fehler: " . $e->getMessage());

  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
}