<?php
namespace UserAgentParserMatrix;

use UserAgentParser\Model;

class GenerateSummary extends AbstractGenerate
{

    private function getStatisticRow($label, $part, $subPart = null, $subSubPart = null)
    {
        $statistics = $this->getAnalyze()->getStatistics();
        
        $providers = $this->getProviders();
        
        $html = '<tr>';
        $html .= '<td>' . $label . '</td>';
        
        foreach ($providers as $provider) {
            $value = $statistics[$provider->getName()][$part];
            if ($subPart !== null) {
                $value = $value[$subPart];
            }
            
            if ($subSubPart !== null) {
                $value = $value[$subSubPart];
            }
            
            if ($value === null || $value === 0) {
                $html .= '<th></th>';
                
                continue;
            }
            
            /*
             * Percent
             */
            $total = $this->getTotal();
            
            $substractTotal = 0;
            if ($part == 'resultFound') {
                $substractTotal = 0;
            } elseif ($part == 'botFound' || $part == 'generalFound') {
                $total = $statistics[$provider->getName()]['resultFound'];
            } elseif ($part === 'bot') {
                $total = $statistics[$provider->getName()]['botFound'];
            } else {
                $total = $statistics[$provider->getName()]['generalFound'];
            }
            
            $percent = $this->getPercent($value, $total);
            
            if ($percent > 90) {
                $class = 'progress-bar-success';
            } elseif ($percent > 70) {
                $class = 'progress-bar-warning';
            } else {
                $class = 'progress-bar-danger';
            }
            
            $progressbar = '
                <div class="progress">
                <div class="progress-bar ' . $class . '" role="progressbar" aria-valuenow="' . $percent . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent . '%;">
                    ' . $value . '
                </div>
                </div>
            ';
            
            $html .= '<th>' . $progressbar . '</th>';
        }
        
        $html .= '</tr>';
        
        return $html;
    }

    public function getConcreteHtml()
    {
        $providers = $this->getProviders();
        
        $total = $this->getTotal();
        
        $table = '<table class="table table-bordered table-condensed">';
        
        /*
         * Header
         */
        $table .= '<tr>';
        $table .= '<th style="width: 250px;"></th>';
        
        foreach ($providers as $provider) {
            $table .= '<th style="width: 200px;">' . $provider->getName() . '</th>';
        }
        
        $statistics = $this->getAnalyze()->getStatistics();
        
        /*
         * General
         */
        $table .= '<tr>';
        $table .= '<th colspan="' . ($total + 1) . '">General</th>';
        $table .= '</tr>';
        
        $table .= '<tr>';
        $table .= '<td>Total</td>';
        
        foreach ($providers as $provider) {
            $table .= '<td>' . $this->getTotal() . '</td>';
        }
        $table .= '</tr>';
        
        $table .= $this->getStatisticRow('Result found', 'resultFound');
        $table .= $this->getStatisticRow('General found', 'generalFound');
        $table .= $this->getStatisticRow('Bot found', 'botFound');
        
        /*
         * Browser
         */
        $table .= '<tr>';
        $table .= '<th colspan="' . ($total + 1) . '">Browser - <i>percent bar is relative to General found</i></th>';
        $table .= '</tr>';
        
        $table .= $this->getStatisticRow('Name', 'browser', 'name');
        $table .= $this->getStatisticRow('Version complete', 'browser', 'version', 'complete');
        $table .= $this->getStatisticRow('Version major', 'browser', 'version', 'major');
        $table .= $this->getStatisticRow('Version minor', 'browser', 'version', 'minor');
        $table .= $this->getStatisticRow('Version patch', 'browser', 'version', 'patch');
        
        /*
         * renderingEngine
         */
        $table .= '<tr>';
        $table .= '<th colspan="' . ($total + 1) . '">Rendering engine - <i>percent bar is relative to General found</i></th>';
        $table .= '</tr>';
        
        $table .= $this->getStatisticRow('Name', 'renderingEngine', 'name');
        $table .= $this->getStatisticRow('Version complete', 'renderingEngine', 'version', 'complete');
        $table .= $this->getStatisticRow('Version major', 'renderingEngine', 'version', 'major');
        $table .= $this->getStatisticRow('Version minor', 'renderingEngine', 'version', 'minor');
        $table .= $this->getStatisticRow('Version patch', 'renderingEngine', 'version', 'patch');
        
        /*
         * operatingSystem
         */
        $table .= '<tr>';
        $table .= '<th colspan="' . ($total + 1) . '">Operating system - <i>percent bar is relative to General found</i></th>';
        $table .= '</tr>';
        
        $table .= $this->getStatisticRow('Name', 'operatingSystem', 'name');
        $table .= $this->getStatisticRow('Version complete', 'operatingSystem', 'version', 'complete');
        $table .= $this->getStatisticRow('Version major', 'operatingSystem', 'version', 'major');
        $table .= $this->getStatisticRow('Version minor', 'operatingSystem', 'version', 'minor');
        $table .= $this->getStatisticRow('Version patch', 'operatingSystem', 'version', 'patch');
        
        /*
         * device
         */
        $table .= '<tr>';
        $table .= '<th colspan="' . ($total + 1) . '">Device - <i>percent bar is relative to General found</i></th>';
        $table .= '</tr>';
        
        $table .= $this->getStatisticRow('Model', 'device', 'model');
        $table .= $this->getStatisticRow('Brand', 'device', 'brand');
        $table .= $this->getStatisticRow('Type', 'device', 'type');
        $table .= $this->getStatisticRow('isMobile', 'device', 'isMobile');
        $table .= $this->getStatisticRow('isTouch', 'device', 'isTouch');
        
        /*
         * bot
         */
        $table .= '<tr>';
        $table .= '<th colspan="' . ($total + 1) . '">Bot - <i>percent bar is relative to Bot found</i></th>';
        $table .= '</tr>';
        
        $table .= $this->getStatisticRow('isBot', 'bot', 'isBot');
        $table .= $this->getStatisticRow('Name', 'bot', 'name');
        $table .= $this->getStatisticRow('Type', 'bot', 'type');
        
        $table .= '</table>';
        
        return $table;
    }

    public function persist()
    {
        if (! file_exists($this->getFolder())) {
            mkdir($this->getFolder(), null, true);
        }
        
        file_put_contents($this->getFolder() . '/summary.html', $this->toHtml());
    }
}
