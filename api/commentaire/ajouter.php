<?php

require_once '../../includes.php';

use Commentaire\Commentaire;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_GET, ['texte'], ['id_ut'], ['id_photo'])) {

$secured = Sanitize::arrayFields($_GET, ['texte'], ['id_ut'], ['id_photo']);

// --------------- PROCESSING THE REQUEST------------------------

$commentaire = new Commentaire($db);
$commentaire->texte = $secured['texte'];
$commentaire->id_ut = $secured['id_ut'];
$commentaire->id_photo = $secured['id_photo'];


if ($commentaire->ajout()) {//action a faire

http_response_code(200);//envoie reponse
echo json_encode(
[
'id' => $commentaire->id,
'date' => $commentaire->date,
'texte' => $commentaire->texte
]
);

} else {
http_response_code(500);
echo json_encode('Server Error');
}

// --------------- END - PROCESSING THE REQUEST------------------------

} else {

http_response_code(400);
echo json_encode('Missing Arguments');

/* http_response_code(401);
echo json_encode('Please login to use our services'); */

}