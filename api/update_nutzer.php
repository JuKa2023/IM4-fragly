<?php
require_once('db.php');
require_once('session_check.php');

// JSON-Eingabe einlesen
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

$userId = $_SESSION['ID'];

// Felder auslesen
$username    = trim($data['Benutzername']   ?? '');
$email       = trim($data['Email']          ?? '');
$newPassword = trim($data['newPassword']    ?? '');

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
    SELECT User_ID
    FROM Nutzer
    WHERE (Nutzer = :user OR Email = :email)
      AND User_ID <> :me
    LIMIT 1
");
$stmt->execute([
    ':user'  => $username,
    ':email' => $email,
    ':me'    => $userId
]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'error' => 'Benutzername oder E-Mail bereits vergeben.']);
    exit;
}

// Optionales Passwort-Update vorbereiten
$passClause = '';
$params = [
    ':user'  => $username,
    ':email' => $email,
    ':me'    => $userId,
];
if ($newPassword !== '') {
    $params[':pass'] = password_hash($newPassword, PASSWORD_DEFAULT);
    $passClause = ", PW = :pass";
}

// Update ausführen
$sql = "
    UPDATE Nutzer
    SET Nutzer = :user,
        Email  = :email
        {$passClause}
    WHERE User_ID = :me
";
$upd = $pdo->prepare($sql);
$ok  = $upd->execute($params);

if ($ok) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Speichern fehlgeschlagen.']);
}