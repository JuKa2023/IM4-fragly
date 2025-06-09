<?php
require_once('session_check.php');

// User is logged in, return user data
$response = [
    "status" => "success",
    "user" => $_SESSION['user'],
    "user_id" => $_SESSION['ID']
];

http_response_code(200);
echo json_encode($response);