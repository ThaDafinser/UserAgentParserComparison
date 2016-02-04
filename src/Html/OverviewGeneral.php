<?php
namespace UserAgentParserComparison\Html;

use Doctrine\ORM\EntityManager;

class OverviewGeneral extends AbstractHtml
{

    private $em;

    private $userAgentCount;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     *
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->em;
    }

    private function getUserAgentCount()
    {
        if ($this->userAgentCount === null) {
            $sql = "
                SELECT
                    COUNT(1) getThis
                FROM userAgent
            ";
            
            $conn = $this->getEntityManager()->getConnection();
            $result = $conn->fetchAll($sql);
            
            $this->userAgentCount = $result[0]['getThis'];
        }
        
        return $this->userAgentCount;
    }

    private function getProviders()
    {
        $sql = "
            SELECT
            	provider.*,
            
            	SUM(resResultFound) as resultFound,
            
            	COUNT(resBrowserName) as browserFound,
            	COUNT(resEngineName) as engineFound,
            	COUNT(resOsName) as osFound,
            
            	COUNT(resDeviceModel) as deviceModelFound,
            	COUNT(resDeviceBrand) as deviceBrandFound,
            	COUNT(resDeviceType) as deviceTypeFound,
            	COUNT(resDeviceIsMobile) as asMobileDetected,
            
            	COUNT(resBotIsBot) as asBotDetected,
            
            	AVG(resParseTime) as avgParseTime
            FROM result
            JOIN provider on proId = provider_id
            GROUP BY
            	proId
            ORDER BY 
            	proName
        ";
        
        $conn = $this->getEntityManager()->getConnection();
        
        return $conn->fetchAll($sql);
    }

    private function getTable()
    {
        $html = '<table class="striped">';
        
        /*
         * Header
         */
        $html .= '
            <tr>
                <th>
                    Provider
                </th>
                <th>
                    Results
                </th>
                <th>
                    Browser
                </th>
               <th>
                    Engine
                </th>
               <th>
                    Operating system
                </th>
               <th>
                    Device brand
                </th>
                <th>
                    Device model
                </th>
                <th>
                    Device type
                </th>
               <th>
                    Is mobile
                </th>
               <th>
                    Is bot
                </th>
               <th>
                    Parse time
                </th>
                <th>
                    Actions
                </th>
            </tr>
        ';
        
        /*
         * body
         */
        $totalUserAgentsOnePercent = $this->getUserAgentCount() / 100;
        
        $html .= '<tbdoy>';
        foreach ($this->getProviders() as $row) {
            
//             var_dump($row);
//             exit();
            
            $html .= '<tr>';
            
            $html .= '<th><a href="https://packagist.org/packages/' . $row['proPackageName'] . '">' . $row['proName'] . '</a>';
            $html .= '<br /><small>' . $row['proVersion'] . '</small></th>';
            
            /*
             * Result found?
             */
            $html .= '
                <td>
                    ' . round($row['resultFound'] / $totalUserAgentsOnePercent, 2) . '%	    
                    <div class="progress">
						<div class="determinate" style="width: ' . round($row['resultFound'] / $totalUserAgentsOnePercent, 0) . '"></div>
					</div>
				</td>
            ';
            
            $html .= '
                <td>
                    ' . round($row['browserFound'] / $totalUserAgentsOnePercent, 2) . '%
                    <div class="progress">
						<div class="determinate" style="width: ' . round($row['browserFound'] / $totalUserAgentsOnePercent, 0) . '"></div>
					</div>
				</td>
            ';
            
            if($row['proCanDetectEngineName'] == 1){
                $html .= '
                    <td>
                        ' . round($row['engineFound'] / $totalUserAgentsOnePercent, 2) . '%
                        <div class="progress">
    						<div class="determinate" style="width: ' . round($row['engineFound'] / $totalUserAgentsOnePercent, 0) . '"></div>
    					</div>
    				</td>
                ';
            } else {
                $html .= '
                    <td>
                        <i class="material-icons">close</i>
                    </td>
                ';
            }
            
            /*
             * OS
             */
            if($row['proCanDetectOsName'] == 1){
                $html .= '
                    <td>
                        ' . round($row['osFound'] / $totalUserAgentsOnePercent, 2) . '%
                        <div class="progress">
    						<div class="determinate" style="width: ' . round($row['osFound'] / $totalUserAgentsOnePercent, 0) . '"></div>
    					</div>
    				</td>
                ';
            } else {
                $html .= '
                    <td>
                        <i class="material-icons">close</i>
                    </td>
                ';
            }
            
            
            /*
             * device
             */
            if($row['proCanDetectDeviceBrand'] == 1){
                $html .= '
                    <td>
                        ' . round($row['deviceBrandFound'] / $totalUserAgentsOnePercent, 2) . '%
                        <div class="progress">
    						<div class="determinate" style="width: ' . round($row['deviceBrandFound'] / $totalUserAgentsOnePercent, 0) . '"></div>
    					</div>
    				</td>
                ';
            } else {
                $html .= '
                    <td>
                        <i class="material-icons">close</i>
                    </td>
                ';
            }
            
            if($row['proCanDetectDeviceModel'] == 1){
                $html .= '
                    <td>
                        ' . round($row['deviceModelFound'] / $totalUserAgentsOnePercent, 2) . '%
                        <div class="progress">
    						<div class="determinate" style="width: ' . round($row['deviceModelFound'] / $totalUserAgentsOnePercent, 0) . '"></div>
    					</div>
    				</td>
                ';
            } else {
                $html .= '
                    <td>
                        <i class="material-icons">close</i>
                    </td>
                ';
            }
            
            if($row['proCanDetectDeviceType'] == 1){
                $html .= '
                    <td>
                        ' . round($row['deviceTypeFound'] / $totalUserAgentsOnePercent, 2) . '%
                        <div class="progress">
    						<div class="determinate" style="width: ' . round($row['deviceTypeFound'] / $totalUserAgentsOnePercent, 0) . '"></div>
    					</div>
    				</td>
                ';
            } else {
                $html .= '
                    <td>
                        <i class="material-icons">close</i>
                    </td>
                ';
            }
            
            if($row['proCanDetectDeviceIsMobile'] == 1){
                $html .= '
                    <td>
                        ' . round($row['asMobileDetected'] / $totalUserAgentsOnePercent, 2) . '%
                        <div class="progress">
    						<div class="determinate" style="width: ' . round($row['asMobileDetected'] / $totalUserAgentsOnePercent, 0) . '"></div>
    					</div>
    				</td>
                ';
            } else {
                $html .= '
                    <td>
                        <i class="material-icons">close</i>
                    </td>
                ';
            }
            
            if($row['proCanDetectBotIsBot'] == 1){
                $html .= '
                    <td>
                        ' . $row['asBotDetected'] . '
    				</td>
                ';
            } else {
                $html .= '
                    <td>
                        <i class="material-icons">close</i>
                    </td>
                ';
            }
            
            $html .= '
                <td>
                    ' . round($row['avgParseTime'], 5) . '
				</td>
            ';
            
            $html .= '<td><a href="' . $row['proName'] . '.html" class="btn waves-effect waves-light">Details</a></td>';
            
            $html .= '</tr>';
        }
        $html .= '</tbdoy>';
        
        $html .= '</table>';
        
        return $html;
    }

    public function getHtml()
    {
        $body = '
<div class="section">
    <h1 class="header center orange-text">Useragent parser comparison v' . COMPARISON_VERSION . '</h1>

    <div class="row center">
        <h5 class="header light">
            We took <strong>' . $this->getUserAgentCount() . '</strong> different user agents and analyzed them with all providers below.<br />
            That way, it\'s possible to get a real overview.
        </h5>
    </div>
</div>

<div class="section">
    ' . $this->getTable() . '
</div>
        
<div class="section center">
        
    <h2 class="header center orange-text">
        Detected by all providers
    </h2>
        
    <a href="detected/general/browser-names.html" class="btn waves-effect waves-light">
        Browser names    
    </a><br /><br />
        
    <a href="detected/general/rendering-engines.html" class="btn waves-effect waves-light">
        Rendering engines
    </a><br /><br />
        
    <a href="detected/general/operating-systems.html" class="btn waves-effect waves-light">
        Operating systems
    </a><br /><br />
        
    <a href="detected/general/device-brands.html" class="btn waves-effect waves-light">
        Device brands
    </a><br /><br />
        
    <a href="detected/general/device-models.html" class="btn waves-effect waves-light">
        Device models
    </a><br /><br />
        
    <a href="detected/general/device-types.html" class="btn waves-effect waves-light">
        Device types
    </a><br /><br />
        
    <a href="detected/general/bot-names.html" class="btn waves-effect waves-light">
        Bot names
    </a><br /><br />
        
    <a href="detected/general/bot-types.html" class="btn waves-effect waves-light">
        Bot types
    </a><br /><br />
        
</div>
';
        
        return parent::getHtmlCombined($body);
    }
}
