<?php

require_once '../../src/includes-api.php';

use Commentaire\Commentaire;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_GET, ['id'])) {

    $secured = Sanitize::arrayFields($_GET, ['id']);

// --------------- PROCESSING THE REQUEST------------------------

    $commentaire = new Commentaire($db);
    $commentaire->id = $secured['id'];

    if ($commentaire->delete($secured['id'], $_SESSION['user']->id) !== FALSE) {

        http_response_code(200);
        json_encode(['id' => $commentaire->id]);

    } else {
        http_response_code(500);
        echo json_encode('Server Error');
    }

// --------------- END - PROCESSING THE REQUEST------------------------

} else {
    http_response_code(400);
    echo json_encode('Missing Arguments');
}