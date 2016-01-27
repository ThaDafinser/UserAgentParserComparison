<?php
namespace UserAgentParserComparison\Harmonize;

class OsName extends AbstractHarmonize
{

    protected static $replaces = [
        'BlackBerry' => [
            'BlackBerry OS'
        ],
        
        'Chrome OS' => [
            'ChromeOS'
        ],
        
        'Linux' => [
            'GNU/Linux'
        ],
        
        'Windows' => [
            'Win32',
            'Win2000',
            'WinVista',
            'Win7',
            'Win8',
            
            'Windows 2000',
            'Windows XP',
            'Windows 7',
            'Windows 8',
            
            'Windows CE',
            'Windows Mobile'
        ],
        
        'iOS' => [
            'iPhone OS'
        ],
        
        'OS X' => [
            'Mac',
            'Mac OS',
            'Mac OS X'
        ],
        
        'Symbian' => [
            'SymbianOS',
            'Symbian OS',
            'Symbian OS Series 40',
            'Symbian OS Series 60',
            'Symbian S60',
            
            'Series40',
            'Series60',
            
            'Nokia Series 40'
        ]
    ];
}
