<?php
namespace UserAgentParserComparison\Html;

class SimpleList extends AbstractHtml
{

    private $elements = [];

    public function setElements(array $elements)
    {
        $this->elements = $elements;
    }

    public function getElements()
    {
        return $this->elements;
    }

    private function getList()
    {
        $html = '<ul class="list collection">';
        
        foreach ($this->getElements() as $element) {
            
            $html .= '<li class="collection-item">';
            
            $html .= '<h4 class="searchable">' . $element['name'];
            
            /*
             * Optional
             */
            if (isset($element['detectionCount'])) {
                $html .= ' <small>' . $element['detectionCount'] . 'x detected</small>';
            }
            if (isset($element['detectionCountUnique'])) {
                $html .= ' <small>(' . $element['detectionCountUnique'] . 'x unique)</small>';
            }
            if (isset($element['detectionValuesDistinct'])) {
                $html .= '<br /><small>' . $element['detectionValuesDistinct'] . '</small>';
            }
            
            $html .= '</h4>';
            
            $html .= '<strong>Example user agent</strong><br />';
            
            $html .= '<span class="userAgent">';
            $html .= '<a href="' . $this->getUserAgentUrl($element['uaId']) . '">' . $element['uaString'] . '</a>';
            $html .= '</span>';
            
            $html .= '</li>';
            
        }
        
        $html .= '</ul>';
        
        return $html;
    }

    public function getHtml()
    {
        $body = '
<div class="section">
    <h1 class="header center orange-text">' . $this->getTitle() . '</h1>
        
    <div class="row center">
        ' . count($this->getElements()) . ' result(s) found
    </div>
</div>
        
<div class="section" id="simple-list">
    <form>
        <div class="input-field">
          <input class="search" type="search" placeholder="Search for a user agent">
          <i class="material-icons">close</i>
        </div>
      </form>
            
    ' . $this->getList() . '
</div>
';
        
        $script = '
var options = {
    page: 10000,
    valueNames: [\'searchable\']
};

var hackerList = new List(\'simple-list\', options);    
';
        
        return parent::getHtmlCombined($body, $script);
    }
}
