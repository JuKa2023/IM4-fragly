<?php
session_start();

function getLoggedInUser() {
    if (isset($_SESSION['user_id'])) {
        return [
            "status" => "success",
            "user_id" => $_SESSION['user_id'],
            "email" => $_SESSION['email'],
            "username" => $_SESSION['username']
        ];
    } else {
        session_destroy();
        return [
            "status" => "error"
        ];
    }
}