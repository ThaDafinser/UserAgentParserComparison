<?php
namespace UserAgentParserComparison\Harmonize;

class DeviceBrand extends AbstractHarmonize
{

    protected static $replaces = [
        'Sony' => [
            'Sony Ericsson',
            'SonyEricsson'
        ],
        
        'BlackBerry' => [
            'RIM'
        ],
        
        'HCL' => [
            'HCLme'
        ],
        
        'TechnoTrend' => [
            'TechnoTrend Goerler/Kathrein'
        ]
    ];
}
