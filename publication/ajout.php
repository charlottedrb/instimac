<?php

require_once '../../includes.php';

use Publication\Publication;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_GET, ['photo'], ['id_ut'], ['lieu'], ['description'], ['groupe'])) {

$secured = Sanitize::arrayFields($_GET, ['photo'], ['id_ut'], ['lieu'],  ['description'], ['groupe']);

// --------------- PROCESSING THE REQUEST------------------------

$publication = new publication($db);
$publication->photo = $secured['photo'];
$publication->id_ut = $secured['id_ut'];
$publication->lieu = $secured['lieu'];
$publication->description = $secured['description'];
$publication->groupe = $secured['groupe'];




if ($publication->ajout()) {//action a faire

http_response_code(200);//envoie reponse
echo json_encode(
[
'id' => $publication->id,
'date' => $publication->date,
'lieu' => $publication->lieu,
'photo' => $publication->photo,
'description' => $publication->description,
'groupe' => $publication->groupe
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