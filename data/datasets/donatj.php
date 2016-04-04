<?php
$file = 'vendor/donatj/phpuseragentparser/Tests/user_agents.json';
$content = file_get_contents($file);

$json = json_decode($content);

$userAgents = [];

foreach ($json as $ua => $row) {
    
    $data = [
        'resFilename' => $file,
        
        'resBrowserName' => $row->browser,
        'resBrowserVersion' => $row->version,
        
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
    
    $userAgents[bin2hex(sha1($ua, true))] = [
        'uaString' => $ua,
        'result' => $data
    ];
}

return [
    'provider' => [
        'proType' => 'testSuite',
        'proName' => 'Browscap',
        'proPackageName' => 'browscap/browscap',
        'proHomepage' => 'https://github.com/browscap/browscap',
        
        'proCanDetectBrowserName' => 1,
        'proCanDetectBrowserVersion' => 1,
        
        'proCanDetectEngineName' => 1,
        'proCanDetectEngineVersion' => 1,
        
        'proCanDetectOsName' => 1,
        'proCanDetectOsVersion' => 1,
        
        'proCanDetectDeviceModel' => 1,
        'proCanDetectDeviceBrand' => 1,
        'proCanDetectDeviceType' => 1,
        'proCanDetectDeviceIsMobile' => 1,
        'proCanDetectDeviceIsTouch' => 1,
        
        'proCanDetectBotIsBot' => 1,
        'proCanDetectBotName' => 1,
        'proCanDetectBotType' => 1
    ],
    
    'userAgents' => $userAgents
];
