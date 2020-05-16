<?php

require_once('autoloader.php');
require_once('src/Database/Database.php');
require_once('src/Publication/Publication.php');


// use Database\Database as Database;
// use Team\Team;
// use User\User;
//use Publication\Publication; 

$db = new Database();
$db->connect();

// $test = [];
// $test['DataBaseRequests'] = $db->init();

// $team = new Team($db);
// $test['Team'] = $team->init();

// $user = new User($db);
// var_dump($user);
// $test['User'] = $user->init();

$publication = new Publication($db);
$publication = $publication->init($db);
$publication->get(1);
var_dump($publication);

?>

<!DOCTYPE>
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