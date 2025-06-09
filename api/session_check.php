<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

session_start();

if (isset($_SESSION['user']) && isset($_SESSION['ID'])) {
    // User is logged in, continue
} else {
    // User is not logged in, destroy session and return error
    session_destroy();
    $response = [
        "status" => "user not logged in"
    ];
    http_response_code(401); // Unauthorized
    die(json_encode($response));
}
