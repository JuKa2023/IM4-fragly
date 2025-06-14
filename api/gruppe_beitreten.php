<?php
require_once 'session_check.php';
require_once 'db.php';

$userId = $_SESSION['ID'] ?? 0;
$name = trim($_POST['name'] ?? '');
$code = trim($_POST['code'] ?? '');

if ($userId === 0) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt']);
    exit;
}
if ($name === '' && $code === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Name oder Code fehlt']);
    exit;
}

try {
    $pdo->beginTransaction();
    if ($code !== '') {
        $stmt = $pdo->prepare(
            'SELECT gruppe_id FROM gruppe WHERE kuerzel = :code LIMIT 1 FOR UPDATE');
        $stmt->execute(['code' => $code]);
    } else {
        $stmt = $pdo->prepare(
            'SELECT gruppe_id FROM gruppe WHERE name = :name LIMIT 1 FOR UPDATE');
        $stmt->execute(['name' => $name]);
    }

    $grp = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$grp) throw new RuntimeException('Gruppe nicht gefunden', 404);

    $gruppeId = (int)$grp['gruppe_id'];

    $check = $pdo->prepare(
        'SELECT 1 FROM nutzer_gruppe
     WHERE user_id = :uid AND gruppe_id = :gid
     LIMIT 1'
    );
    $check->execute(['uid' => $userId, 'gid' => $gruppeId]);
    $already = (bool)$check->fetchColumn();

    if (!$already) {
        $ins = $pdo->prepare(
            'INSERT INTO nutzer_gruppe (user_id, gruppe_id, erstellt)
       VALUES (:uid, :gid, NOW())'
        );
        $ins->execute(['uid' => $userId, 'gid' => $gruppeId]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'gruppeId' => $gruppeId, 'alreadyMember' => $already]);

} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}