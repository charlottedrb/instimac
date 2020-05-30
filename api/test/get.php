<?php

header('Content-Type: application/json; charset=UTF-8');

if (isset($_GET['test'])) {
    http_response_code(200);
    echo json_encode("It's getting myself off!");
} else {
    http_response_code(500);
    echo json_encode(false);
}