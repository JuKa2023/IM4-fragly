<?php
session_start();
if (isset($_SESSION['user_id'])) {
    http_response_code(200);
} else {
    http_response_code(401);
    session_destroy();
}