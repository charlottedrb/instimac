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