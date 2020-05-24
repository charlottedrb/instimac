<?php

require_once '../../includes.php';

use Reagir\Reagir;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_GET, ['id_ut'], ['id_photo'], ['type'])) {

$secured = Sanitize::arrayFields($_GET, ['id_ut'], ['id_photo'], ['type']);

// --------------- PROCESSING THE REQUEST------------------------

$reagir = new Reagir($db);
$reagir->type = $secured['type'];
$reagir->id_ut = $secured['id_ut'];
$reagir->id_photo = $secured['id_photo'];

if ($reagir->ajout()) { //action a faire

http_response_code(200); //envoie reponse
echo json_encode(
[
'id' => $commentaire->id,
'date' => $commentaire->date,
'type' => $commentaire->type,
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