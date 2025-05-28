<?php
require_once('db.php');
require_once('session_check.php');

$userId = $_SESSION['ID'];

// Function 1: Get gruppe_ids
function getGroupIdsForUser($pdo, $userId) {
    try {
        $stmt = $pdo->prepare("
            SELECT gruppe_id
            FROM Nutzer_hat_Gruppe
            WHERE user_id = :user_id
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        error_log("Error in getGroupIdsForUser: " . $e->getMessage());
        throw $e;
    }
}

// Function 2: Get group details based on gruppe_ids
function getGroupDetailsForIds($pdo, $gruppeIds) {
    if (empty($gruppeIds)) {
        return [];
    }

    try {
        $placeholders = implode(',', array_fill(0, count($gruppeIds), '?'));

        $stmt = $pdo->prepare("
            SELECT Gruppe_ID, Gruppe_Name, Kuerzel, Erstellt_von_User_ID
            FROM Gruppe
            WHERE Gruppe_ID IN ($placeholders)
        ");
        $stmt->execute($gruppeIds);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getGroupDetailsForIds: " . $e->getMessage());
        throw $e;
    }
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
    error_log("Database error in get_user_groups.php: " . $e->getMessage());
    http_response_code(500); // Server error
    echo json_encode([
        "status" => "error",
        "message" => "Datenbankfehler: " . $e->getMessage()
    ]);
}