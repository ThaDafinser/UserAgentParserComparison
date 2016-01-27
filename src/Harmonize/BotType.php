<?php
namespace UserAgentParserComparison\Harmonize;

class BotType extends AbstractHarmonize
{

    protected static $replaces = [
        
        'RSS' => [
            'Feed Fetcher',
            'Feed Parser'
        ],
        
        'Site Monitor' => [
            'Site Monitors'
        ]
    ];
}
