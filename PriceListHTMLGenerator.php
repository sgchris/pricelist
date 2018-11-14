<?php


class PriceListHTMLGenerator {
    
    protected $data = [];
    
    /**
     * 
     * @param mixed $data 
     * @return PriceListHTMLGenerator
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }
    
    
    /**
     * 
     * @param array $data 
     * @return  
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
    
    /**
     * Shortcut to get the HTML directly
     * 
     * @param mixed $data 
     * @return  
     */
    public static function generateHtml($data) 
    {
        /* @var PriceListHTMLGenerator */
        static $plg = null;
        
        // create the object
        if (is_null($plg)) {
            $plg = new self();
        }
        
        return $plg->setData($data)->getHtml();
    }
    
    
    /**
     * 
     * @return string
     */
    public function getHtml()
    {
        echo '<!--';
        var_dump($this->data);
        echo '-->';
        if (empty($this->data) || !isset($this->data[0]) || !is_array($this->data[0])) {
            return '<!-- price list data is not valid -->';
        }
        
        // start the HTML
        $html = '<div class="pl-wrapper">'.PHP_EOL;
        
        $numOfCols = count($this->data[0]) - 1;
        for ($col = 1; $col < $numOfCols + 1; ++$col) {
            $html.= '<div class="pl-col">'.PHP_EOL;
            for ($row = 1; $row < count($this->data); ++$row) {
                $html.= '<div class="pl-cell" style="'.($this->getCellStyle($col, $row)).'">'.PHP_EOL.
                    ($this->getCellHtml($col, $row)).PHP_EOL.
                    '</div>'.PHP_EOL;
            }
            $html.= '</div>'.PHP_EOL;
        }
        
        $html.= '</div>'.PHP_EOL;
        
        return $html;
    }
    

    /**
     * 
     * @param mixed $col 
     * @param mixed $row 
     * @return  
     */
    protected function getCellHtml($col, $row)
    {
        $html = $this->data[$row][$col];
        
        // process the metadata
        return $html;
    }
    
    /**
     * 
     * Available properties bold/italic/underline, #dddddd, 
     * 
     * @param mixed $col 
     * @param mixed $row 
     * @return  
     */
    protected function getCellStyle($col, $row)
    {
        $rowMetaData = $this->data[$row][0];
        $colMetaData = $this->data[0][$col];
        
        return $colMetaData . $rowMetaData;
    }
    
    
}