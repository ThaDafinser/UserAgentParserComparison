<?php
namespace UserAgentParserComparison;

use UserAgentParser\Provider\AbstractProvider;
use UserAgentParser\Model\UserAgent;
use UserAgentParser\Model\Version;

class AnalyzeResult
{

    private $providerResults = [];

    private $groupedResult;

    public function reset()
    {
        $this->providerResults = [];
        $this->groupedResult = null;
    }

    /**
     *
     * @param AbstractProvider $provider            
     * @param UserAgent $result            
     */
    public function addResult(AbstractProvider $provider, UserAgent $result = null, array $misc = [])
    {
        $array = [];
        if ($result !== null) {
            $array = $result->toArray();
        }
        
        $this->providerResults[] = [
            'provider' => $provider,
            'result' => $result,
            'resultArray' => $array,
            
            'misc' => $misc
        ];
    }

    /**
     *
     * @return array
     */
    public function getAnalyzedResult()
    {
        $return = [];
        
        foreach ($this->providerResults as $key => $providerResult) {
            /* @var $provider \UserAgentParser\Provider\AbstractProvider */
            $provider = $providerResult['provider'];
            
            /* @var $result \UserAgentParser\Model\UserAgent */
            $result = $providerResult['result'];
            
            $return[] = [
                'provider' => $provider,
                'result' => $result,
                
                'misc' => $providerResult['misc'],
                
                'matchCount' => $this->getPossibleWrongPositive($provider, $result)
            ];
        }
        
        return $return;
    }

    private function getProviderCount()
    {
        return count($this->providerResults);
    }

    /**
     *
     * @return array
     */
    private function getGroupedResult()
    {
        if ($this->groupedResult !== null) {
            return $this->groupedResult;
        }
        
        $resultArray = array_column($this->providerResults, 'resultArray');
        
        $browser = array_column($resultArray, 'browser');
        $browserName = array_column($browser, 'name');
        $browserVersion = array_column($browser, 'version');
        
        $engine = array_column($resultArray, 'renderingEngine');
        $engineName = array_column($engine, 'name');
        $engineVersion = array_column($engine, 'version');
        
        $os = array_column($resultArray, 'operatingSystem');
        $osName = array_column($os, 'name');
        $osVersion = array_column($os, 'version');
        
        $this->groupedResult = [
            
            'browser' => [
                'name' => $browserName,
                'version' => $browserVersion
            ],
            
            'engine' => [
                'name' => $engineName,
                'version' => $engineVersion
            ],
            
            'os' => [
                'name' => $osName,
                'version' => $osVersion
            ]
        ];
        
        return $this->groupedResult;
    }

    private function getPossibleWrongPositive(AbstractProvider $provider, UserAgent $resultToCheck = null)
    {
        if ($resultToCheck === null) {
            return [];
        }
        
        return [
            'renderingEngine' => [
                'name' => $this->getMatchCountEngineName($resultToCheck),
                'engine' => $this->getMatchCountVersion($resultToCheck->getRenderingEngine()
                    ->getVersion(), 'engine')
            ]
        ];
    }


    private function getMatchCountEngineName(UserAgent $resultToCheck)
    {
        $groupedResult = $this->getGroupedResult();
        
        $toCompare = $this->getNormalizedEngineName($resultToCheck->getRenderingEngine()
            ->getName());
        
        // no result itself...so no comparison needed
        if ($toCompare === null) {
            return [
                'countOtherResults' => null,
                'matchCount' => null
            ];
        }
        
        // -1 because the own result is also here!
        $resultsAvailable = - 1;
        $matchCount = - 1;
        foreach ($groupedResult['engine']['name'] as $value) {
            
            $normalizedValue = $this->getNormalizedEngineName($value);
            
            if ($normalizedValue === null) {
                continue;
            }
            
            $resultsAvailable ++;
            
            if ($toCompare == $normalizedValue) {
                $matchCount ++;
            }
        }
        
        return [
            'countOtherResults' => $resultsAvailable,
            'matchCount' => $matchCount
        ];
    }

    private function getMatchCountVersion(Version $version, $type)
    {
        // no version provided -> nothing to compare!
        if ($version->getComplete() === null) {
            return null;
        }
        
        $groupedResult = $this->getGroupedResult();
        
        // -1 because the own result is also here!
        $matchCount = - 1;
        foreach ($groupedResult[$type]['version'] as $versionArray) {
            // no version available -> nothing to compare!
            if ($versionArray['complete'] === null) {
                continue;
            }
            
            if ($version->getMajor() == $versionArray['major']) {
                if ($version->getMinor() === null || $version->getMinor() == $versionArray['minor']) {
                    if ($version->getPatch() === null || $version->getPatch() == $versionArray['patch']) {
                        $matchCount ++;
                    }
                }
            }
        }
        
        return $matchCount;
    }
    
    private function getNormalizedEngineName($name)
    {
        if ($name === null) {
            return null;
        }
        /*
         * "NULL"
         * "Blink"
         * "Dillo"
         * "Edge" <-- str_ireplace
         * "EdgeHTML"<-- str_ireplace
         * "Gecko"
         * "KHTML"
         * "Microsoft Word"
         * "NetFront"
         * "Presto"
         * "T5"
         * "Tasman"
         * "Text-based"
         * "Trident"
         * "U2"
         * "U3"
         * "WebKit" <- covered with strtolower
         * "Webkit" <- covered with strtolower
         * "webkit" <- covered with strtolower
         *
         */
        $name = str_ireplace('Edge', 'EdgeHTML', $name);
    
        $name = strtolower($name);
    
        return $name;
    }
}
