<?php

require_once '../../src/includes-api.php';

use Commentaire\Commentaire;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_GET, ['publication-id'])) {

    $secured = Sanitize::arrayFields($_GET, ['publication-id']);

    // --------------- PROCESSING THE REQUEST------------------------

    $commentaire = new Commentaire($db);

    $data = $commentaire->getMultiple($secured['publication-id']);

    if (!empty($data)) {

        http_response_code(200);//envoie reponse
        echo json_encode($data);

    } else {
        echo json_encode([]);
    }

// --------------- END - PROCESSING THE REQUEST------------------------

} else {

    http_response_code(400);
    echo json_encode('Missing Arguments');

    /* http_response_code(401);
    echo json_encode('Please login to use our services'); */

}