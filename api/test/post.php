<?php

header('Content-Type: application/json; charset=UTF-8');

if (isset($_POST['test'])) {
    http_response_code(200);
    echo json_encode("It's posting myself!");
} else {
    http_response_code(500);
    echo json_encode(false);
}