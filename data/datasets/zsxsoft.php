<?php

function hydrateZsxsoft($data, array $row)
{
    $row = $row[1];
    
    $data['resRawResult'] = serialize($row);
    
    if (isset($row[2]) && $row[2] != '') {
        $data['resBrowserName'] = $row[2];
    }
    if (isset($row[3]) && $row[3] != '') {
        $data['resBrowserVersion'] = $row[3];
    }
    
    if (isset($row[5]) && $row[5] != '') {
        $data['resOsName'] = $row[5];
    }
    if (isset($row[6]) && $row[6] != '') {
        $data['resOsVersion'] = $row[6];
    }
    
    // if(isset($row[8]) && $row[8] != ''){
    // var_dump($row[8]);
    // }
    // if(isset($row[9]) && $row[9] != ''){
    // var_dump($row[9]);
    // }
    
    // var_dump($row);
    // var_dump($data);
    // exit();
    
    // 0 => browser image
    // 1 => os image
    // 2 => browser name
    // 3 => browser version
    // 4 => browser title
    // 5 => os name
    // 6 => os version
    // 7 => os title
    // 8 => device title
    // 9 => platform type
    
    return $data;
}

$fixtureData = include 'vendor/zsxsoft/php-useragent/tests/UserAgentList.php';

if (! is_array($fixtureData)) {
    throw new \Exception('wrong result!');
}
$userAgents = [];

foreach ($fixtureData as $row) {
    
    $data = [
        'resFilename' => 'vendor/zsxsoft/php-useragent/tests/UserAgentList.php',
        
        'resBrowserName' => null,
        'resBrowserVersion' => null,
        
        'resEngineName' => null,
        'resEngineVersion' => null,
        
        'resOsName' => null,
        'resOsVersion' => null,
        
        'resDeviceModel' => null,
        'resDeviceBrand' => null,
        'resDeviceType' => null,
        'resDeviceIsMobile' => null,
        'resDeviceIsTouch' => null,
        
        'resBotIsBot' => null,
        'resBotName' => null,
        'resBotType' => null
    ];
    
    $result = hydrateZsxsoft($data, $row);
    
    $userAgents[bin2hex(sha1($row[0][0], true))] = [
        'uaString' => $row[0][0],
        'result' => $result
    ];
    echo '.';
}

return [
    'provider' => [
        'proType' => 'testSuite',
        'proName' => 'Zsxsoft',
        'proPackageName' => 'zsxsoft/php-useragent',
        'proHomepage' => 'https://github.com/zsxsoft/php-useragent',
        
        'proCanDetectBrowserName' => 1,
        'proCanDetectBrowserVersion' => 1,
        
        'proCanDetectEngineName' => 0,
        'proCanDetectEngineVersion' => 0,
        
        'proCanDetectOsName' => 1,
        'proCanDetectOsVersion' => 1,
        
        'proCanDetectDeviceModel' => 1,
        'proCanDetectDeviceBrand' => 1,
        'proCanDetectDeviceType' => 0,
        'proCanDetectDeviceIsMobile' => 0,
        'proCanDetectDeviceIsTouch' => 0,
        
        'proCanDetectBotIsBot' => 0,
        'proCanDetectBotName' => 1,
        'proCanDetectBotType' => 0
    ],
    
    'userAgents' => $userAgents
];
