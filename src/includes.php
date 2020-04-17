<?php

require 'autoloader.php';

use DataBase\DataBase as Database;
use Exemple\Exemple;

//Démarre une nouvelle session
session_start();

$speaker = new Speaker();
$db = new DataBase();
$db->connect();

if (!empty($_SESSION['user']) )  $_SESSION['user']->database = &$db;
