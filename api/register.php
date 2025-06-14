<?php
require_once('db.php');

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($email) || empty($password)) {
    echo "Bitte fülle alle Felder aus";
    exit;
}

if (strlen($password) < 8) {
    echo "Passwort muss mindestens 8 Zeichen lang sein";
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("SELECT * FROM nutzer WHERE email = :email OR nutzer = :username");
$stmt->execute([
    ':email' => $email,
    ':username' => $username
]);
$user = $stmt->fetch();

if ($user) {
    echo "Username oder E-Mail bereits vergeben";
    exit;
} else {
    $insert = $pdo->prepare("INSERT INTO nutzer (nutzer, email, password, erstellt) VALUES (:user, :email, :pass, CURDATE())");
    $success = $insert->execute([
        ':user' => $username,
        ':email' => $email,
        ':pass' => $hashedPassword
    ]);

    if ($success) {
        echo "Registrierung erfolgreich";
    } else {
        echo "Registrierung fehlgeschlagen";
    }
}