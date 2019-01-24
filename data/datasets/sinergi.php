<?php
//$file = 'vendor/sinergi/browser-detector/tests/BrowserDetector/Tests/_files/UserAgentStrings.xml';
//$content = file_get_contents($file);
//
//$xml = new SimpleXmlElement($content);
//
//$userAgents = [];
//
//foreach ($xml->strings->string as $string) {
//
//    $data = [
//        'resFilename' => $file,
//
//        'resBrowserName' => null,
//        'resBrowserVersion' => null,
//
//        'resEngineName' => null,
//        'resEngineVersion' => null,
//
//        'resOsName' => null,
//        'resOsVersion' => null,
//
//        'resDeviceModel' => null,
//        'resDeviceBrand' => null,
//        'resDeviceType' => null,
//        'resDeviceIsMobile' => null,
//        'resDeviceIsTouch' => null,
//
//        'resBotIsBot' => null,
//        'resBotName' => null,
//        'resBotType' => null
//    ];
//
//    $string = $string->field;
//
//    $browserName = (string) $string[0];
//    $browserVersion = (string) $string[1];
//
//    $osName = (string) $string[2];
//    $osVersion = (string) $string[3];
//
//    $deviceModel = (string) $string[4];
//
//    $ua = (string) $string[6];
//    $ua = str_replace("\n", ' ', $ua);
//    $ua = preg_replace('!\s+!', ' ', $ua);
//
//    if($browserName != ''){
//        $data['resBrowserName'] = $browserName;
//    }
//    if($browserVersion != ''){
//        $data['resBrowserVersion'] = $browserVersion;
//    }
//
//    if($osName != ''){
//        $data['resOsName'] = $osName;
//    }
//    if($osVersion != ''){
//        $data['resOsVersion'] = $osVersion;
//    }
//
//    if($deviceModel != ''){
//        $data['resDeviceModel'] = $deviceModel;
//    }
//
//    $userAgents[bin2hex(sha1($ua, true))] = [
//        'uaString' => $ua,
//        'result' => $data
//    ];
//}

return [
    'provider' => [
        'proType' => 'testSuite',
        'proName' => 'SinergiBrowserDetector',
        'proPackageName' => 'sinergi/browser-detector',
        'proHomepage' => 'https://github.com/sinergi/php-browser-detector',
        
        'proCanDetectBrowserName' => 1,
        'proCanDetectBrowserVersion' => 1,
        
        'proCanDetectEngineName' => 0,
        'proCanDetectEngineVersion' => 0,
        
        'proCanDetectOsName' => 1,
        'proCanDetectOsVersion' => 1,
        
        'proCanDetectDeviceModel' => 1,
        'proCanDetectDeviceBrand' => 0,
        'proCanDetectDeviceType' => 0,
        'proCanDetectDeviceIsMobile' => 0,
        'proCanDetectDeviceIsTouch' => 0,
        
        'proCanDetectBotIsBot' => 0,
        'proCanDetectBotName' => 0,
        'proCanDetectBotType' => 0
    ],
    
    'userAgents' => []
];