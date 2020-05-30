<?php

require_once '../../src/includes-api.php';

use Publication\Publication;
use Sanitize\Sanitize;
use File\File;

header('Content-Type: application/json; charset=UTF-8');

// Check if params are sended
if (Sanitize::checkEmptyFields($_POST, ['description', 'groupe']) && Sanitize::checkEmptyFields($_FILES, ['photo'])) {

    $secured = Sanitize::arrayFields($_POST, ['description', 'groupe']);

// --------------- PROCESSING THE REQUEST------------------------

    $publication = new Publication($db);
    $file = new File($db);

    if ($file->set($_FILES['photo']) !== FALSE) {

        if ($publication->set($secured['description'], $secured['groupe'], $_SESSION['user']->id, $file->id) !== FALSE) {

            http_response_code(200);
            echo json_encode(
                [
                    'id' => $publication->id,
                    'date' => $publication->date,
                    'description' => $publication->description,
                    'photoURL' => $file->idToURL(),
                    'utilisateur' => [
                        'photoURL' => './img/default-user.jpg',
                        'nom' => $_SESSION['user']->name . ' ' . $_SESSION['user']->surname,
                    ]
                ]
            );
            exit;
        } else{
            http_response_code(500);
            echo json_encode('Server Error: #2');
        }
    } else {
        http_response_code(500);
        echo json_encode('Server Error: #1');
    }


// --------------- END - PROCESSING THE REQUEST------------------------

} else {
    http_response_code(400);
    echo json_encode('Missing Arguments');
}