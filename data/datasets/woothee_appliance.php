<?php
$fixtureData = \Spyc::YAMLLoad('vendor/woothee/woothee-testset/testsets/appliance.yaml');

$userAgents = array_column($fixtureData, 'target');

return [
    'userAgents' => $userAgents,
    'source' => 'woothee/woothee-testset',
    'group' => 'mixed'
];
