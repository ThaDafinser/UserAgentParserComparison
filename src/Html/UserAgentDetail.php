<?php
namespace UserAgentParserComparison\Html;

use UserAgentParserComparison\Entity\UserAgentEvaluation;
use UserAgentParserComparison\Entity\Result;
use UserAgentParserComparison\Entity\UserAgent;

class UserAgentDetail extends AbstractHtml
{

    /**
     *
     * @var UserAgent
     */
    private $userAgent;

    /**
     *
     * @var Result[]
     */
    private $results = [];

    public function setUserAgent(UserAgent $userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     *
     * @return \UserAgentParserComparison\Entity\UserAgent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     *
     * @param array $results            
     */
    public function setResults(array $results)
    {
        $this->results = $results;
    }

    /**
     *
     * @return Result[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     *
     * @return \UserAgentParserComparison\Entity\Provider[]
     */
    private function getProviders()
    {
        $providers = [];
        
        foreach ($this->getResults() as $result) {
            $provider = $result->getProvider();
            
            $providers[$provider->name] = $provider;
        }
        
        return $providers;
    }

    private function getProviderCapabilityCount($type)
    {
        $property = 'canDetect' . ucfirst($type);
        
        if (isset($this->{$property})) {
            return $this->{$property};
        }
        
        $canDetect = 0;
        foreach ($this->getProviders() as $provider) {
            if ($provider->{$property} === true) {
                $canDetect ++;
            }
        }
        
        $this->{$property} = $canDetect;
        
        return $canDetect;
    }

    private function getProvidersTable()
    {
        $html = '<table class="striped">';
        
        $html .= '<tr>';
        $html .= '<th></th>';
        $html .= '<th colspan="3">General</th>';
        $html .= '<th colspan="5">Device</th>';
        $html .= '<th colspan="3">Bot</th>';
        $html .= '<th colspan="2"></th>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<th>Provider</th>';
        $html .= '<th>Browser</th>';
        $html .= '<th>Engine</th>';
        $html .= '<th>OS</th>';
        
        $html .= '<th>Brand</th>';
        $html .= '<th>Model</th>';
        $html .= '<th>Type</th>';
        $html .= '<th>Is mobile</th>';
        $html .= '<th>Is touch</th>';
        
        $html .= '<th>Is bot</th>';
        $html .= '<th>Name</th>';
        $html .= '<th>Type</th>';
        
        $html .= '<th>Parse time</th>';
        $html .= '<th>Actions</th>';
        
        $html .= '</tr>';
        
        /*
         * Test suite
         */
        $html .= '<tr><th colspan="14" class="green lighten-3">';
        $html .= 'Test suite';
        $html .= '</th></tr>';
        
        foreach ($this->getResults() as $result) {
            /* @var $result \UserAgentParserComparison\Entity\Result */
            if ($result->getProvider()->type == 'testSuite') {
                $html .= $this->getRow($result);
            }
        }
        
        /*
         * Providers
         */
        $html .= '<tr><th colspan="14" class="green lighten-3">';
        $html .= 'Providers';
        $html .= '</th></tr>';
        
        foreach ($this->getResults() as $result) {
            if ($result->getProvider()->type == 'real') {
                $html .= $this->getRow($result);
            }
        }
        
        $html .= '</table>';
        
        return $html;
    }

    private function getRow(Result $result)
    {
        $provider = $result->getProvider();
        
        $html = '<tr>';
        
        $html .= '<td>' . $provider->name . '<br />';
        $html .= '<small>' . $result->getProviderVersion() . '</small><br />';
        if ($result->getFilename() != '') {
            $html .= '<small>' . $result->getFilename() . '</small>';
        }
        $html .= '</td>';
        
        if ($result->getResultFound() !== true) {
            $html .= '
                    <td colspan="12" class="center-align red lighten-1">
                        <strong>No result found</strong>
                    </td>
                ';
            
            $html .= '</tr>';
            
            return $html;
        }
        
        /*
         * General
         */
        if ($provider->canDetectBrowserName === true) {
            $html .= '<td>' . $result->getBrowserName() . ' ' . $result->getBrowserVersion() . '</td>';
        } else {
            $html .= '<td><i class="material-icons">close</i></td>';
        }
        
        if ($provider->canDetectEngineName === true) {
            $html .= '<td>' . $result->getEngineName() . ' ' . $result->getEngineVersion() . '</td>';
        } else {
            $html .= '<td><i class="material-icons">close</i></td>';
        }
        
        if ($provider->canDetectOsName === true) {
            $html .= '<td>' . $result->getOsName() . ' ' . $result->getOsVersion() . '</td>';
        } else {
            $html .= '<td><i class="material-icons">close</i></td>';
        }
        
        /*
         * Device
         */
        if ($provider->canDetectDeviceBrand === true) {
            $html .= '<td style="border-left: 1px solid #555">' . $result->getDeviceBrand() . '</td>';
        } else {
            $html .= '<td style="border-left: 1px solid #555"><i class="material-icons">close</i></td>';
        }
        
        if ($provider->canDetectDeviceModel === true) {
            $html .= '<td>' . $result->getDeviceModel() . '</td>';
        } else {
            $html .= '<td><i class="material-icons">close</i></td>';
        }
        
        if ($provider->canDetectDeviceType === true) {
            $html .= '<td>' . $result->getDeviceType() . '</td>';
        } else {
            $html .= '<td><i class="material-icons">close</i></td>';
        }
        
        if ($provider->canDetectDeviceIsMobile === true) {
            if ($result->getDeviceIsMobile() === true) {
                $html .= '<td>yes</td>';
            } else {
                $html .= '<td></td>';
            }
        } else {
            $html .= '<td><i class="material-icons">close</i></td>';
        }
        
        if ($provider->canDetectDeviceIsTouch === true) {
            if ($result->getDeviceIsTouch() === true) {
                $html .= '<td>yes</td>';
            } else {
                $html .= '<td></td>';
            }
        } else {
            $html .= '<td><i class="material-icons">close</i></td>';
        }
        
        /*
         * Bot
         */
        if ($provider->canDetectBotIsBot === true) {
            if ($result->getBotIsBot() === true) {
                $html .= '<td style="border-left: 1px solid #555">yes</td>';
            } else {
                $html .= '<td style="border-left: 1px solid #555"></td>';
            }
        } else {
            $html .= '<td style="border-left: 1px solid #555"><i class="material-icons">close</i></td>';
        }
        
        if ($provider->canDetectBotName === true) {
            $html .= '<td>' . $result->getBotName() . '</td>';
        } else {
            $html .= '<td><i class="material-icons">close</i></td>';
        }
        if ($provider->canDetectBotType === true) {
            $html .= '<td>' . $result->getBotType() . '</td>';
        } else {
            $html .= '<td><i class="material-icons">close</i></td>';
        }
        
        $html .= '<td>' . round($result->getParseTime(), 5) . '</td>';
        
        $html .= '<td>
        
<!-- Modal Trigger -->
<a class="modal-trigger btn waves-effect waves-light" href="#modal-' . $provider->id . '">Detail</a>
        
<!-- Modal Structure -->
<div id="modal-' . $provider->id . '" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>' . $provider->name . ' result detail</h4>
        <p><pre><code class="php">' . print_r($result->getRawResult(), true) . '</code></pre></p>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">close</a>
    </div>
</div>
        
                </td>';
        
        $html .= '</tr>';
        
        return $html;
    }

    public function getHtml()
    {
        $additionalHeaders = $this->getUserAgent()->additionalHeaders;
        
        $addStr = '';
        if ($this->getUserAgent()->additionalHeaders !== null && count($this->getUserAgent()->additionalHeaders) > 0) {
            $addStr = '<strong>Additional headers</strong><br />';
            foreach ($this->getUserAgent()->additionalHeaders as $key => $value) {
                $addStr .= '<strong>' . htmlspecialchars($key) . '</strong> ' . htmlspecialchars($value) . '<br />';
            }
        }
        
        $body = '
<div class="section">
	<h1 class="header center orange-text">User agent detail</h1>
	<div class="row center">
        <h5 class="header light">
            ' . htmlspecialchars($this->getUserAgent()->string) . '<br />
            ' . $addStr . '
        </h5>
	</div>
</div>   

<div class="section">
    ' . $this->getProvidersTable() . '
</div>
';
        
        $script = '
$(document).ready(function(){
    // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
    $(\'.modal-trigger\').leanModal();
});
        ';
        
        return parent::getHtmlCombined($body, $script);
    }
}
