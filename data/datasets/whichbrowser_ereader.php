<?php
$userAgents = shell_exec('php vendor/whichbrowser/testrunner/runner.php list ereader');
$userAgents = explode("\n", $userAgents);
array_pop($userAgents);

return [
    'userAgents' => $userAgents,
    'source' => 'whichbrowser/testrunner',
    'group' => 'tablet'
];
