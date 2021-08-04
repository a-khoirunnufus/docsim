<?php

include '../../../config.php';
include APP_PATH.'/vendor/autoload.php';
include APP_PATH.'/app/utils/__init.php';

use Utils\Database;
use Utils\Similarity;

// $mainDocId = 1;
// $compDocId = [2,3,4,5,6,7,8,9,10,11];
// $treshold = 0.3;

$mainDocId = $_POST["mainDocId"];
$compDocId = explode(",",$_POST["compDocId"]);
$treshold = floatval($_POST["treshold"]);

// GET DOCUMENT
$db = new Database();
$conn = $db->connect();

$sql = "SELECT id, title, prepro_text FROM document WHERE id = ".$mainDocId;
$main_doc = $db->queryGetResultArr($sql);
$main_doc = $main_doc[0];
$main["title"] = $main_doc["title"];
$main["sntcs"] = explode("<SEP>", $main_doc["prepro_text"]);

$sim_report["docId"] = $mainDocId;
$sim_report["docTitle"] = $main["title"];
$sim_report["sntcsCount"] = count($main["sntcs"]);
$sim_report["details"] = array();
foreach($main["sntcs"] as $key => $sntcs)
{
    array_push($sim_report["details"], array(
        "sntcsId" => $key,
        "sntcs" => $sntcs,
        "similarTo" => array()
    ));
}

// LOOPING START HERE
foreach($compDocId as $currentCompDocId)
{

    $sql = "SELECT id, title, prepro_text FROM document WHERE id = ".$currentCompDocId;
    $comp_doc = $db->queryGetResultArr($sql);
    $comp_doc = $comp_doc[0];
    $comp["title"] = $comp_doc["title"];
    $comp["sntcs"] = explode("<SEP>", $comp_doc["prepro_text"]);

    // convert to json
    $data["mainDoc"] = array( "docTitle" => $main["title"], "sntcs" => $main["sntcs"] );
    $data["compDoc"] = array( "docTitle" => $comp["title"], "sntcs" => $comp["sntcs"] );
    $data_json = json_encode($data);

    // send request to api
    $client = new Guzzle\Http\Client();
    $request = $client->post('https://docsim-api.herokuapp.com/similarity');
    $request->setBody(
        $data_json, 
        'application/json'
    );
    $res = $request->send()->json();

    foreach($res['result'] as $key_a => $sim)
    {
        foreach($sim['cossimList'] as $key_b => $cossim)
        {
            if($cossim >= $treshold)
            {
                $comp_sntcs = $comp["sntcs"];
                array_push($sim_report["details"][$key_a]["similarTo"], array(
                    "docId" => $currentCompDocId,
                    "docTitle" => $comp["title"],
                    "sntcsId" => $key_b,
                    "sntcs" => $comp_sntcs[$key_b],
                    "cossim" => $cossim
                ));
            }
        }
    }

}
// LOOPING END

$db->close();

$sim_report["simCount"] = 0;
foreach($sim_report["details"] as $detail)
{
    if(count($detail["similarTo"]) > 0)
    {
        $sim_report["simCount"] += 1;
    }
}

header("Content-type:application/json");
echo json_encode($sim_report);
