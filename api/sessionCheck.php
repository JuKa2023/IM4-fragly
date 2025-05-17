<?php
// session_check.php
require_once 'session_helper.php';

header('Content-Type: application/json');
echo json_encode(getLoggedInUser());