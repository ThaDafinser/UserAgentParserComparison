<?php
use UserAgentParser\Provider\WhichBrowser;

function hydrateWhichbrowser($data, array $row, $userAgent)
{
    $data['uaRawResult'] = serialize($row);
    
    $result = $row['result'];
    
    $provider = new WhichBrowser();
    
    $result = $provider->parse($userAgent);
    
    if ($result->getBot()->getIsBot()) {
        $data['uaBotIsBot'] = $result->getBot()->getIsBot();
        $data['uaBotName'] = $result->getBot()->getName();
        
        return $data;
    }
    
    $data['uaBrowserName'] = $result->getBrowser()->getName();
    $data['uaBrowserVersion'] = $result->getBrowser()
        ->getVersion()
        ->getComplete();
    $data['uaEngineName'] = $result->getRenderingEngine()->getName();
    $data['uaEngineVersion'] = $result->getRenderingEngine()
        ->getVersion()
        ->getComplete();
    $data['uaOsName'] = $result->getOperatingSystem()->getName();
    $data['uaOsVersion'] = $result->getOperatingSystem()
        ->getVersion()
        ->getComplete();
    
    $data['uaDeviceModel'] = $result->getDevice()->getModel();
    $data['uaDeviceBrand'] = $result->getDevice()->getBrand();
    $data['uaDeviceType'] = $result->getDevice()->getType();
    $data['uaDeviceIsMobile'] = $result->getDevice()->getIsMobile();
    
    return $data;
}

/*
 * Fixtures 1) all expect bots.yml
 */
$path = 'vendor/whichbrowser/parser/tests/data';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
$files = new RegexIterator($iterator, '/^.+\.yaml$/i', RecursiveRegexIterator::GET_MATCH);

$allData = [];

foreach ($files as $file) {
    $file = $file[0];
    
    $fixtureData = \Spyc::YAMLLoad($file);
    
    if (! is_array($fixtureData)) {
        throw new \Exception('wrong result!');
    }
    
    foreach ($fixtureData as $row) {
        
        if (! isset($row['headers']['User-Agent'])) {
            $headers = http_parse_headers($row['headers']);
            
            if (! isset($headers['User-Agent'])) {
                echo 'Skip...';
                continue;
                
                var_dump($row['headers']);
                var_dump($headers);
                var_dump($file);
                exit();
            }
            
            $userAgent = $headers['User-Agent'];
        } else {
            $userAgent = $row['headers']['User-Agent'];
        }
        
        $data = [
            'uaSource' => 'whichbrowser/parser',
            'uaFileName' => $file,
            
            'uaString' => $userAgent,
            
            'uaBrowserName' => null,
            'uaBrowserVersion' => null,
            
            'uaEngineName' => null,
            'uaEngineVersion' => null,
            
            'uaOsName' => null,
            'uaOsVersion' => null,
            
            'uaDeviceModel' => null,
            'uaDeviceBrand' => null,
            'uaDeviceType' => null,
            'uaDeviceIsMobile' => null,
            'uaDeviceIsTouch' => null,
            
            'uaBotIsBot' => null,
            'uaBotName' => null,
            'uaBotType' => null
        ];
        
        try {
            $data = hydrateWhichbrowser($data, $row, $userAgent);
            
            $allData[] = $data;
        } catch (\UserAgentParser\Exception\NoResultFoundException $ex) {
            continue;
        }
    }
}

return $allData;
