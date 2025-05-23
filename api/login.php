<?php
require_once('db.php');


header('Content-Type: text/plain; charset=UTF-8');

// Get POST data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Check if username is in database
$stmt = $pdo->prepare("SELECT * FROM Nutzer WHERE Nutzer = :username");
$stmt->execute([
    ':username' => $username
]);
$user = $stmt->fetch();

if ($user) {
    // Verify password
    if (password_verify($password, $user['PW'])) {
        // session starten
        session_start();
        $_SESSION['user'] = $user['Nutzer'];
        $_SESSION['ID'] = $user['User_ID'];

        echo "Login erfolgreich";


    } else {
        echo "Falsches Passwort";
    }
    } else {
        echo "Benutzername nicht gefunden";
}