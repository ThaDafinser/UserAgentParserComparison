<?php
$userAgents = shell_exec('php vendor/whichbrowser/testrunner/runner.php list mobile');
$userAgents = explode("\n", $userAgents);
array_pop($userAgents);

return $userAgents;
