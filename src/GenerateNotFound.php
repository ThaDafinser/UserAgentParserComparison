<?php
namespace UserAgentParserMatrix;

use UserAgentParser\Model;
use UserAgentParser\Provider\AbstractProvider;

class GenerateNotFound extends AbstractGenerate
{

    private $activeProvider;

    /**
     *
     * @param AbstractProvider $activeProvider            
     */
    public function setActiveProvider(AbstractProvider $activeProvider)
    {
        $this->activeProvider = $activeProvider;
    }

    /**
     *
     * @return AbstractProvider
     */
    public function getActiveProvider()
    {
        return $this->activeProvider;
    }

    private function addRow(array $rowData)
    {
        $activeProvider = $this->getActiveProvider();
        
        $html = '<tr>';
        $html .= '<th>' . $rowData['userAgent'] . '</th>';
        foreach ($this->getProviders() as $provider) {
            
            if ($provider->getName() === $activeProvider->getName()) {
                continue;
            }
            
            $html .= '<td>';
            if ($rowData[$provider->getName()] === null) {
                $html .= 'no result found';
            } else {
                $html .= '<button onclick="$(this).parent().find(\'.detail\').toggle()" class="btn btn-default">show detail</button>';
                
                $html .= '<div class="detail" style="display: none"><pre>' . print_r($rowData[$provider->getName()]->toArray(true)['providerResultRaw'], true) . '</pre></div>';
            }
            $html .= '</td>';
        }
        $html .= '</tr>';
        
        return $html;
    }

    public function getConcreteHtml()
    {
        $activeProvider = $this->getActiveProvider();
        
        $rows = [];
        foreach ($this->getAnalyze()->getResults() as $rowData) {
            $actualProvider = $rowData[$activeProvider->getName()];
            
            if ($actualProvider === null) {
                $rows[] = $this->addRow($rowData);
            }
            // exit();
        }
        
        $table = '<h1>Not found ' . $activeProvider->getName() . '</h1>';
        $table .= '<table class="table table-bordered table-condensed">';
        
        $table .= '<tr>';
        $table .= '<th></th>';
        
        foreach ($this->getProviders() as $provider) {
            if ($provider->getName() === $activeProvider->getName()) {
                continue;
            }
            
            $table .= '<th>' . $provider->getName() . '</th>';
        }
        $table .= '</tr>';
        
        $table .= implode(PHP_EOL, $rows);
        $table .= '</table>';
        
        return $table;
    }

    public function persist()
    {
        if (! file_exists($this->getFolder())) {
            mkdir($this->getFolder(), null, true);
        }
        
        foreach ($this->getProviders() as $provider) {
            $this->setActiveProvider($provider);
            
            file_put_contents($this->getFolder() . '/notFound_' . $provider->getName() . '.html', $this->toHtml());
        }
    }
}