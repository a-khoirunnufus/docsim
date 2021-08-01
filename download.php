<?php

include 'utils/__init.php';

use Utils\Database;
use Utils\Similarity;

// GET DOCUMENT
$db = new Database();
$conn = $db->connect();

$sql = "SELECT id, title, prepro_text FROM document WHERE id = 1";
$main_doc = $db->queryGetResultArr($sql);

$sql = "SELECT id, title, prepro_text FROM document WHERE id = 2";
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

header("Content-type:application/json");
echo $data_json;