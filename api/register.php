<?php
require_once('db.php');
require_once('session_check.php');


header('Content-Type: text/plain; charset=UTF-8');

// Get POST data
$username = $_POST['username'] ?? '';
$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
if (empty($username) || empty($email) || empty($password)) {
    echo "Bitte fülle alle Felder aus";
    exit;
}

if (strlen($password) < 8) {
    echo "Passwort muss mindestens 8 Zeichen lang sein";
    exit;
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Check if user already exists
$stmt = $pdo->prepare("SELECT * FROM Nutzer WHERE Email = :email OR Nutzer = :username");
$stmt->execute([
    ':email' => $email,
    ':username' => $username
]);
$user = $stmt->fetch();

if ($user) {
    echo "Username oder E-Mail bereits vergeben";
    exit;
} else {
    // Insert new user
    $insert = $pdo->prepare("INSERT INTO Nutzer (Nutzer, Email, PW, Erstelldatum) VALUES (:user, :email, :pass, CURDATE())");
    $success = $insert->execute([
        ':user'  => $username,
        ':email' => $email,
        ':pass'  => $hashedPassword
    ]);

    if ($success) {
        echo "Registrierung erfolgreich";
    } else {
        echo "Registrierung fehlgeschlagen";
    }
}