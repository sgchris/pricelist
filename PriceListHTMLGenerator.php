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
        $this->hash = uniqid('pl_');
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

        if (!window._fixPricelistHeights) {
            window._fixPricelistHeights = function(uniqueHash) {
                
                // set the col widths
                var i, maxHeight, 
                    numOfCols = $('#'+uniqueHash+' .pl-col').length;

                var colWidth = 100.0 / numOfCols;
                // adjust col width by screen size
                if (window.outerWidth < 980 && colWidth < 32) {
                    colWidth = 33.3; // max three cols
                }
                if (window.outerWidth < 600 && colWidth < 49) {
                    colWidth = 50; // max 2 cols
                }
                

                $('#'+uniqueHash+' .pl-col')
                    .css('width', (colWidth - 1) + '%')
                    .css('margin-left', '1%');
                
                // set cells heights
                $('#'+uniqueHash+' .pl-col:eq(0) .pl-cell').each(function(rowNum, col) {
                    maxHeight = 0;
                    for (i=0; i<numOfCols; ++i) {
                        // rowNum's cell in i's column
                        var targetCell = $('#'+uniqueHash+' .pl-col:eq('+i+') .pl-cell:eq('+rowNum+')');
                        if (targetCell.get(0).offsetHeight > maxHeight) {
                            maxHeight = targetCell.get(0).offsetHeight;
                        }
                    }
                    
                    for (i=0; i<numOfCols; ++i) {
                        // rowNum's cell in i's column
                        var targetCell = $('#'+uniqueHash+' .pl-col:eq('+i+') .pl-cell:eq('+rowNum+')');
                        targetCell.css('height', (maxHeight) + 'px');
                    }
                });
            };
        }

        var _resizeTimer_{$this->hash} = null;
        $(window).on('load',function() {
            window._fixPricelistHeights('{$this->hash}');
        }).on('resize',function() {
            clearTimeout(_resizeTimer_{$this->hash});
            _resizeTimer_{$this->hash} = setTimeout(function() {
                window._fixPricelistHeights('{$this->hash}');
            }, 500);
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
        
        $html.= '<div class="pl-clearfix"></div>'.PHP_EOL;
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