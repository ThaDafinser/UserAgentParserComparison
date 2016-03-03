<?php
$path = 'vendor/browscap/browscap/tests/fixtures/issues';

function hydrateBrowscap($data, array $row)
{
    $data['resRawResult'] = serialize($row[1]);
    
    $row = $row[1];
    
    if (isset($row['Browser']) && stripos($row['Browser'], 'Fake') !== false) {
        throw new \Exception('skip...');
    }
    
    if (isset($row['Crawler']) && $row['Crawler'] === true) {
        $data['resBotIsBot'] = 1;
        
        if (isset($row['Browser']) && $row['Browser'] != '') {
            $data['resBotName'] = $row['Browser'];
        }
        
        if (isset($row['Browser_Type']) && $row['Browser_Type'] != '') {
            $data['resBotType'] = $row['Browser_Type'];
        }
        
        return $data;
    }
    
    if (isset($row['Browser']) && $row['Browser'] != '') {
        $data['resBrowserName'] = $row['Browser'];
    }
    if (isset($row['Version']) && $row['Version'] != '') {
        $data['resBrowserVersion'] = $row['Version'];
    }
    
    if (isset($row['RenderingEngine_Name']) && $row['RenderingEngine_Name'] != '') {
        $data['resEngineName'] = $row['RenderingEngine_Name'];
    }
    if (isset($row['RenderingEngine_Version']) && $row['RenderingEngine_Version'] != '') {
        $data['resEngineVersion'] = $row['RenderingEngine_Version'];
    }
    
    if (isset($row['Platform']) && $row['Platform'] != '') {
        $data['resOsName'] = $row['Platform'];
    }
    if (isset($row['Platform_Version']) && $row['Platform_Version'] != '') {
        $data['resOsVersion'] = $row['Platform_Version'];
    }
    
    if (isset($row['Device_Name']) && $row['Device_Name'] != '') {
        $data['resDeviceModel'] = $row['Device_Name'];
    }
    if (isset($row['Device_Brand_Name']) && $row['Device_Brand_Name'] != '') {
        $data['resDeviceBrand'] = $row['Device_Brand_Name'];
    }
    if (isset($row['Device_Type']) && $row['Device_Type'] != '') {
        $data['resDeviceType'] = $row['Device_Type'];
    }
    if (isset($row['isMobileDevice']) && $row['isMobileDevice'] != '') {
        $data['resDeviceIsMobile'] = $row['isMobileDevice'];
    }
    if (isset($row['Device_Pointing_Method']) && $row['Device_Pointing_Method'] == 'touchscreen') {
        $data['resDeviceIsTouch'] = 1;
    }
    
    return $data;
}

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
$files = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

$userAgents = [];

foreach ($files as $file) {
    $file = $file[0];
    
    $result = include $file;
    
    if (! is_array($result)) {
        throw new \Exception($file . ' did not return an array!');
    }
    
    foreach ($result as $row) {
        
        $data = [
            'resFileName' => $file,
            
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
        
        try {
            $result = hydrateBrowscap($data, $row);
            
            $userAgents[bin2hex(sha1($row[0], true))] = [
                'uaString' => $row[0],
                'results' => [
                    $result
                ]
            ];
        } catch (\Exception $ex) {
            // skip this UA
            echo 'S';
        }
    }
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
