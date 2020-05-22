<?php
include 'autoloader.php';

use Database\Test as Database;
use Team\Team;
use User\User;
<<<<<<< HEAD
=======
use Publication\Publication;

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

// ------------------ PROCESS INIT & TESTING -------------------------
>>>>>>> fix/class-groupe

$db = new Database();
$db->connect();

$publication = new Publication($db);
$publication = $publication->init($db);
$publication->get(1);

$test = [];
$test['DataBaseRequests'] = $db->init();

$user = new User($db);
$test['User'] = $user->init();

$publication = new Publication($db);
$test['Publication-Table'] = $publication->init();
$test['Publication-Test'] = $publication->test();

// --------------------------------------------------------------------


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