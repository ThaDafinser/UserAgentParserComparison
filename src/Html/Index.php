<?php
namespace UserAgentParserComparison\Html;

class Index extends AbstractHtml
{

    private function getButtons()
    {
        $html = '';
        
        for ($i = COMPARISON_VERSION; $i > 0; $i --) {
            $txt = 'Version ' . $i;
            if ($i === COMPARISON_VERSION) {
                $txt .= ' (latest)';
            }
            
            $html .= '
                <a class="modal-trigger btn waves-effect waves-light" 
                    href="/UserAgentParserComparison/v' . $i . '/index.html">
                    ' . $txt . '
                </a><br /><br />
            ';
        }
        
        return $html;
    }

    public function getHtml()
    {
        $body = '
<div class="section">
    <h1 class="header center orange-text">UserAgentParser comparison</h1>

    <div class="row center">
        <p>
            See the comparison of different user agent parsers
        </p>
        
        ' . $this->getButtons() . '
            
        by Martin Keckeis (@ThaDafinser)
    </div>
</div>
';
        
        return parent::getHtmlCombined($body);
    }
}
