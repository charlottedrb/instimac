<?php

require_once '../../src/includes-api.php';

use Publication\Publication;
use Sanitize\Sanitize;
use File\File;


// Check if params are sended
if (Sanitize::checkEmptyFields($_GET, ['id'])) {

    $secured = Sanitize::arrayFields($_GET, ['id']);

// --------------- PROCESSING THE REQUEST------------------------

    $file = new File($db);

    if ($file->getById($secured['id'])) {

        http_response_code(200);
        header('Content-Type: ' . $file->mime . '; charset=UTF-8');
        echo $file->getContent();
        exit;

    } else {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code(500);
        echo json_encode('Server Error');
    }

// --------------- END - PROCESSING THE REQUEST------------------------

} else {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(400);
    echo json_encode('Missing Arguments');
}