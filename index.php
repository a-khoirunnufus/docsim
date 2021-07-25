<?php

include 'vendor/autoload.php';

require_once 'utils/PdfParser.php';
// require_once 'utils/Database.php';
require_once 'utils/Text.php';

use Utils\PdfParser;
// use Utils\Database;

$parsedText = PdfParser::parseText('storage/test_document.pdf');
$sentencePerLineText = PdfParser::genSentencePerLine($parsedText);
PdfParser::saveTXT($sentencePerLineText, 'storage/test_text.txt');