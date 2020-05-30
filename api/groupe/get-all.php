<?php

require_once '../../src/includes-api.php';

use Groupe\Groupe;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

if (Sanitize::checkEmptyFields($_GET)) {

    $secured = Sanitize::arrayFields($_GET);

// --------------- PROCESSING THE REQUEST------------------------

    $groupe = new Groupe($db);
    $data = $groupe->getMultiple();

    if ($data !== FALSE && !empty($data)) {
        http_response_code(200);//envoie reponse
        echo json_encode($data);

    } else {
        http_response_code(500);
        echo json_encode('Server Error');
    }

// --------------- END - PROCESSING THE REQUEST------------------------

} else {
    http_response_code(400);
    echo json_encode('Missing Arguments');
}