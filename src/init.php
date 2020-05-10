<?php

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

require 'autoloader.php';

use Database\Database;

$test = [];

$db = new Database();
$test['Database'] = $db->connect();

?>
<!DOCTYPE>
<html>
<head>

</head>
<body>

<h1>Initialisation</h1>
<table>
	<td>Classe</td>
	<td>Résultat</td>
	</th>
    <?php displayResults($test); ?>
</table>
</body>
</html>
