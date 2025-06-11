<?php
require_once('db.php');
require_once('session_check.php');

$input = json_decode(file_get_contents('php://input'), true);
if (empty($input['gruppe_id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'gruppe_id fehlt']);
    exit;
}
$gruppeId = (int)$input['gruppe_id'];
$userId   = $_SESSION['ID'];

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

try {
    $stmt = $pdo->prepare("
        SELECT 
            Nutzer.User_ID, 
            Nutzer.Nutzer, 
            Nutzer.Profilbild_URL,
            g.Gruppe_Name,
            g.Kuerzel,
            g.Gruppe_Link AS Link
        FROM Nutzer
        JOIN Nutzer_hat_Gruppe nhg ON nhg.user_id = Nutzer.User_ID
        JOIN Gruppe g ON g.Gruppe_ID = nhg.gruppe_id
        WHERE g.Gruppe_ID = :gid
    ");
    $stmt->execute([':gid' => $gruppeId]);
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode($members);
} catch (PDOException $e) {
    error_log("Error in get_group_members.php: {$e->getMessage()}");
    http_response_code(500);
    echo json_encode('Datenbankfehler');
}