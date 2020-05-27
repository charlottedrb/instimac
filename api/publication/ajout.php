<?php

require_once '../../includes.php';

use Publication\Publication;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_POST, ['photo','description', 'groupe'])) {

$secured = Sanitize::arrayFields($_POST, ['photo','description', 'groupe']);

// --------------- PROCESSING THE REQUEST------------------------

$publication = new publication($db);




if ($publication->set(FALSE, $secured['description'] )) {//action a faire

    http_response_code(200);//envoie reponse
    echo json_encode(
        [
            'id' => $publication->$id,
            'date' => $publication->$date,
            'description' => $publication->$description,
            'photoURL',
            'utilisateur' => ['photoURL','nom']
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