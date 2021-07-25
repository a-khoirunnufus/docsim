<?php

require_once 'utils/PdfParser.php';

use Utils\PdfParser;

$parser = new PdfParser();

$parser->parseText('test_document.pdf');