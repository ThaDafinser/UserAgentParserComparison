<?php
namespace UserAgentParserMatrix;

use UserAgentParser\Exception;
use UserAgentParser\Model;
use UserAgentParser\Provider\Chain;

class Analyze
{
    /**
     *
     * @var Chain
     */
    private $chainProvider;

    /**
     *
     * @var array
     */
    private $userAgents = [];

    private $results = [];

    private $statistics = [];

    public function setChainProvider(Chain $provider)
    {
        $this->chainProvider = $provider;
    }

    /**
     *
     * @return Chain
     */
    public function getChainProvider()
    {
        return $this->chainProvider;
    }

    /**
     *
     * @param array $userAgents
     */
    public function setUserAgents(array $userAgents)
    {
        $this->userAgents = $userAgents;
    }

    /**
     *
     * @return array
     */
    public function getUserAgents()
    {
        return $this->userAgents;
    }

    public function execute()
    {
        echo 'Total UA: ' . count($this->getUserAgents()) . PHP_EOL;

        foreach ($this->getUserAgents() as $userAgent) {
            $row = [
                'userAgent' => $userAgent,
            ];

            echo '.';

            foreach ($this->getChainProvider()->getProviders() as $provider) {
                try {
                    $result                    = $provider->parse($userAgent);
                    $row[$provider->getName()] = $result;
                } catch (Exception\NoResultFoundException $ex) {
                    $row[$provider->getName()] = null;
                }
            }

            $this->results[] = $row;
        }
        
        echo PHP_EOL . PHP_EOL;

        /*
         * Analyze the result
         */
        $this->createStatistic();
    }

    private function createStatistic()
    {
        /*
         * Create the keys
         */
        foreach ($this->getChainProvider()->getProviders() as $provider) {
            
            $emptyResult = new Model\UserAgent();
            $emptyResult = $emptyResult->toArray();

            $emptyResult['resultFound']  = 0;
            $emptyResult['generalFound'] = 0;
            $emptyResult['botFound']     = 0;

            $emptyResult['noResultFound'] = 0;
            $emptyResult['bot']['isBot']  = 0;

            $this->statistics[$provider->getName()] = $emptyResult;
        }

        /*
         * Add count
         */
        foreach ($this->getResults() as $row) {
            foreach ($this->getChainProvider()->getProviders() as $provider) {
                $providerStat = $this->statistics[$provider->getName()];

                if (! isset($row[$provider->getName()])) {
                    $providerStat['noResultFound'] += 1;

                    continue;
                }

                /* @var $result \UserAgentParser\Model\UserAgent */
                $result      = $row[$provider->getName()];
                $resultArray = $result->toArray();

                $providerStat['resultFound'] += 1;

                if ($result->getBot()->getIsBot() !== true) {
                    $providerStat['generalFound'] += 1;
                } else {
                    $providerStat['botFound'] += 1;
                }

                foreach ($resultArray as $key => $part) {
                    foreach ($part as $subKey => $subPart) {
                        if (is_array($subPart)) {
                            // version!

                            foreach ($subPart as $subSubKey => $subSubPart) {
                                if ($subSubPart !== null) {
                                    $providerStat[$key][$subKey][$subSubKey] += 1;
                                }
                            }

                            continue;
                        }

                        if ($subPart === null) {
                            continue;
                        }

                        $providerStat[$key][$subKey] += 1;
                    }
                }

                $this->statistics[$provider->getName()] = $providerStat;
            }
        }
    }

    /**
     *
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     *
     * @return array
     */
    public function getStatistics()
    {
        return $this->statistics;
    }
}
