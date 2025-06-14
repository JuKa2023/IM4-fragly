<?php
require_once 'session_check.php';
require_once 'db.php';

$code = trim($_GET['code'] ?? '');
if ($code === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Code fehlt']);
    exit;
}

$stmt = $pdo->prepare('SELECT name FROM gruppe WHERE kuerzel = :code LIMIT 1');
$stmt->execute(['code' => $code]);
$name = $stmt->fetchColumn();

if (!$name) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Gruppe nicht gefunden']);
} else {
    echo json_encode(['success' => true, 'name' => $name]);
}