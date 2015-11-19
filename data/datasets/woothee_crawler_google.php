<?php
$fixtureData = \Spyc::YAMLLoad('vendor/woothee/woothee-testset/testsets/crawler_google.yaml');

$userAgents = array_column($fixtureData, 'target');

return [
    'userAgents' => $userAgents,
    'source' => 'woothee/woothee-testset',
    'group' => 'bot'
];
