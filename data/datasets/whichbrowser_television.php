<?php
$userAgents = shell_exec('php vendor/whichbrowser/testrunner/runner.php list television');
$userAgents = explode("\n", $userAgents);
array_pop($userAgents);

return [
    'userAgents' => $userAgents,
    'source' => 'whichbrowser/testrunner',
    'group' => 'tv'
];
