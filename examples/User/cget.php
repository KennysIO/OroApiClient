<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$apiUrl = 'http://your.oro.com/api/rest/latest';
$user   = 'user';
$token  = 'token';
$api    = new KennysIO\OroApiClient\Api($apiUrl, $user, $token);

$query  = [

    'limit' => 2,
    'page' => 1
];

$response = $api->get('users', $query);

print_r($response->raw_headers);
print_r($response->body);
