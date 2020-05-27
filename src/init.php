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
use User\User;
use Publication\Publication;
use File\File;

// ------------------ PROCESS INIT & TESTING -------------------------


$db = new Database();
$db->connect();

$test = [];
$test['DataBaseRequests'] = $db->init();

$user = new User($db);
$test['User-Table'] = $user->init();
$test['User-Test'] = $user->test();


$publication = new Publication($db);
$test['Publication-Table'] = $publication->init();
$test['Publication-Test'] = $publication->test();

if (!empty($_FILES['file'])) {

    $file = new File($db);
    $test['File-Table'] = $file->init();
    $test['File-Test'] = $file->test($_FILES['file']);
}

// --------------------------------------------------------------------

?><!DOCTYPE>
<html>
<head>
	<meta charset="UTF-8">
</head>
<body>

<form action="" method="post" enctype="multipart/form-data">
	<input type="file" name="file">
	<button>Test FILE</button>
</form>

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