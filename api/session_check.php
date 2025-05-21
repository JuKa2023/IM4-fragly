<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

session_start();
if (isset($_SESSION['ID'])) {
    http_response_code(200);
} else {
    http_response_code(401);
    session_destroy();
}