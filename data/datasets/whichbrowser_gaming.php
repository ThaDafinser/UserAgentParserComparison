<?php
$userAgents = shell_exec('php vendor/whichbrowser/testrunner/runner.php list gaming');
$userAgents = explode("\n", $userAgents);
array_pop($userAgents);

return [
    'userAgents' => $userAgents,
    'source' => 'whichbrowser/testrunner',
    'group' => 'mixed'
];
