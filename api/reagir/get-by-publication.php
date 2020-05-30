<?php

require_once '../../src/includes-api.php';

use Reagir\Reagir;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_GET, ['publication'])) {

    $secured = Sanitize::arrayFields($_GET, ['publication']);

// --------------- PROCESSING THE REQUEST------------------------

    $reagir = new Reagir($db);

    if ($reagir->getCount($secured['publication'])) {

        http_response_code(200);
        echo json_encode(['love' => $reagir->count]);

    } else {
        http_response_code(500);
        echo json_encode('Server Error');
    }

// --------------- END - PROCESSING THE REQUEST------------------------

} else {
    http_response_code(400);
    echo json_encode('Missing Arguments');
}