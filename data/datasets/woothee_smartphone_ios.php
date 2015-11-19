<?php
$fixtureData = \Spyc::YAMLLoad('vendor/woothee/woothee-testset/testsets/smartphone_ios.yaml');

$userAgents = array_column($fixtureData, 'target');

return [
    'userAgents' => $userAgents,
    'source' => 'woothee/woothee-testset',
    'group' => 'mixed'
];
