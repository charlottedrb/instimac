<?php
include 'autoloader.php';

use Database\Test as Database;
use Team\Team;
use User\User;

$db = new Database();
$db->connect();

$publication = new Publication($db);
$publication = $publication->init($db);
$publication->get(1);

$test = [];
$test['DataBaseRequests'] = $db->init();

$team = new Team($db);
$test['Team'] = $team->init();

$user = new User($db);
var_dump($user);
$test['User'] = $user->init();


?><!DOCTYPE>
<html>
<head>
	<meta charset="UTF-8">
</head>
<body>

<h1>Initialisation</h1>
<table>
	<tr>
		<td>Classe</td>
		<td>Résultat</td>
	</tr>
    <?php displayResults($test); ?>
</table>
</body>
</html>