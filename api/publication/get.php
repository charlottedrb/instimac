<?php

require_once '../../src/includes-api.php';

use Publication\Publication;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

if (Sanitize::checkEmptyFields($_GET, ['id'])) {

    $secured = Sanitize::arrayFields($_GET, ['id']);

// --------------- PROCESSING THE REQUEST------------------------

    $publication = new publication($db);
    $publication->id = $secured['id'];

    if ($publication->getById($secured['id'])) {

        http_response_code(200);
        echo json_encode(
            [
                'id' => $publication->id,
                'description' => $publication->description,
                'photoURL',
                'date' => $publication->date,
                'utilisateur' => ['photoURL', 'nom']
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