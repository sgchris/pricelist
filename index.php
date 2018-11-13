<?php

require 'vendor/autoload.php';
require 'MyXlsxReader.php';
require 'PriceListGenerator.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$inputFileName = __DIR__.'/data/Price_List_1.xlsx';

/** Load $inputFileName to a Spreadsheet Object  **/
/* @var \PhpOffice\PhpSpreadsheet\Reader\Xlsx */
//$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');

/* @var \PhpOffice\PhpSpreadsheet\Spreadsheet */
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
$sheet = $spreadsheet->getActiveSheet();
$data = $sheet->toArray();

$html = PriceListGenerator::generateHtml($data);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php echo $html; ?>
</body>
</html>


