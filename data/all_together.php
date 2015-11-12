<?php
$handleAllTogether = opendir('data');
if ($handleAllTogether === false) {
    throw new \Exception('folder could not get opened');
}

$userAgentsTogether = [];

while ($filename = readdir($handleAllTogether)) {
    
    if ($filename == '.' || $filename == '..' || strpos($filename, '.php') === false) {
        continue;
    }
    
    if ($filename == 'all_together.php') {
        continue;
    }
    
    $userAgentsTogetherPart = include 'data/' . $filename;
    
    $userAgentsTogether = array_merge($userAgentsTogether, $userAgentsTogetherPart);
}
closedir($handleAllTogether);

$userAgentsTogether = array_unique($userAgentsTogether);

$useFilename = basename(__FILE__);

return $userAgentsTogether;
