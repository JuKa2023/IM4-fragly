<?php
header('Content-Type: application/json; charset=utf-8');

require_once('db.php');
require_once('session_check.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// 1) Authentifizierung
if (!isset($_SESSION['ID'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Nicht eingeloggt'
    ]);
    exit;
}
$user_id = $_SESSION['ID'];

// 2) Eingaben lesen und validieren
$groupName   = trim($_POST['Gruppe_Name'] ?? '');
$loeschdatum = $_POST['Loeschdatum'] ?? null;

if ($groupName === '') {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Gruppenname darf nicht leer sein.'
    ]);
    exit;
}

try {
    // 3) Transaction starten
    $pdo->beginTransaction();

    // 4) Eindeutigkeit prüfen
    $check = $pdo->prepare("SELECT COUNT(*) FROM Gruppe WHERE Gruppe_Name = ?");
    $check->execute([$groupName]);
    if ($check->fetchColumn() > 0) {
        $pdo->rollBack();
        http_response_code(409);
        echo json_encode([
            'success' => false,
            'message' => 'Gruppenname existiert bereits.'
        ]);
        exit;
    }

    // 5) Gruppe anlegen
    $kuerzel      = bin2hex(random_bytes(6));
    $erstelldatum = date('Y-m-d');
    $insertGroup  = $pdo->prepare("
        INSERT INTO Gruppe
            (Gruppe_Name, Kuerzel, Erstellt_von_User_ID, Erstelldatum, Loeschdatum)
        VALUES (?, ?, ?, ?, ?)
    ");
    $insertGroup->execute([
        $groupName,
        $kuerzel,
        $user_id,
        $erstelldatum,
        $loeschdatum ?: null
    ]);
    $gruppe_id = $pdo->lastInsertId();

    // 6) Ersteller in Nutzer_hat_Gruppe eintragen
    $insertMember = $pdo->prepare("
        INSERT INTO Nutzer_hat_Gruppe
            (user_id, gruppe_id, erstelldatum)
        VALUES (?, ?, ?)
    ");
    $insertMember->execute([
        $user_id,
        $gruppe_id,
        date('Y-m-d')
    ]);

    // 7) Transaction committen
    $pdo->commit();

    // 8) JSON-Antwort
    echo json_encode([
        'success' => true,
        'message' => 'Gruppe wurde erfolgreich erstellt.',
        'kuerzel' => $kuerzel,
        'link'    => "https://example.com/{$kuerzel}"
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Datenbankfehler: ' . $e->getMessage()
    ]);
}