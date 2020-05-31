<?php

require_once '../../src/includes-api.php';

use Publication\Publication;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_GET, ['groupe'])) {

    $secured = Sanitize::arrayFields($_GET, ['groupe']);

// --------------- PROCESSING THE REQUEST------------------------

    $publication = new publication($db);
    $data = $publication->getMultiple($secured['groupe']);

    if (!empty($data)) {
        http_response_code(200);//envoie reponse
        echo json_encode($data);

    } else {
        http_response_code(200);
        echo json_encode([]);
    }

// --------------- END - PROCESSING THE REQUEST------------------------

} else {
    http_response_code(400);
    echo json_encode('Missing Arguments');
}