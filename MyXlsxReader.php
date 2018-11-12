<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MyXlsxReader {
    
    /* @var string */
    protected $filePath;
    
    /**
     * @param string $filePath XLSX file path
     * 
     * @return MyXlsxReader
     */
    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    /**
     * Get the data from the Xlsx files as an array [row][col]
     * 
     * @return array
     */
    public function getData() {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();
        return $sheet->toArray();
    }
    
    /**
     * @param mixed $filePath 
     * 
     * @return array[row][col]
     */
    public static function getXlsxData($filePath)
    {
        $mxr = new self($filePath);
        return $mxr->getData();
    }
}
