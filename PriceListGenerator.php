<?php


class PriceListGenerator {
    
    protected $data = [];
    
    /**
     * 
     * @param mixed $data 
     * @return PriceListGenerator
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
        /* @var PriceListGenerator */
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
        $rowMetaData = $this->data[$row][0];
        $colMetaData = $this->data[0][$col];
        
        // process the metadata
        return $this->data[$row][$col];
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
        
        // process the metadata
        $styles = [];

        // column meta data
        if (!empty($colMetaData)) {
            $props = explode(',', $colMetaData);
            foreach ($props as $prop) {
                if ($prop[0] == '#') {
                    $styles['color'] = $prop;
                } elseif (preg_match('/^bg#([0-9A-Za-z]{3,6})/i', $prop, $match)) {
                    $styles[] = 'background:'.preg_replace('/^bg/i', '', $prop);
                } elseif ($prop == 'italic') {
                    $styles[] = 'font-style:'.$prop;
                } elseif ($prop == 'bold') {
                    $styles[] = 'font-weight:'.$prop;
                } elseif ($prop == 'underline') {
                    $styles[] = 'text-decoration:'.$prop;
                }
            }
        }
        
        // row meta data
        if (!empty($rowMetaData)) {
            $props = explode(',', $rowMetaData);
            foreach ($props as $prop) {
                if ($prop[0] == '#') {
                    $styles['color'] = $prop;
                } elseif (preg_match('/^bg#([0-9A-Za-z]{3,6})/i', $prop, $match)) {
                    $styles[] = 'background:'.preg_replace('/^bg/i', '', $prop);
                } elseif ($prop == 'italic') {
                    $styles[] = 'font-style:'.$prop;
                } elseif ($prop == 'bold') {
                    $styles[] = 'font-weight:'.$prop;
                } elseif ($prop == 'underline') {
                    $styles[] = 'text-decoration:'.$prop;
                }
            }
        }
        return implode(';', $styles);
    }
    
    
}