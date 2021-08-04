<?php

// FILE TO GET SIMILARITY REPORT

include 'vendor/autoload.php';

$diplay_result = false;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$data['mainDocId'] = $_POST["mainDocId"];
	$data['compDocId'] = $_POST["compDocId"];
	$data['treshold'] = $_POST["treshold"];

    $client = new \GuzzleHttp\Client();
    $response = $client->request(
        'POST', 
        'http://localhost/www/docsim/genSimReport.php', 
        ['form_params' => $data]
    );
    $res = json_decode($response->getBody());
    $diplay_result = true;
}
?>

<html>
<head>
    <title>Similarity Check</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
        }
        td {
            vertical-align: top;
        }
    </style>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
        main doc id: <input type="text" name="mainDocId"><br>
        comp doc id (separate with ','): <input type="text" name="compDocId"><br>
        treshold: <input type="text" name="treshold"><br>
        <input type="submit" value="submit">
    </form>
    <hr>
    <?php if($diplay_result): ?>
        <h3>Result:</h3>
        <p><strong>Document Id:</strong> <?= $res->docId; ?></p>
        <p><strong>Document Title:</strong> <?= $res->docTitle; ?></p>
        <p><strong>Sentences Count:</strong> <?= $res->sntcsCount; ?></p>
        <p><strong>Similarity Count:</strong> <?= $res->simCount; ?></p>

        <table>
            <tr>
                <th>Id</th>
                <th>Sentences</th>
                <th>Similar Sentences</th>
            </tr>
            <?php foreach($res->details as $detail): ?>
                <tr>
                    <td>
                        <?= $detail->sntcsId; ?>
                    </td>
                    <td>
                        <?= $detail->sntcs; ?>
                    </td>
                    <td>
                        <ul>
                            <?php foreach($detail->similarTo as $similar): ?>
                                <li>
                                    <strong>Document id:</strong> <?= $similar->docId; ?><br>
                                    <strong>Sentence id:</strong> <?= $similar->sntcsId; ?><br>
                                    <strong>Sentence:</strong> <?= $similar->sntcs; ?><br>
                                    <strong>Similarity:</strong> <?= strval( round($similar->cossim,2)*100 ); ?>%
                                </li> 
                            <?php endforeach; ?>
                        </ul>
                    </td>
                <tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>