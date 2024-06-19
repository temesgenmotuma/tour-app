<?php
session_start();

function checkUserRole($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != $role) {
        http_response_code(403);
        echo "Access denied!";
        exit();
    }
}
