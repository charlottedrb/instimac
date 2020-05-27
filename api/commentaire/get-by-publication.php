<?php

require_once '../../includes.php';

use Commentaire\Commentaire;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_GET, ['publication-id']) {

    $secured = Sanitize::arrayFields($_GET, ['publication-id']);

    // --------------- PROCESSING THE REQUEST------------------------

    $commentaire = new Commentaire($db);
    $tabCommentaire = $commentaire->getTabComPhotoDateDecoissant($secured['idPublication']);
    
    if ($tabCommentaire) {//action a faire
        $tabResultat;
        foreach ($tabCommentaire as $com) {
            foreach ($tabResultat as $newComResultat) {
                $newComResultat['id'] = $tabCommentaire['id'];
                $newComResultat['contenu'] = $tabCommentaire['texte'];
                $newComResultat['date'] = $tabCommentaire['date'];
                $newComResultat['utilisateur'][['photoURL','nom']];
            }
        }
        http_response_code(200);//envoie reponse
        echo json_encode(
            $newComResultat;
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