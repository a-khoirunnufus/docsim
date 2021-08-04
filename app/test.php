<?php

require_once '../vendor/autoload.php';

// send request to api
// $client = new Guzzle\Http\Client();

// $request = $client->post(
//     'https://docsim-api.herokuapp.com/similarity', 
//     array(
//         'headers' => array('Content-Type' => 'application/json')
//     ),
//     $data_json
// );
// $res = $request->send()->json();

$request = $client->post('https://docsim-api.herokuapp.com/similarity');
$request->setBody(
    '{"foo":"baz"}', 
    'application/json'
);