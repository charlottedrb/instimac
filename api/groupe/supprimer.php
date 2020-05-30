<?php

require_once '../../src/includes-api.php';

use Groupe\Groupe;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

if (Sanitize::checkEmptyFields($_GET, ['id'])) {

    $secured = Sanitize::arrayFields($_GET, ['id']);

// --------------- PROCESSING THE REQUEST------------------------

    $groupe = new Groupe($db);
    $groupe->id = $secured['id'];

    if ($groupe->delete($groupe->id, $_SESSION['user']->id) !== FALSE) {
        http_response_code(200);
        echo json_encode("OK");
    } else {
        http_response_code(500);
        echo json_encode('Server Error');
    }

// --------------- END - PROCESSING THE REQUEST------------------------

} else {
    http_response_code(400);
    echo json_encode('Missing Arguments');
}