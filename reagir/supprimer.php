<?php

require_once '../../includes.php';

use Reagir\Reagir;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_GET, ['id_phot'], ['id_ut'])) {

$secured = Sanitize::arrayFields($_GET, ['id_photo'], ['id_ut']);

// --------------- PROCESSING THE REQUEST------------------------

$reagir = new Reagir($db);
$reagir->id_photo = $secured['id_photo'];
$reagir->id_ut = $secured['id_ut'];


if ($reagir->delete($secured['id_photo'], $secured['id_ut'])) { //action a faire

http_response_code(200); //envoie reponse
echo json_encode(
[
'id_photo' => $reagir->id_photo,
'id_ut' => $reagir->id_ut
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