<?php
namespace UserAgentParserComparison\Harmonize;

class BrowserName extends AbstractHarmonize
{

    protected static $replaces = [
        
        '360 Browser' => [
            '360 Phone Browser'
        ],
        
        'Amazon Silk' => [
            'Amazon Silk Browser'
        ],
        
        'Chrome' => [
            'Chrome Dev',
            'Chrome Mobile'
        ],
        
        'Firefox' => [
            'Firefox Mobile'
        ],
        
        'IE' => [
            'IE Mobile',
            'IEMobile',
            'Internet Explorer',
            'MSIE'
        ],
        
        'Opera' => [
            'Opera Mini',
            'Opera Mobile',
            'Opera Next'
        ]
        
    ];
}
