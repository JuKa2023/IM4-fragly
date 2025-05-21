<?php
require_once('session_check.php');
require_once('db.php');

// 1) auth check
if (!isset($_SESSION['ID'])) {
    http_response_code(401);
    echo json_encode([
      'success' => false,
      'message' => 'Nicht eingeloggt'
    ]);
    exit;
}
$user_id = $_SESSION['ID'];

// 2) read + validate input
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
    // 3) transaction
    $pdo->beginTransaction();

    // 4) uniqueness
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

    // 5) insert Gruppe
    $kuerzel      = bin2hex(random_bytes(6));
    $erstelldatum = date('Y-m-d');
    $insertGroup  = $pdo->prepare("
      INSERT INTO Gruppe
        (Gruppe_Name, Kuerzel, Erstellt_von_User_ID, Erstelldatum, Loeschdatum)
      VALUES (?, ?, ?, ?, ?)
    ");
    $insertGroup->execute([
      $groupName, $kuerzel, $user_id, $erstelldatum,
      $loeschdatum ?: null
    ]);
    $gruppe_id = $pdo->lastInsertId();

    // 6) insert into Nutzer_hat_Gruppe
    $insertMember = $pdo->prepare("
      INSERT INTO Nutzer_hat_Gruppe
        (user_id, gruppe_id, erstelldatum)
      VALUES (?, ?, ?)
    ");
    $insertMember->execute([
      $user_id, $gruppe_id, date('Y-m-d')
    ]);

    // 7) commit
    $pdo->commit();

    // 8) respond with JSON
    echo json_encode([
      'success'     => true,
      'message'     => 'Gruppe wurde erfolgreich erstellt.',
      'kuerzel'     => $kuerzel,
      // build your real link here or let the client do it:
      'link'        => "https://example.com/{$kuerzel}"
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