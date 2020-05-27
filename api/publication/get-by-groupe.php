<?php

require_once '../../includes.php';

use Publication\Publication;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_GET, ['groupe', 'limite'])) {

$secured = Sanitize::arrayFields($_GET, ['groupe', 'limite']);

// --------------- PROCESSING THE REQUEST------------------------

$publication = new publication($db);
$tabpublication = $publication->getMultiple(['g_id'=>$secured['groupe']]);

if ($tabpublication) {//action a faire
	$tabResultat;
	foreach($tabpublication as $grp){
		foreach($tabResultat as $nouvGroupe){
			$nouvGroupe['id'] = $tabpublication['id'];
			$nouvGroupe['description'] = $tabpublication['description'];
			$photoURl;
			$nouvGroupe['utilisateur'=>"photoURL"] = $tabpublication['utilisateur'=>"photoURL"];
			$nouvGroupe['utilisateur'=>'nom'] = $tabpublication['utilisateur'=>'nom'];
		}
	}
http_response_code(200);//envoie reponse
echo json_encode(
$nouvGroupe;
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