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
                
                'misc' => $providerResult['misc']
//                 'matchCount' => $this->getPossibleWrongPositive($provider, $result)
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
    
        $os = array_column($resultArray, 'operatingSystem');
        $osName = array_column($os, 'name');
        $browserVersion = array_column($browser, 'version');
    
        $this->groupedResult = [
            'browser' => [
                'name' => $browserName,
                'version' => $browserVersion
            ],
    
            'os' => [
                'name' => $osName
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
            'browser' => [
                'name' => $this->getMatchCountBrowserName($resultToCheck),
                'version' => $this->getMatchCountVersion($resultToCheck->getBrowser()
                    ->getVersion(), 'browser')
            ]
        ];
    }

    private function getMatchCountBrowserName(UserAgent $resultToCheck)
    {
        $groupedResult = $this->getGroupedResult();
        
        // -1 because the own result is also here!
        $matchCount = - 1;
        foreach ($groupedResult['browser']['name'] as $browserName) {
            if ($browserName == $resultToCheck->getBrowser()->getName()) {
                $matchCount ++;
            }
        }
        
        return $matchCount;
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
}
