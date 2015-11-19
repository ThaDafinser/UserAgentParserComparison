<?php
namespace UserAgentParserComparison;

use PDO;

class GenerateHtmlListV2
{

    private $subquery;

    private $result = [];

    private $providers = [];

    private $groupedResult = [];

    public function setSubquery($subquery)
    {
        $this->subquery = $subquery;
    }

    public function getSubquery()
    {
        return $this->subquery;
    }

    private function setResult(array $result)
    {
        $this->result = $result;
    }

    private function getResult()
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

    private function initResult()
    {
        $pdo = new PDO('sqlite:data/results.sqlite3');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "
            SELECT
            	*
            FROM userAgent
            JOIN vendorResult
            	ON userAgent_uaId = uaId
            WHERE uaId IN(" . $this->getSubquery() . ")
            ORDER BY
                userAgent.userAgent
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $this->setResult($stmt->fetchAll(PDO::FETCH_ASSOC));
        
        /*
         * Group result
         */
        foreach ($this->getResult() as $row) {
            // init the array
            if (! isset($this->groupedResult[$row['uaId']])) {
                $this->groupedResult[$row['uaId']] = [
                    'userAgent' => [
                        'name' => $row['userAgent'],
                        'source' => $row['source'],
                        'group' => $row['group']
                    ],
                    
                    'providers' => []
                ];
            }
            
            if (! isset($this->providers[$row['providerName']])) {
                $this->providers[$row['providerName']] = $row['providerName'];
            }
            
            $this->groupedResult[$row['uaId']]['providers'][$row['providerName']] = $row;
        }
    }

    /**
     *
     * @return array
     */
    private function getProviders()
    {
        return $this->providers;
    }

    /**
     *
     * @return array
     */
    private function getGroupedResult()
    {
        return $this->groupedResult;
    }

    private function getList()
    {
        $this->initResult();
        
        echo $this->getTitle() . PHP_EOL;
        
        $html = '';
        foreach ($this->getGroupedResult() as $row) {
            $html .= '
                <li class="">
                    <div class="card darken-1">
                    <div class="card-content">
            ';
            $html .= '<span class="card-title userAgent">' . $row['userAgent']['name'] . '</span>';
            
            $html .= '<p>' . $this->getProvidersTable($row) . '</p>';
            
            $html .= '
                    </div>
                </li>
            ';
        }
        
        return $html;
    }

    private function getProvidersTable(array $row)
    {
        $html = '<table class="bordered">';
        
        $colspan = count($this->getProviders());
        $colspan ++;
        
        $html .= '<tr>';
        $html .= '<th></th>';
        
        foreach ($this->getProviders() as $providerName) {
            $html .= '<th>';
            $html .= $providerName;
            $html .= '</th>';
        }
        $html .= '</tr>';
        
        /*
         * Browser
         */
        $html .= '<tr>';
        $html.='<th>Browser</th>';
        
        foreach ($this->getProviders() as $providerName) {
            $providerRow = $row['providers'][$providerName];
            
            
            $html .= '<td>';
            
            $html .= $providerRow['browserName'];
            if ($providerRow['browserVersion'] != '') {
                $html .= ' / ' . $providerRow['browserVersion'];
            }
            
            $html .= '</td>';
        }
        $html .= '</tr>';
        
        /*
         * Rendering engine
         */
        $html .= '<tr>';
        $html.='<th>Engine</th>';
        
        foreach ($this->getProviders() as $providerName) {
            $providerRow = $row['providers'][$providerName];
            
            $html .= '<td>';
            
            $html .= $providerRow['engineName'];
            if ($providerRow['engineVersion'] != '') {
                $html .= ' / ' . $providerRow['engineVersion'];
            }
            
            $html .= '</td>';
        }
        $html .= '</tr>';
        
        /*
         * OS
         */
        $html .= '<tr>';
        $html.='<th>Operating system</th>';
        
        foreach ($this->getProviders() as $providerName) {
            $providerRow = $row['providers'][$providerName];
            
            $html .= '<td>';
            
            $html .= $providerRow['osName'];
            if ($providerRow['osVersion'] != '') {
                $html .= ' / ' . $providerRow['osVersion'];
            }
            
            $html .= '</td>';
        }
        $html .= '</tr>';
        
        /*
         * Device
         */
        $html .= '<tr>';
        $html.='<th colspan="'.$colspan .'" class="grey lighten-2">Device</th>';
        $html.='</tr>';
        
        $html .= '<tr>';
        $html.='<th>Model</th>';
        foreach ($this->getProviders() as $providerName) {
            $providerRow = $row['providers'][$providerName];
        
            $html .= '<td>';
        
            $html .= $providerRow['deviceModel'];
            
            $html .= '</td>';
        }
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html.='<th>Brand</th>';
        foreach ($this->getProviders() as $providerName) {
            $providerRow = $row['providers'][$providerName];
        
            $html .= '<td>';
        
            $html .= $providerRow['deviceBrand'];
        
            $html .= '</td>';
        }
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html.='<th>Type</th>';
        foreach ($this->getProviders() as $providerName) {
            $providerRow = $row['providers'][$providerName];
        
            $html .= '<td>';
        
            $html .= $providerRow['deviceType'];
        
            $html .= '</td>';
        }
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html.='<th>Is mobile</th>';
        foreach ($this->getProviders() as $providerName) {
            $providerRow = $row['providers'][$providerName];
        
            $html .= '<td>';
        
            if($providerRow['deviceIsMobile'] == 1){
                $html.='yes';
            }
        
            $html .= '</td>';
        }
        $html .= '</tr>';
        
        
        
        /*
         * Bot
         */
        $html .= '<tr>';
        $html.='<th colspan="'.$colspan .'" class="grey lighten-2">Bot</th>';
        $html.='</tr>';
        
        $html .= '<tr>';
        $html.='<th>Is bot</th>';
        foreach ($this->getProviders() as $providerName) {
            $providerRow = $row['providers'][$providerName];
        
            $html .= '<td>';
        
            if($providerRow['botIsBot'] == 1){
                $html.='yes';   
            }
        
            $html .= '</td>';
        }
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html.='<th>Name</th>';
        foreach ($this->getProviders() as $providerName) {
            $providerRow = $row['providers'][$providerName];
        
            $html .= '<td>';
        
            $html .= $providerRow['botName'];
        
            $html .= '</td>';
        }
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html.='<th>Type</th>';
        foreach ($this->getProviders() as $providerName) {
            $providerRow = $row['providers'][$providerName];
        
            $html .= '<td>';
        
            $html .= $providerRow['botType'];
        
            $html .= '</td>';
        }
        $html .= '</tr>';
        
        $html .= '</table>';
        
        return $html;
    }

    public function getHtml()
    {
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
            ' . count($this->getGroupedResult()) . ' results<br />
                
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
    
        <ul class="list">
            ' . $listElements . '
        </ul>
    </div>
    </div>
        
    <script src="http://cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js"></script>
    <script>
    var options = {
        valueNames: [\'userAgent\']
    };
    
    var hackerList = new List(\'userAgent-list\', options);
    
    </script>
</body>
</html>';
    }
}
