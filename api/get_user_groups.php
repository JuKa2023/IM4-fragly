<?php
require_once('db.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");

session_start();

// 🚫 Not logged in
if (!isset($_SESSION['ID'])) {
    http_response_code(401); // Unauthorized
    echo json_encode([
        "status" => "error",
        "message" => "Nicht eingeloggt"
    ]);
    exit;
}

$userId = $_SESSION['ID'];

// Function 1: Get gruppe_ids
function getGroupIdsForUser($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT gruppe_id
        FROM Nutzer_hat_Gruppe
        WHERE user_id = :user_id
    ");
    $stmt->execute([':user_id' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Function 2: Get group details based on gruppe_ids
function getGroupDetailsForIds($pdo, $gruppeIds) {
    if (empty($gruppeIds)) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($gruppeIds), '?'));

    $stmt = $pdo->prepare("
        SELECT Gruppe_ID, Gruppe_Name, Kürzel, Erstelltdatum, Erstellt_von_User_ID
        FROM Gruppe
        WHERE Gruppe_ID IN ($placeholders)
    ");
    $stmt->execute($gruppeIds);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ✅ Main logic
try {
    $gruppeIds = getGroupIdsForUser($pdo, $userId);

    // 🔐 User is not in any groups
    if (empty($gruppeIds)) {
        http_response_code(204); // No Content
        echo json_encode([
            "status" => "empty",
            "message" => "Du bist in keiner Gruppe."
        ]);
        exit;
    }

    $groups = getGroupDetailsForIds($pdo, $gruppeIds);

    http_response_code(200); // OK
    echo json_encode([
        "status" => "success",
        "groups" => $groups
    ]);
} catch (PDOException $e) {
    http_response_code(500); // Server error
    echo json_encode([
        "status" => "error",
        "message" => "Datenbankfehler: " . $e->getMessage()
    ]);
}