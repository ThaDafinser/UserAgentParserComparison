<?php
namespace UserAgentParserComparison;

use PDO;

class GenerateHtmlListSimple
{

    private $result = [];

    public function setResult(array $result)
    {
        $this->result = $result;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    private function getList()
    {
        $html = '';
        
        foreach ($this->getResult() as $row) {
            $html .= '<li class="collection-item">';
            $html .= '<h4 class="searchable">' . $row['name'] . '</h4>';
            $html .= '<strong>Example user agent</strong><br />';
            $html .= '<span class="userAgent">' . $row['userAgent'] . '</span>';
            $html .= '</li>';
        }
        
        return $html;
    }

    public function getHtml()
    {
        echo $this->getTitle() . PHP_EOL;
        
        $listElements = $this->getList();
        
        return '
<html>
<head>
    <title>' . $this->getTitle() . '</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.min.css">
</head>
    
<body>
    <div class="container">
    
	<div class="section no-pad-bot" id="index-banner">
		<h1 class="header center orange-text">' . $this->getTitle() . '</h1>
		<div class="row center">
            ' . count($this->getResult()) . ' results<br />
    
            <p>
			 <a href="../index.html" class="btn-large waves-effect waves-light">
                Back to the overview
            </a>
			</p>
		</div>
	</div>
    
    
    <div class="section" id="userAgent-list">
        <nav class="teal lighten-2">
        <div class="nav-wrapper">
          <form>
            <div class="input-field">
              <input class="search" type="search" placeholder="Search for a user agent">
              <i class="material-icons">close</i>
            </div>
          </form>
        </div>
        </nav>
    
        <ul class="list collection">
            ' . $listElements . '
        </ul>
    </div>
    </div>
    
    <script src="http://cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js"></script>
    <script>
    var options = {
        page: 10000,
        valueNames: [\'searchable\', \'userAgent\']
    };
    
    var hackerList = new List(\'userAgent-list\', options);
    
    </script>
</body>
</html>';
    }
}