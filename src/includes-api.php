<?php

require 'autoloader.php';

use Database\Database;
use User\Speaker;

if (session_status() == PHP_SESSION_NONE) session_start();

$speaker = new Speaker();
$db = new DataBase();
$db->connect();

if (!empty($_SESSION['user'])) {
    $_SESSION['user']->database = &$db;
}

if (empty($_SESSION['session']) || $_SESSION['session']->check() == FALSE || $_SESSION['user']->isGranted(['ADMIN', 'MODERATOR', 'USER']) == FALSE) {

    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(401);
    echo json_encode('Please login to use our services');
    exit;
}

