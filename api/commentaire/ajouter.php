<?php

require_once '../../includes.php';

use Commentaire\Commentaire;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_POST, ['publication-id', 'contenu'])) {

    $secured = Sanitize::arrayFields($_POST, ['publication-id', 'contenu']);

    // --------------- PROCESSING THE REQUEST------------------------

    $commentaire = new Commentaire($db);
    $commentaire->texte = $secured['contenu'];
    //$commentaire->idUt = idUt;
    $commentaire->idPublication = $secured['publication-id'];


    if ($commentaire->ajout()) {//action a faire

        http_response_code(200);//envoie reponse
        echo json_encode(
            [
                'id' => $commentaire->id,
                'date' => $commentaire->date,
                'contenu' => $commentaire->texte,
                'utilisateur' => ['photoURL','nom']
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