<?php

require_once '../../includes.php';

use Groupe\Groupe;
use Sanitize\Sanitize;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_GET)) {

$secured = Sanitize::arrayFields($_GET);

// --------------- PROCESSING THE REQUEST------------------------

$groupe = new Groupe($db);
$tabGroupe = $groupe->getGroupe($secured['id']);

if ($tabGroupe) {//action a faire
	$tabResultat;
	foreach($tabGroupe as $grp){
		foreach($tabResultat as $nouvCom){
			$nouvCom['id'] = $tabGroupe['id'];
			$nouvCom['titre'] = $tabGroupe['titre'];
			$nouvCom['lieu'] = $tabGroupe['lieu'];
			$nouvCom['date'] = $tabGroupe['date'];
		}
	}
http_response_code(200);//envoie reponse
echo json_encode(
$nouvCom;
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