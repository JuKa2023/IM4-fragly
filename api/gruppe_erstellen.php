<?php
require_once('session_check.php');
require_once('db.php');

$user_id = $_SESSION['ID'];

$groupName = trim($_POST['name'] ?? '');
$loeschdatum = $_POST['loeschdatum'] ?? null;

if ($groupName === '') {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Gruppenname darf nicht leer sein.'
    ]);
    exit;
}

try {
    $pdo->beginTransaction();

    $check = $pdo->prepare("SELECT COUNT(*) FROM gruppe WHERE name = ?");
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

    $kuerzel = bin2hex(random_bytes(6));
    $erstellt = date('Y-m-d');
    $insertGroup = $pdo->prepare("
        INSERT INTO gruppe
            (name, kuerzel, ersteller, erstellt, loeschdatum)
        VALUES (?, ?, ?, ?, ?)
    ");
    $insertGroup->execute([
        $groupName, $kuerzel, $user_id, $erstellt,
        $loeschdatum ?: null
    ]);
    $gruppe_id = $pdo->lastInsertId();

    $insertMember = $pdo->prepare("
        INSERT INTO nutzer_gruppe
            (user_id, gruppe_id, erstellt)
        VALUES (?, ?, ?)
    ");
    $insertMember->execute([
        $user_id, $gruppe_id, date('Y-m-d')
    ]);

    $pdo->commit();

    echo json_encode([
        'success'     => true,
        'message'     => 'Gruppe wurde erfolgreich erstellt.',
        'kuerzel'     => $kuerzel,
        'link'        => "http://localhost:5173/{$kuerzel}",
        'group_name'  => $groupName
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