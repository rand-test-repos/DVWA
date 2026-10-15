<?php

require_once __DIR__ . '/../src/User.php';
require_once __DIR__ . '/../src/UserController.php';

unset($_SERVER['HTTP_AUTHORIZATION']);

$controller = new Src\UserController('GET', 2, null);
http_response_code(200);
ob_start();
$controller->processRequest();
$body = ob_get_clean();

if (http_response_code() !== 401) {
    throw new RuntimeException('Unauthenticated collection GET did not return 401');
}

if ($body !== json_encode(array ('status' => 'Invalid or missing token'))) {
    throw new RuntimeException('Unauthenticated collection GET did not return the authentication error');
}

foreach (array ('tony', 'morph', 'chas', '"id"', '"name"', '"level"') as $rosterField) {
    if (strpos($body, $rosterField) !== false) {
        throw new RuntimeException('Unauthenticated collection GET disclosed user roster data');
    }
}
