<?php
namespace UserAgentParserComparison\Harmonize;

class BotName extends AbstractHarmonize
{

    protected static $replaces = [
        'Google App Engine' => [
            'Google AppEngine',
            'AppEngine-Google'
        ],
        
        'Java' => [
            'Java Standard Library'
        ]
    ];
}
