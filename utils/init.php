<?php

include 'utils/PdfParser.php';
include 'utils/Database.php';
include 'utils/Text.php';
include 'utils/Similarity.php';

define("APP_PATH", realpath($_SERVER["DOCUMENT_ROOT"])."/".basename(__DIR__));