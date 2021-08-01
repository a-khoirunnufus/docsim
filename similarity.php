<?php

include 'utils/__init.php';
include 'vendor/autoload.php';

use Utils\Database;
use Utils\Similarity;

// looping start
// for ($i=2; $i < 12; $i++) { 

// GET DOCUMENT
$db = new Database();
$conn = $db->connect();

$sql = "SELECT id, title, prepro_text FROM document WHERE id = 13";
$main_doc = $db->queryGetResultArr($sql);

$sql = "SELECT id, title, prepro_text FROM document WHERE id = 14";
$comp_doc = $db->queryGetResultArr($sql);

$db->close();

// convert to array
$main["title"] = $main_doc[0]["title"];
$main["sntcs"] = explode("<SEP>", $main_doc[0]["prepro_text"]);

$comp["title"] = $comp_doc[0]["title"];
$comp["sntcs"] = explode("<SEP>", $comp_doc[0]["prepro_text"]);

// var_dump($comp["sntcs"]);
// exit();

$data["mainDoc"] = ["docTitle" => $main["title"], "sntcs" => $main["sntcs"]];
$data["compDoc"] = ["docTitle" => $comp["title"], "sntcs" => $comp["sntcs"]];

$data_json = json_encode($data);

$client = new \GuzzleHttp\Client();
$response = $client->request('POST', 'https://docsim-api.herokuapp.com/similarity', ['json' => $data]);

$res = json_decode($response->getBody());

// var_dump($res->result);

foreach($res->result as $key_a => $sim)
{
    foreach($sim->cossimList as $key_b => $num)
    {
        if($num >= 0.5)
        {
            echo $data["mainDoc"]["sntcs"][$key_a].", <br>is similar to:<br> ".$data["compDoc"]["sntcs"][$key_b];
            echo "<br>with sim value: ".$num."<br><br>";
        }
    }
}

// }
// looping end