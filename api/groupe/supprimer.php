<?php

require_once '../../includes.php';

use Groupe\Groupe;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_GET, ['id'], ['id_ut'])) {

$secured = Sanitize::arrayFields($_GET, ['id'], ['id_ut']);

// --------------- PROCESSING THE REQUEST------------------------

$groupe = new Groupe($db);
$groupe->nom = $secured['id'];


if ($groupe->delete($secured['id'], $secured['id_ut'])) {//action a faire

http_response_code(200);//envoie reponse
echo json_encode(
[
'id' => $groupe->id
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