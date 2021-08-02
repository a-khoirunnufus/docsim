<?php

// EXAMPLE FILE FOR UPLOAD DOCUMENT

include 'vendor/autoload.php';
include 'utils/__init.php';
include 'config.php';

use Utils\Database;
use Utils\PdfParser;
use Utils\File;
use Ramsey\Uuid\Uuid;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$data['title'] = $_POST["title"];
	$data['author'] = $_POST["author"];
	$data['description'] = $_POST["description"];

	// handle file upload
	
	$pdf_file_path = APP_PATH."/storage/".Uuid::uuid4()."-".basename($_FILES["file"]["name"]);
	$uploadOk = 1;

	// var_dump($target_file);
	// exit();

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	  echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	  if (move_uploaded_file($_FILES["file"]["tmp_name"], $pdf_file_path)) {
	    echo "The file <strong>". htmlspecialchars( basename( $_FILES["file"]["name"])). "</strong> has been uploaded.";
	  } else {
	    echo "Sorry, there was an error uploading your file.";
	    exit();
	  }
	}

	// $mimeType = mime_content_type($target_file);
	// echo "File mime type: {$mimeType}";

	// SAVE LOCAL FILE
	$txt_file_path = PdfParser::saveRawText($pdf_file_path);
	$prepro_text= PdfParser::getPreprocessingText($txt_file_path);

	$pdf = bin2hex(File::getContents($pdf_file_path)['content']);

	// insert to db
	$db = new Database();
	$conn = $db->connect();
	$sql =  "INSERT INTO document(title, pdf_file, prepro_text, author, description)".
			"VALUES(?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("sssss", $data['title'], $pdf, $prepro_text, $data['author'], $data['description']);
	$stmt->execute();
	$stmt->close();
	$conn->close();

	// delete local file
	unlink($pdf_file_path);
	unlink($txt_file_path);
	// unlink($preprocessing_txt_file_path);
}

?>

<html>
<head>
	<title>Upload Dokumen</title>
	<style type="text/css">
		input {
			margin: .5rem 0;
		}
	</style>
</head>
<body>
<div style="width: 400px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 2rem; background-color: rgba(0,0,0,.05);">

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
	title: <input type="text" name="title"><br>
	file: <input type="file" name="file"><br>
	author: <input type="text" name="author"><br>
	description: <input type="text" name="description"><br>
	<input type="submit" value="submit">
</form>

</div>
</body>
</html>