<?php

include 'autoloader.php';

function displayResults(&$tests)
{
    foreach ($tests as $key => $value) {

        $string = '<tr>';
        $string .= '<td>' . $key . '</td><td>';
        if ($value === TRUE) {
            $string .= '<span style="color: limegreen;">OK</span>';
        } else {
            $string .= '<span style="color: darkred;">ERROR</span>';
        }
        $string .= '</td></tr>';
        echo $string;
    }
}

use Database\Test as Database;
use Team\Team;
use User\User;

$db = new Database();
$db->connect();

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
