<?php

require_once '../../includes.php';

use Commentaire/Commentaire;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_GET, ['texte'], ['utilisateur'], ['photo'])) {

$secured = Sanitize::arrayFields($_GET, ['texte'], ['utilisateur'], ['photo']);

// --------------- PROCESSING THE REQUEST------------------------

$commentaire = new Commentaire($db);
$commentaire->id = $secured['id'];

if ($commentaire->ajoutCommentaire($secured['utilisateur'], $secured['photo'], $secured['texte'])) {//action a faire

http_response_code(200);//envoie reponse
echo json_encode(
[
'id' => $commentaire->getId(),
'date' => $commentaire->getDate(),
'texte' => $commentaire->getTexte()
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