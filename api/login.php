<?php
require_once('db.php');


header('Content-Type: text/plain; charset=UTF-8');

// Get POST data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Check if username is in database
$stmt = $pdo->prepare("SELECT * FROM nutzer WHERE nutzer = :username");
$stmt->execute([
    ':username' => $username
]);
$user = $stmt->fetch();

if ($user) {
    // Verify password
    if (password_verify($password, $user['password'])) {
        // session starten
        session_start();
        $_SESSION['user'] = $user['nutzer'];
        $_SESSION['ID'] = $user['user_id'];

        echo "Login erfolgreich";


    } else {
        echo "Falsches Passwort";
    }
} else {
    echo "Benutzername nicht gefunden";
}