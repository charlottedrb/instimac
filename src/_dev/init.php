<?php
include '../autoloader.php';

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

$test = [];
$env = [];

// ------------------ PROCESS INIT & TESTING -------------------------

use Database\Test as Database;
use User\User;
use Publication\Publication;
use File\File;
use Groupe\Groupe;
use Reagir\Reagir;
use Commentaire\Commentaire;

$db = new Database();
$db->connect();

if (!empty($_GET['drop'])) {

    $db->exec('DROP TABLE utilisateurs, commentaires, photos, files,reactions, groupes, test');
}


if (!empty($_FILES['file1'])) {

    //--------- DATABASE SETUP ---------------

    $test['DataBaseRequests'] = $db->init();

    $user = new User($db);
    $test['User-Table'] = $user->init();

    $groupe = new Groupe($db);
    $test['Groupe-Table'] = $groupe->init();

    $publication = new Publication($db);
    $test['Publication-Table'] = $publication->init();

    $commentaires = new Commentaire($db);
    $test['Commentaire-Table'] = $commentaires->init();

    $reagir = new Reagir($db);
    $test['Reagir-Table'] = $reagir->init();


    $file = new File($db);
    $test['File-Table'] = $file->init();

    //--------- DEV ENVIRONMENT ---------------

    $user = new User($db);
    $user->name = "Grégoire";
    $user->surname = "Petitalot";
    $user->email = "perso@gregoirep.com";
    $user->password = "gregoire";
    $user->enabled = 1;
    $env['User-1'] = $user->create(['ADMIN', 'USER']);

    $user2 = new User($db);
    $user2->name = "BOB";
    $user2->surname = "LE BO";
    $user2->email = "test@test.com";
    $user2->password = "test";
    $user2->enabled = 1;
    $env['User-2'] = $user2->create(['USER']);


    $file = new File($db);
    $env['File-1-set'] = $file->set($_FILES['file1']);

    $groupe = new Groupe($db);
    $env['Groupe-1'] = $groupe->set('JEUDIMAC', 'COPEPANIC', "2016-05-12 12:23:02", $user->id);

    $groupe2 = new Groupe($db);
    $env['Groupe-2'] = $groupe2->set('BUREAU SONIA', 'RESERVE', "2018-05-22 12:52:02", $user2->id);


    $publication = new Publication($db);
    $env['Publication-set'] = $publication->set('Une belle publication', $groupe->id, $user->id);
    $publication->photoId = $file->id;
    $env['Publication-up'] = $publication->update();

    $publicationBis = new Publication($db);
    $env['Publication-BIS-set'] = $publicationBis->set('Je suis une belle', $groupe2->id, $user2->id);
    $publicationBis->photoId = $file->id;
    $env['Publication-BIS-up'] = $publicationBis->update();

    $commentaire = new Commentaire($db);
    $env['Commentaire-set-1'] = $commentaire->set($user->id, $publication->id, 'Very beautiful Comentaire');
    $env['Commentaire-set-2'] = $commentaire->set($user->id, $publication->id, 'Very beautiful 2Comentaire');
    $env['Commentaire-set-3'] = $commentaire->set($user2->id, $publication->id, 'Very beautiful 3Comentaire');
    $env['Commentaire-set-4'] = $commentaire->set($user->id, $publication->id, 'Very beautiful 4Comentaire');
    $env['Commentaire-set-5'] = $commentaire->set($user2->id, $publication->id, 'Very beautiful Codffmentaire');

    $reaction = new Reagir($db);
    $env['Reaction-set-1'] = $reagir->set(1, $publication->id, $user->id);
    $env['Reaction-set-12'] = $reagir->set(1, $publication->id, $user->id);

    $env['Reaction-set-21'] = $reagir->set(1, $publication->id, $user->id);
    $env['Reaction-set-22'] = $reagir->set(1, $publication->id, $user->id);

}

// --------------------------------------------------------------------

?><!DOCTYPE>
<html>
<head>
	<meta charset="UTF-8">
</head>
<body>

<h1>1- Add a file</h1>

<form action="" method="get" enctype="multipart/form-data">
	<input type="hidden" name="drop" value="1"><br>
	<button style="background-color: red;">/!\ DROP DATABASE</button>
</form>

<form action="" method="post" enctype="multipart/form-data">
	<input type="file" name="file1"><br>
	<button>Test FILE</button>
</form>

<h1>2 - Check Database</h1>
<table>
	<tr>
		<td>Classe</td>
		<td>Résultat</td>
	</tr>
    <?php displayResults($test); ?>
</table>

<h1>3 - Check environment</h1>
<table>
	<tr>
		<td>Element</td>
		<td>Résultat</td>
	</tr>
    <?php displayResults($env); ?>
</table>

</body>
</html>