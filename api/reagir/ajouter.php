<?php

require_once '../../includes.php';

use Reagir\Reagir;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_GET, ['publication','type_reaction'])) {

$secured = Sanitize::arrayFields($_GET, ['publication','type_reaction']);

// --------------- PROCESSING THE REQUEST------------------------

$reagir = new Reagir($db);
$reagir->type = $secured['type_reaction'];
$reagir->id_photo = $secured['publication'];

if ($reagir->set($secured['type_reaction'],$secured['publication'],$idUt) && $reagir->getCount($secured['publication']) ) { 
    $count=$reagir->getCount($secured['publication']
    http_response_code(200); //envoie reponse
    echo json_encode(
        [
            'love' => $count,
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