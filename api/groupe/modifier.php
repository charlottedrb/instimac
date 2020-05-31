<?php

require_once '../../src/includes-api.php';

use Groupe\Groupe;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_POST, ['id', 'titre', 'lieu', 'date'])) {

    $secured = Sanitize::arrayFields($_POST, ['id', 'titre', 'lieu', 'date']);

// --------------- PROCESSING THE REQUEST------------------------

    $groupe = new Groupe($db);
    $groupe->id = $secured['id'];

    if ($groupe->update($secured['titre'], $secured['lieu'], $secured['date']) !== FALSE) {

        http_response_code(200);
        echo json_encode(
            [
                'id' => $groupe->id,
                'titre' => Sanitize::display($groupe->nom),
                'lieu' => Sanitize::display($groupe->lieu),
                'date' => $groupe->date
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
}