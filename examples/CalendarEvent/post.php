<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$apiUrl = 'http://your.oro.com/api/rest/latest';
$user   = 'user';
$token  = 'token';
$api    = new KennysIO\OroApiClient\Api($apiUrl, $user, $token);

$event = [

    'calendar' => 1,
    'title' => 'Hello World!',
    'start' => "1970-01-01T00:00:00",
    'end' => "1970-01-01T01:00:00"
];

$response = $api->post('calendarevents', $event);

print_r($response->raw_headers);
print_r($response->body);
