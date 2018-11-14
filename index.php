<?php
/**
 * Using: phpspreadsheet (https://phpspreadsheet.readthedocs.io/en/develop/)
 */

require 'vendor/autoload.php';

require 'PriceListReader.php';
require 'PriceListHTMLGenerator.php';

// get the latest data file
$inputFileName = getLatestDataFile();
if (!$inputFileName) {
    die('No data file');
}

// read the data
$data = PriceListReader::getXlsxData($inputFileName);

// generate the HTML (string)
$html = PriceListHTMLGenerator::generateHtml($data);

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
<?php

function getLatestDataFile() 
{
    $dataFiles = glob(__DIR__.'/data/*.xlsx');
    $latestFile = false;
    foreach ($dataFiles as $dataFile) {
        if (!$latestFile || filemtime($latestFile) < filemtime($dataFile)) {
            $latestFile = $dataFile;
        }
    }
    
    return $latestFile;
}


