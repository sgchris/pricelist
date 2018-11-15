<?php


class PriceListHTMLGenerator {
    
    /* @var array */
    protected $data = [];
    
    /* @var string */
    protected $hash = '';
    
    /**
     * 
     * @param mixed $data 
     * @return PriceListHTMLGenerator
     */
    public function __construct($data = [])
    {
        $this->data = $data;
        $this->hash = uniqid('pl_');//sha1(microtime(true));
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
     * Get the JS related to the current table
     * 
     * @return string
     */
    public function getJavascript() 
    {
        $js = <<<JS
        <script>
        $(window).on('load',function() {
            // set the col widths
            var i, maxHeight, 
                numOfCols = $('#{$this->hash} .pl-col').length;
            $('#{$this->hash} .pl-col')
                .css('width', ((100.0 / numOfCols) - 1) + '%')
                .css('margin-left', '1%');
            
            // set cells heights
            $('#{$this->hash} .pl-col:eq(0) .pl-cell').each(function(rowNum, col) {
                maxHeight = 0;
                for (i=0; i<numOfCols; ++i) {
                    // rowNum's cell in i's column
                    var targetCell = $('#{$this->hash} .pl-col:eq('+i+') .pl-cell:eq('+rowNum+')');
                    if (targetCell.get(0).clientHeight > maxHeight) {
                        maxHeight = targetCell.get(0).clientHeight;
                    }
                }
                
                for (i=0; i<numOfCols; ++i) {
                    // rowNum's cell in i's column
                    var targetCell = $('#{$this->hash} .pl-col:eq('+i+') .pl-cell:eq('+rowNum+')');
                    targetCell.css('height', maxHeight + 'px');
                }
            });
        });
        </script>
JS;
        return $js;
        
    }
    
    
    /**
     * 
     * @return string
     */
    public function getHtml()
    {
        if (empty($this->data) || !isset($this->data[0]) || !is_array($this->data[0])) {
            return '<!-- price list data is not valid -->';
        }
        
        // start the HTML
        $html = '<div class="pl-wrapper" id="'.($this->hash).'">'.PHP_EOL;
        
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
        
        $html.= $this->getJavascript().PHP_EOL;
        
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
        
        // replace images
        $html = preg_replace('/\[([^\]]+?)\.(jpg|gif|png)\]/i', '<img src="$1.$2" />', $html);
        
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
        
        return $colMetaData .';'. $rowMetaData;
    }
    
    
}