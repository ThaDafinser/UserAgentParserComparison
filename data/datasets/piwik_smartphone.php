<?php
$fixtureData = \Spyc::YAMLLoad('vendor/piwik/device-detector/Tests/fixtures/smartphone.yml');

$userAgents = array_column($fixtureData, 'user_agent');

return [
    'userAgents' => $userAgents,
    'source' => 'piwik/device-detector',
    'group' => 'phone'
];
