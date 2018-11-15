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
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
</head>
<body>
    <div style="width: 500px;">
    <?php echo $html; ?>
    </div>
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


