<?php

include 'utils/__init.php';
include 'config.php';

use Utils\Database;
use Utils\PdfParser;
use Utils\File;
// use Utils\Text;

// SAVE LOCAL FILE EXAMPLE
// $save1 = PdfParser::saveRawText(APP_PATH."/storage/artikel-kompas-2.pdf");
// $save2 = PdfParser::savePreprocessingText($save1);

/*
 * INSERT DOCUMENT TO DATABASE EXAMPLE START
 */

// $db = new Database();
// $conn = $db->connect();

// $sql =  "INSERT INTO document(title, pdf_file, txt_file, preprocessing_txt_file, author, description)".
// 		"VALUES(?, ?, ?, ?, ?, ?)";
// $stmt = $conn->prepare($sql);

// $title = "test_document";
// $pdf = bin2hex(File::getContents(APP_PATH."/storage/artikel-cnn.pdf")['content']);
// $txt = bin2hex(File::getContents(APP_PATH."/storage/artikel-cnn.txt")['content']);
// $preproTxt = bin2hex(File::getContents(APP_PATH."/storage/artikel-cnn-preprocessing.txt")['content']);
// $author = "Budi Sibudi";
// $description = "test description";
// $stmt->bind_param("ssssss", $title, $pdf, $txt, $preproTxt, $author, $description);
// $stmt->execute();

// $stmt->close();
// $conn->close();

/*
 * INSERT DOCUMENT TO DATABASE EXAMPLE END
 */



// GET DOCUMENT EXAMPLE
// $db = new Database();
// $conn = $db->connect();
// $sql = "SELECT * FROM document";
// $result = $db->queryGetResultArr($sql);

// var_dump($result);

