<?php

require_once '../../src/includes-api.php';

use Commentaire\Commentaire;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_POST, ['publication-id', 'contenu'])) {

    $secured = Sanitize::arrayFields($_POST, ['publication-id', 'contenu']);

    // --------------- PROCESSING THE REQUEST------------------------

    $commentaire = new Commentaire($db);

    if ($commentaire->set($_SESSION['user']->id, $secured['publication-id'], $secured['contenu']) !== FALSE) {

        http_response_code(200);
        echo json_encode(
            [
                'id' => $commentaire->id,
                'date' => $commentaire->created,
                'contenu' => Sanitize::display($commentaire->texte),
                'utilisateur' => [
                    'photoURL' => './img/default-user.jpg',
                    'nom' => Sanitize::display($_SESSION['user']->name . ' ' . $_SESSION['user']->surname),
                ]
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