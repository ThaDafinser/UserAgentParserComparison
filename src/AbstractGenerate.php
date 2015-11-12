<?php
namespace UserAgentParserMatrix;

use UserAgentParser\Provider\AbstractProvider;

abstract class AbstractGenerate
{

    private $analyze;

    private $folder;

    private $total;

    public function setAnalyze(Analyze $analyze)
    {
        $this->analyze = $analyze;
    }

    /**
     *
     * @return Analyze
     */
    public function getAnalyze()
    {
        return $this->analyze;
    }

    /**
     *
     * @return AbstractProvider[]
     */
    protected function getProviders()
    {
        return $this->getAnalyze()
            ->getChainProvider()
            ->getProviders();
    }

    public function setFolder($folder)
    {
        $this->folder = $folder;
    }

    public function getFolder()
    {
        return $this->folder;
    }

    protected function getTotal()
    {
        if ($this->total !== null) {
            return $this->total;
        }
        
        $this->total = count($this->getAnalyze()->getUserAgents());
        
        return $this->total;
    }

    protected function getPercent($value, $total)
    {
        $onePercent = $total / 100;
        
        if ($onePercent == 0) {
            return 100;
        }
        
        return round($value / $onePercent, 0);
    }

    abstract protected function getConcreteHtml();

    protected function toHtml()
    {
        $htmlPart = $this->getConcreteHtml();
        
        $html = <<<END
<html>
    <head>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" />
    	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </head>
        
    <body>
        $htmlPart
    </body>
</html>
END;
        return $html;
    }
}