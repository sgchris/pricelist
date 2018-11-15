<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PriceListReader {
    
    /* @var string */
    protected $filePath;
    
    /* @var array */
    protected $data = null;
    
    /**
     * @param string $filePath XLSX file path
     * 
     * @return PriceListReader
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
        if (!is_null($this->data)) {
            return $this->data;
        }
        
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $this->data = $sheet->toArray();
        
        $lastRow = $this->_getLastRowNumber();
        $lastCol = $this->_getLastColumnNumber();
        
        // cut the empty edges
        $this->data = array_slice($this->data, 0, $lastRow);
        
        foreach ($this->data as $rowNum => $row) {
            $this->data[$rowNum] = array_slice($this->data[$rowNum], 0, $lastCol);
        }
        
        return $this->data;
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
    
    /**
     * 
     * Get the last row - last row is the one filled with nulls
     * @return  
     */
    protected function _getLastRowNumber()
    {
        foreach ($this->data as $rowNum => $row) {
            // skip the first "metadata" row
            if ($rowNum === 0) {
                continue;
            }
            
            // check all its cells
            $rowIsEmpty = true;
            foreach ($row as $cell) {
                if (!empty($cell)) {
                    $rowIsEmpty = false;
                    break;
                }
            }
            
            if ($rowIsEmpty) {
                break;
            }
        }
        
        return $rowNum;
    }

    
    /**
     * 
     * Get the last row - last row is the one filled with nulls
     * @return  
     */
    protected function _getLastColumnNumber()
    {
        for ($lastColumn = 1; $lastColumn < count($this->data[0]); ++$lastColumn) {
            $isLastColumn = true;
            foreach ($this->data as $row) {
                if (!empty($row[$lastColumn])) {
                    $isLastColumn = false;
                    break;
                }
            }
            
            if ($isLastColumn) {
                break;
            }
        }
        
        return $lastColumn;
    }
    
}
