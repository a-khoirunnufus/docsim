<?php

include 'vendor/autoload.php';
include 'utils/__init.php';
include 'config.php';

use Utils\PdfParser;
use Utils\Database;
use Utils\Similarity;

// echo APP_PATH;

/*
 *
 * Parse pdf to text version 1, then separate per sentences
 *
 */
// $parsedText = PdfParser::parseText('storage/artikel-kompas-2.pdf');
// $sentencePerLineText = PdfParser::genSentencePerLine($parsedText);
// PdfParser::saveTXT($sentencePerLineText, 'storage/artikel-kompas-2.txt');

/*
 *
 * get similarity report
 *
 */
$arrSentences1 = Similarity::getArrSentences('storage/artikel-kompas-1-preprocessing.txt');
$arrSentences2 = Similarity::getArrSentences('storage/artikel-kompas-2-preprocessing.txt');

$simReport = Similarity::compareSentences($arrSentences1, $arrSentences2);

var_dump($simReport);


/*
 *
 * Parse pdf to text version 2 (better), then separate per sentences
 *
 */
// $fd = popen("tools/xpdf-tools-linux-4.03/bin64/pdftotext storage/artikel-kompas-2.pdf", "r");
// pclose($fd);

// $parsedText = PdfParser::parseFromTxt('storage/artikel-kompas-2.txt');
// $sentencePerLineText = PdfParser::genSentencePerLine($parsedText);
// PdfParser::saveTXT($sentencePerLineText, 'storage/artikel-kompas-2-formatted.txt');