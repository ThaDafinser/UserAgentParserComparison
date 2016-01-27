<?php
namespace UserAgentParserComparison\Html;

use UserAgentParserComparison\Entity\UserAgentEvaluation;
use UserAgentParserComparison\Entity\Result;

class UserAgentDetail extends AbstractHtml
{

    /**
     *
     * @var string
     */
    private $userAgent;

    /**
     *
     * @var UserAgentEvaluation
     */
    private $userAgentEvaluation;

    /**
     *
     * @var Result[]
     */
    private $results = [];

    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     *
     * @param UserAgentEvaluation $userAgentEvaluation            
     */
    public function setUserAgentEvaluation(UserAgentEvaluation $userAgentEvaluation = null)
    {
        $this->userAgentEvaluation = $userAgentEvaluation;
    }

    /**
     *
     * @return UserAgentEvaluation
     */
    public function getUserAgentEvaluation()
    {
        return $this->userAgentEvaluation;
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
        $html .= '<th></th>';
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
        
        $html .= '<th>Actions</th>';
        
        $html .= '</tr>';
        
        foreach ($this->getResults() as $result) {
            
            $provider = $result->getProvider();
            
            $html .= '<tr>';
            
            $html .= '<td>' . $provider->name . '<br /><small>' . $result->getProviderVersion() . '</small></td>';
            
            if ($result->getResultFound() !== true) {
                
                $html .= '
                    <td colspan="12" class="center-align red lighten-1">
                        <strong>No result found</strong>
                    </td>
                ';
                
                $html .= '</tr>';
                
                continue;
            }
            
            /*
             * General
             */
            if ($provider->canDetectBotIsBot) {}
            
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
                $html .= '<td>' . $result->getDeviceBrand() . '</td>';
            } else {
                $html .= '<td><i class="material-icons">close</i></td>';
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
                    $html .= '<td>yes</td>';
                } else {
                    $html .= '<td></td>';
                }
            } else {
                $html .= '<td><i class="material-icons">close</i></td>';
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
            
            // @todo somehow show this as a detail?
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
        }
        
        $html .= '</table>';
        
        return $html;
    }

    private function getEvaluationRow($type)
    {
        $uaEvaluation = $this->getUserAgentEvaluation();
        
        $html = '<tr>';
        $html .= '<td>' . $type . '</td>';
        $html .= '<td>' . $uaEvaluation->{$type . 'Found'} . ' of ' . $this->getProviderCapabilityCount($type) . '</td>';
        
        $html .= '<td>' . $uaEvaluation->{$type . 'FoundUnique'} . '</td>';
        $html .= '<td>' . $uaEvaluation->{$type . 'MaxSameResultCount'} . '</td>';
        
        $html .= '<td>' . $uaEvaluation->{$type . 'HarmonizedFoundUnique'} . '</td>';
        $html .= '<td>' . $uaEvaluation->{$type . 'HarmonizedMaxSameResultCount'} . '</td>';
        $html .= '</tr>';
        
        return $html;
    }

    private function getEvaluationTable()
    {
        $html = '<table class="striped">';
        
        $html .= '<tr>';
        
        $html .= '<th>Part</th>';
        $html .= '<th>Found</th>';
        
        $html .= '<th>Unique found</th>';
        $html .= '<th>Max same result count</th>';
        
        $html .= '<th>Harmonized unique</th>';
        $html .= '<th>Harmonized max same result count</th>';
        
        $html .= '</tr>';
        
        $html .= $this->getEvaluationRow('browserName');
        $html .= $this->getEvaluationRow('browserVersion');
        
        $html .= $this->getEvaluationRow('engineName');
        $html .= $this->getEvaluationRow('engineVersion');
        
        $html .= $this->getEvaluationRow('osName');
        $html .= $this->getEvaluationRow('osVersion');
        
        $html .= $this->getEvaluationRow('deviceBrand');
        $html .= $this->getEvaluationRow('deviceModel');
        $html .= $this->getEvaluationRow('deviceType');
        
        $html .= $this->getEvaluationRow('botName');
        $html .= $this->getEvaluationRow('botType');
        
        $html .= '</table>';
        
        return $html;
    }

    public function getHtml()
    {
        $script = '
$(document).ready(function(){
    // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
    $(\'.modal-trigger\').leanModal();
});
        ';
        
        $body = '
<div class="section">
	<h1 class="header center orange-text">User agent detail</h1>
	<div class="row center">
        ' . $this->getUserAgent() . '
        <p>
            Detected by ' . $this->getUserAgentEvaluation()->resultFound . ' of ' . $this->getUserAgentEvaluation()->resultCount . ' providers<br />
            As bot detected by ' . $this->getUserAgentEvaluation()->asBotDetectedCount . ' of ' . $this->getProviderCapabilityCount('botIsBot') . '
		</p>
	</div>
</div>   

<div class="section">
    ' . $this->getProvidersTable() . '
</div>
';
        
        // $body .= '
        // <div class="section">
        // ' . $this->getEvaluationTable() . '
        // </div>
        // ';
        
        return parent::getHtmlCombined($body, $script);
    }
}
