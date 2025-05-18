<?php
require_once('db.php');
require_once('session_check.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Check if user is logged in
if (!isset($_SESSION['ID'])) {
    http_response_code(401);
    echo "Nicht eingeloggt";
    exit;
}

$user_id = $_SESSION['ID'];

// Get data from FormData
$groupName = trim($_POST['Gruppe_Name'] ?? '');
$loeschdatum = $_POST['Loeschdatum'] ?? null;

// Basic validation
if ($groupName === '') {
    http_response_code(400);
    echo "Gruppenname darf nicht leer sein.";
    exit;
}

try {
    // Check for uniqueness
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Gruppe WHERE Gruppe_Name = ?");
    $stmt->execute([$groupName]);

    if ($stmt->fetchColumn() > 0) {
        http_response_code(409); // Conflict
        echo "Gruppenname existiert bereits.";
        exit;
    }

    // Create group
    $kuerzel = bin2hex(random_bytes(6)); // Invite code
    $erstelldatum = date('Y-m-d');

    $insert = $pdo->prepare("
        INSERT INTO Gruppe (Gruppe_Name, Kuerzel, Erstellt_von_User_ID, Erstelldatum, Loeschdatum)
        VALUES (?, ?, ?, ?, ?)
    ");

    $insert->execute([
        $groupName,
        $kuerzel,
        $user_id,
        $erstelldatum,
        $loeschdatum ?: null
    ]);

    echo "Gruppe wurde erfolgreich erstellt.";
} catch (PDOException $e) {
    http_response_code(500);
    echo "Datenbankfehler: " . $e->getMessage();
}