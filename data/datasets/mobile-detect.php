<?php
$path = 'vendor/mobiledetect/mobiledetectlib/tests/providers/vendors';

function hydrateMobileDetect($data, array $row)
{
    $data['resRawResult'] = serialize($row);
    
    if ($row['isMobile'] === true) {
        $data['resDeviceIsMobile'] = 1;
    }
    
    if ($row['isTablet'] === true) {
        $data['resDeviceType'] = 'tablet';
    }
    
    if (isset($row['model'])) {
        $data['resDeviceModel'] = $row['model'];
    }
    
    return $data;
}

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
$files = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

$userAgents = [];

foreach ($files as $file) {
    
    $file = $file[0];
    
    $vendorResult = include $file;
    
    if (! is_array($vendorResult)) {
        throw new \Exception($file . ' did not return an array!');
    }
    
    foreach ($vendorResult as $vendor => $result) {
        
        foreach ($result as $userAgent => $row) {
            
            $data = [
                'resFilename' => $file,
                
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
                $result = hydrateMobileDetect($data, $row);
                
                $userAgents[bin2hex(sha1($userAgent, true))] = [
                    'uaString' => $userAgent,
                    'result' => $result
                ];
            } catch (\Exception $ex) {
                // skip this UA
                echo 'S';
            }
        }
    }
}

return [
    'provider' => [
        'proType' => 'testSuite',
        'proName' => 'MobileDetect',
        'proPackageName' => 'mobiledetect/mobiledetectlib',
        'proHomepage' => 'https://github.com/serbanghita/Mobile-Detect',
        
        'proCanDetectBrowserName' => 1,
        'proCanDetectBrowserVersion' => 1,
        
        'proCanDetectEngineName' => 0,
        'proCanDetectEngineVersion' => 0,
        
        'proCanDetectOsName' => 1,
        'proCanDetectOsVersion' => 1,
        
        'proCanDetectDeviceModel' => 1,
        'proCanDetectDeviceBrand' => 0,
        'proCanDetectDeviceType' => 0,
        'proCanDetectDeviceIsMobile' => 1,
        'proCanDetectDeviceIsTouch' => 0,
        
        'proCanDetectBotIsBot' => 0,
        'proCanDetectBotName' => 0,
        'proCanDetectBotType' => 0
    ],
    
    'userAgents' => $userAgents
];
