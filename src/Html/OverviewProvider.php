<?php
namespace UserAgentParserComparison\Html;

use Doctrine\ORM\EntityManager;
use UserAgentParserComparison\Entity\Provider;

class OverviewProvider extends AbstractHtml
{

    private $em;

    private $provider;

    private $userAgentCount;

    public function __construct(EntityManager $em, Provider $provider)
    {
        $this->em = $em;
        $this->provider = $provider;
    }

    /**
     *
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->em;
    }

    /**
     *
     * @return Provider
     */
    private function getProvider()
    {
        return $this->provider;
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

    private function getResult()
    {
        $sql = "
            SELECT
            	SUM(resResultFound) as resultFound,
    
            	COUNT(resBrowserName) as browserFound,
            	COUNT(resEngineName) as engineFound,
            	COUNT(resOsName) as osFound,
    
            	COUNT(resDeviceModel) as deviceModelFound,
            	COUNT(resDeviceBrand) as deviceBrandFound,
            	COUNT(resDeviceType) as deviceTypeFound,
            	COUNT(resDeviceIsMobile) as asMobileDetected,
    
            	COUNT(resBotIsBot) as asBotDetected,
                COUNT(resBotName) as botNameFound,
                COUNT(resBotType) as botTypeFound,
    
            	AVG(resParseTime) as avgParseTime
            FROM result
            JOIN provider on proId = provider_id
            WHERE
                provider_id = '" . $this->getProvider()->id . "'
            GROUP BY
            	proId
        ";
        
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->fetchAll($sql);
        
        return $result[0];
    }

    private function getTable()
    {
        $provider = $this->getProvider();
        
        $html = '<table class="striped">';
        
        /*
         * Header
         */
        $html .= '
            <tr>
                <th>
                    Group
                </th>
                <th>
                    Percent
                </th>
                <th>
                    Total
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
        
        $row = $this->getResult();
        
        $html .= '<tbdoy>';
        
        /*
         * Results found
         */
        $html .= '
            <tr>
            <td>
                Results found
            </td>
            <td>
                ' . round($row['resultFound'] / $totalUserAgentsOnePercent, 2) . '%
                <div class="progress">
					<div class="determinate" style="width: ' . round($row['resultFound'] / $totalUserAgentsOnePercent, 0) . '"></div>
				</div>
			</td>
		    <td>
                ' . $row['resultFound'] . '
            </td>
            <td>
                <a href="not-detected/' . $provider->name . '/no-result-found.html" class="btn waves-effect waves-light">
                    Not detected
                </a>
            </td>
            </tr>
        ';
        
        /*
         * browser
         */
        if ($provider->canDetectBrowserName === true) {
            $html .= '
                <tr>
                <td>
                    Browser names
                </td>
                <td>
                    ' . round($row['browserFound'] / $totalUserAgentsOnePercent, 2) . '%
                    <div class="progress">
    					<div class="determinate" style="width: ' . round($row['browserFound'] / $totalUserAgentsOnePercent, 0) . '"></div>
    				</div>
    			</td>
    		    <td>
                    ' . $row['browserFound'] . '
                </td>
                <td>
                    <a href="detected/' . $provider->name . '/browser-names.html" class="btn waves-effect waves-light">
                        Detected
                    </a>
                    <a href="not-detected/' . $provider->name . '/browser-names.html" class="btn waves-effect waves-light">
                        Not detected
                    </a>
                </td>
                </tr>
            ';
        } else {
            $html .= '
                <tr>
                <td>
                    Engine names
                </td>
                <td colspan="3" class="center-align red lighten-1">
                    <strong>Not available with this provider</strong>
                </td>
                </tr>
            ';
        }
        
        /*
         * engine
         */
        if ($provider->canDetectEngineName === true) {
            $html .= '
                <tr>
                <td>
                    Rendering engines
                </td>
                <td>
                    ' . round($row['engineFound'] / $totalUserAgentsOnePercent, 2) . '%
                    <div class="progress">
    					<div class="determinate" style="width: ' . round($row['engineFound'] / $totalUserAgentsOnePercent, 0) . '"></div>
    				</div>
    			</td>
    		    <td>
                    ' . $row['engineFound'] . '
                </td>
                <td>
                    <a href="detected/' . $provider->name . '/rendering-engines.html" class="btn waves-effect waves-light">
                        Detected
                    </a>
                    <a href="not-detected/' . $provider->name . '/rendering-engines.html" class="btn waves-effect waves-light">
                        Not detected
                    </a>
                </td>
                </tr>
            ';
        } else {
            $html .= '
                <tr>
                <td>
                    Engine name
                </td>
                <td colspan="3" class="center-align red lighten-1">
                    <strong>Not available with this provider</strong>
                </td>
                </tr>
            ';
        }
        
        /*
         * os
         */
        if ($provider->canDetectOsName === true) {
            $html .= '
                <tr>
                <td>
                    Operating systems
                </td>
                <td>
                    ' . round($row['osFound'] / $totalUserAgentsOnePercent, 2) . '%
                    <div class="progress">
    					<div class="determinate" style="width: ' . round($row['osFound'] / $totalUserAgentsOnePercent, 0) . '"></div>
    				</div>
    			</td>
    		    <td>
                    ' . $row['osFound'] . '
                </td>
                <td>
                    <a href="detected/' . $provider->name . '/operating-systems.html" class="btn waves-effect waves-light">
                        Detected
                    </a>
                    <a href="not-detected/' . $provider->name . '/operating-systems.html" class="btn waves-effect waves-light">
                        Not detected
                    </a>
                </td>
                </tr>
            ';
        } else {
            $html .= '
                <tr>
                <td>
                    Operating systems
                </td>
                <td colspan="3" class="center-align red lighten-1">
                    <strong>Not available with this provider</strong>
                </td>
                </tr>
            ';
        }
        
        /*
         * device brand
         */
        if ($provider->canDetectDeviceBrand === true) {
            $html .= '
                <tr>
                <td>
                    Device brands
                </td>
                <td>
                    ' . round($row['deviceBrandFound'] / $totalUserAgentsOnePercent, 2) . '%
                    <div class="progress">
    					<div class="determinate" style="width: ' . round($row['deviceBrandFound'] / $totalUserAgentsOnePercent, 0) . '"></div>
    				</div>
    			</td>
    		    <td>
                    ' . $row['deviceBrandFound'] . '
                </td>
                <td>
                    <a href="detected/' . $provider->name . '/device-brands.html" class="btn waves-effect waves-light">
                        Detected
                    </a>
                    <a href="not-detected/' . $provider->name . '/device-brands.html" class="btn waves-effect waves-light">
                        Not detected
                    </a>
                </td>
                </tr>
            ';
        } else {
            $html .= '
                <tr>
                <td>
                    Device brands
                </td>
                <td colspan="3" class="center-align red lighten-1">
                    <strong>Not available with this provider</strong>
                </td>
                </tr>
            ';
        }
        
        /*
         * device model
         */
        if ($provider->canDetectDeviceModel === true) {
            $html .= '
                <tr>
                <td>
                    Device brands
                </td>
                <td>
                    ' . round($row['deviceModelFound'] / $totalUserAgentsOnePercent, 2) . '%
                    <div class="progress">
    					<div class="determinate" style="width: ' . round($row['deviceModelFound'] / $totalUserAgentsOnePercent, 0) . '"></div>
    				</div>
    			</td>
    		    <td>
                    ' . $row['deviceModelFound'] . '
                </td>
                <td>
                    <a href="detected/' . $provider->name . '/device-models.html" class="btn waves-effect waves-light">
                        Detected
                    </a>
                    <a href="not-detected/' . $provider->name . '/device-models.html" class="btn waves-effect waves-light">
                        Not detected
                    </a>
                </td>
                </tr>
            ';
        } else {
            $html .= '
                <tr>
                <td>
                    Device models
                </td>
                <td colspan="3" class="center-align red lighten-1">
                    <strong>Not available with this provider</strong>
                </td>
                </tr>
            ';
        }
        
        /*
         * device type
         */
        if ($provider->canDetectDeviceType === true) {
            $html .= '
                <tr>
                <td>
                    Device types
                </td>
                <td>
                    ' . round($row['deviceTypeFound'] / $totalUserAgentsOnePercent, 2) . '%
                    <div class="progress">
    					<div class="determinate" style="width: ' . round($row['deviceTypeFound'] / $totalUserAgentsOnePercent, 0) . '"></div>
    				</div>
    			</td>
    		    <td>
                    ' . $row['deviceTypeFound'] . '
                </td>
                <td>
                    <a href="detected/' . $provider->name . '/device-types.html" class="btn waves-effect waves-light">
                        Detected
                    </a>
                    <a href="not-detected/' . $provider->name . '/device-types.html" class="btn waves-effect waves-light">
                        Not detected
                    </a>
                </td>
                </tr>
            ';
        } else {
            $html .= '
                <tr>
                <td>
                    Device types
                </td>
                <td colspan="3" class="center-align red lighten-1">
                    <strong>Not available with this provider</strong>
                </td>
                </tr>
            ';
        }
        
        /*
         * Is mobile
         */
        if ($provider->canDetectDeviceIsMobile === true) {
            $html .= '
                <tr>
                <td>
                    Is mobile
                </td>
                <td>
                    ' . round($row['asMobileDetected'] / $totalUserAgentsOnePercent, 2) . '%
                    <div class="progress">
    					<div class="determinate" style="width: ' . round($row['asMobileDetected'] / $totalUserAgentsOnePercent, 0) . '"></div>
    				</div>
    			</td>
    		    <td>
                    ' . $row['asMobileDetected'] . '
                </td>
                <td>
                    <a href="not-detected/' . $provider->name . '/device-is-mobile.html" class="btn waves-effect waves-light">
                        Not detected
                    </a>
                </td>
                </tr>
            ';
        } else {
            $html .= '
                <tr>
                <td>
                    Is mobile
                </td>
                <td colspan="3" class="center-align red lighten-1">
                    <strong>Not available with this provider</strong>
                </td>
                </tr>
            ';
        }
        
        /*
         * Is bot
         */
        if ($provider->canDetectBotIsBot === true) {
            $html .= '
                <tr>
                <td>
                    Is bot
                </td>
                <td>
                    
    			</td>
    		    <td>
                    ' . $row['asBotDetected'] . '
                </td>
                <td>
                    <a href="detected/' . $provider->name . '/bot-is-bot.html" class="btn waves-effect waves-light">
                        Detected
                    </a>
                    <a href="not-detected/' . $provider->name . '/bot-is-bot.html" class="btn waves-effect waves-light">
                        Not detected
                    </a>
                </td>
                </tr>
            ';
        } else {
            $html .= '
                <tr>
                <td>
                    Is bot
                </td>
                <td colspan="3" class="center-align red lighten-1">
                    <strong>Not available with this provider</strong>
                </td>
                </tr>
            ';
        }
        
        /*
         * Bot name
         */
        if ($provider->canDetectBotName === true) {
            $html .= '
                <tr>
                <td>
                    Bot names
                </td>
                <td>
    			</td>
    		    <td>
                    ' . $row['botNameFound'] . '
                </td>
                <td>
                    <a href="detected/' . $provider->name . '/bot-names.html" class="btn waves-effect waves-light">
                        Detected
                    </a>
                    <a href="not-detected/' . $provider->name . '/bot-names.html" class="btn waves-effect waves-light">
                        Not detected
                    </a>
                </td>
                </tr>
            ';
        } else {
            $html .= '
                <tr>
                <td>
                    Bot names
                </td>
                <td colspan="3" class="center-align red lighten-1">
                    <strong>Not available with this provider</strong>
                </td>
                </tr>
            ';
        }
        
        /*
         * Bot type
         */
        if ($provider->canDetectBotType === true) {
            $html .= '
                <tr>
                <td>
                    Bot types
                </td>
                <td>
    			</td>
    		    <td>
                    ' . $row['botTypeFound'] . '
                </td>
                <td>
                    <a href="detected/' . $provider->name . '/bot-types.html" class="btn waves-effect waves-light">
                        Detected
                    </a>
                    <a href="not-detected/' . $provider->name . '/bot-types.html" class="btn waves-effect waves-light">
                        Not detected
                    </a>
                </td>
                </tr>
            ';
        } else {
            $html .= '
                <tr>
                <td>
                    Bot types
                </td>
                <td colspan="3" class="center-align red lighten-1">
                    <strong>Not available with this provider</strong>
                </td>
                </tr>
            ';
        }
        
        $html .= '</tbdoy>';
        
        $html .= '</table>';
        
        return $html;
    }

    public function getHtml()
    {
        $body = '
<div class="section">
    <h1 class="header center orange-text">' . $this->getProvider()->name . ' overview - <small>' . $this->getProvider()->version . '</small></h1>

    <div class="row center">
        <h5 class="header light">
            We took <strong>' . $this->getUserAgentCount() . '</strong> different user agents and analyzed them with this provider<br />
        </h5>
    </div>
</div>

<div class="section">
    ' . $this->getTable() . '
</div>
';
        
        return parent::getHtmlCombined($body);
    }
}
