<?php
// logout.php
session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Send a JSON success response
header('Content-Type: application/json');
echo json_encode(["status" => "success"]);
exit;
?>