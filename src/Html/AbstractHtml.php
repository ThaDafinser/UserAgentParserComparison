<?php
namespace UserAgentParserComparison\Html;

abstract class AbstractHtml
{

    private $title;

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    protected function getUserAgentUrl($uaId)
    {
        $url = '/UserAgentParserComparison/v' . COMPARISON_VERSION . '/user-agent-detail/' . substr($uaId, 0, 2) . '/' . substr($uaId, 2, 2);
        $url .= '/' . $uaId . '.html';
        
        return $url;
    }

    protected function getHtmlCombined($body, $script = '')
    {
        return '
<html>
<head>
    <title>' . $this->getTitle() . '</title>
        
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
        
<body>
<div class="container">
    ' . $body . '
        
    <div class="section">
        <h1 class="header center orange-text">About this comparison</h1>
    
        <div class="row center">
            <h5 class="header light">
                The primary goal of this project is simple<br />
        
                I wanted to know which user agent parser is the most accurate in each part - device detection, bot detection and so on...<br />
                <br />
                The secondary goal is to provide a source for all user agent parsers to improve their detection based on this results.<br />
                <br />
                You can also improve this further, by suggesting ideas at <a href="https://github.com/ThaDafinser/UserAgentParserComparison">ThaDafinser/UserAgentParserComparison</a><br />
                <br />
                The comparison is based on the abstraction by <a href="https://github.com/ThaDafinser/UserAgentParser">ThaDafinser/UserAgentParser</a>
            </h5>
        </div>
            
    </div>
        
    <div class="card">
		<div class="card-content">
			Comparison created <i>' . date('Y-m-d H:i:s') . '</i> | by 
            <a href="https://github.com/ThaDafinser">ThaDafinser</a>
        </div>
    </div>
			    
</div>
			    
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js"></script>
        
    <script>
    ' . $script . '
    </script>
        
</body>
</html>';
    }

    abstract public function getHtml();
}
