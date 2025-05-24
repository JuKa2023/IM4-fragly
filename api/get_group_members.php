<?php
require_once('db.php');
require_once('session_check.php');

// 1) Anmeldung prüfen
if (!isset($_SESSION['ID'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Nicht eingeloggt']);
    exit;
}

// 2) Input parsen
$input = json_decode(file_get_contents('php://input'), true);
if (empty($input['gruppe_id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'gruppe_id fehlt']);
    exit;
}
$gruppeId = (int)$input['gruppe_id'];
$userId   = $_SESSION['ID'];

// 3) Berechtigungs‐Check: ist der User in dieser Gruppe?
$stmt = $pdo->prepare("
    SELECT 1
    FROM Nutzer_hat_Gruppe
    WHERE user_id = :uid AND gruppe_id = :gid
");
$stmt->execute([':uid' => $userId, ':gid' => $gruppeId]);
if (!$stmt->fetchColumn()) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Kein Zugriff auf diese Gruppe']);
    exit;
}

// 4) Mitglieder abrufen
try {
    $stmt = $pdo->prepare("
        SELECT nhg.user_id,
               nhf.bezeichnung
        FROM Nutzer_hat_Gruppe nhg
        JOIN Nutzer_hat_Frage nhf
          ON nhg.user_id = nhf.user_id
         AND nhf.frage_id = 1
        WHERE nhg.gruppe_id = :gid
    ");
    $stmt->execute([':gid' => $gruppeId]);
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
      'status'  => 'success',
      'members' => $members
    ]);
} catch (PDOException $e) {
    error_log("Error in get_group_members.php: {$e->getMessage()}");
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Datenbankfehler']);
}