<?php
require_once('db.php');
require_once('session_check.php');

// JSON-Eingabe einlesen
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
$userId = $_SESSION['ID'];

// Felder auslesens
$username = trim($data['Benutzername'] ?? '');
$email = trim($data['email'] ?? '');
$newPassword = trim($data['newPassword'] ?? '');

// Validierung
if ($username === '' || $email === '') {
    echo json_encode(['success' => false, 'error' => 'Benutzername und E-Mail dürfen nicht leer sein.']);
    exit;
}
if ($newPassword !== '' && strlen($newPassword) < 8) {
    echo json_encode(['success' => false, 'error' => 'Neues Passwort muss mindestens 8 Zeichen lang sein.']);
    exit;
}

// Dubletten-Check
$stmt = $pdo->prepare("
    SELECT user_id
    FROM nutzer
    WHERE (nutzer = :user OR email = :email)
      AND user_id <> :me
    LIMIT 1
");
$stmt->execute([
    ':user' => $username,
    ':email' => $email,
    ':me' => $userId
]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'error' => 'Benutzername oder E-Mail bereits vergeben.']);
    exit;
}

// Optionales Passwort-Update vorbereiten
$passClause = '';
$params = [
    ':user' => $username,
    ':email' => $email,
    ':me' => $userId,
];
if ($newPassword !== '') {
    $params[':pass'] = password_hash($newPassword, PASSWORD_DEFAULT);
    $passClause = ", password = :pass";
}

// Update ausführen
$sql = "
    UPDATE nutzer
    SET nutzer = :user,
        email  = :email
        {$passClause}
    WHERE user_id = :me
";
$upd = $pdo->prepare($sql);
$ok = $upd->execute($params);

if ($ok) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Speichern fehlgeschlagen.']);
}