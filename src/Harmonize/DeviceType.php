<?php
namespace UserAgentParserComparison\Harmonize;

class DeviceType extends AbstractHarmonize
{

    protected static $replaces = [
        
        'car' => [
            'car browser',
            'Car Entertainment System'
        ],
        
        'camera' => [
            'Digital Camera'
        ],
        
        'console' => [
            'gaming:console'
        ],
        
        'desktop' => [
            'pc'
        ],
        
        'ereader' => [
            'Ebook Reader'
        ],
        
        'tv' => [
            'Smart-TV',
            'television',
            'tv',
            'TV Device'
        ],
        
        'smartphone' => [
            'smartphone',
            'mobile:smart'
        ]
    ];
}
