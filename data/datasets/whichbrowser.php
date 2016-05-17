<?php
use UserAgentParser\Provider\WhichBrowser;
use WhichBrowser\Model\Version;
use WhichBrowser\Model\Main;
use WhichBrowser\Model\Family;
use WhichBrowser\Model\Using;
use Symfony\Component\Yaml\Yaml;

function getWhichbrowserVersion($version)
{
    if (! is_array($version)) {
        $version = [
            'value' => $version
        ];
    }
    
    foreach ($version as $key => $value) {
        if (! in_array($key, [
            'value',
            'hidden',
            'nickname',
            'alias',
            'details',
            'builds'
        ])) {
            throw new \Exception('Unknown version key: ' . $key);
        }
    }
    
    return new Version($version);
}

function hydrateWhichbrowser($data, array $row, array $headers)
{
    if (isset($row['engine']) || isset($row['features']) || isset($row['useragent'])) {
        throw new \Exception('client detection...');
    }
    
    $data['resRawResult'] = serialize($row);
    
    $result = $row['result'];
    
    /*
     * Hydrate...
     */
    $main = new Main();
    
    if (isset($result['browser'])) {
        
        $toUse = [];
        
        foreach ($result['browser'] as $key => $value) {
            
            if ($key == 'name') {
                $toUse['name'] = $value;
            } elseif ($key == 'type') {
                $toUse['type'] = $value;
            } elseif ($key == 'alias') {
                $toUse['alias'] = $value;
            } elseif ($key == 'version') {
                $toUse['version'] = getWhichbrowserVersion($value);
            } elseif ($key == 'using') {
                $usingToUse = [];
                
                if (! is_array($value)) {
                    $usingToUse['name'] = $value;
                }
                if (isset($value['name'])) {
                    $usingToUse['name'] = $value['name'];
                }
                if (isset($value['version'])) {
                    $usingToUse['version'] = getWhichbrowserVersion($value['version']);
                }
                
                $toUse['using'] = new Using($usingToUse);
            } elseif ($key == 'family') {
                $familyToUse = [];
                
                if (! is_array($value)) {
                    $familyToUse['name'] = $value;
                }
                if (isset($value['name'])) {
                    $familyToUse['name'] = $value['name'];
                }
                if (isset($value['version'])) {
                    $familyToUse['version'] = getWhichbrowserVersion($value['version']);
                }
                
                $toUse['family'] = new Family($familyToUse);
            } else {
                throw new \Exception('unknown key: ' . $key . ' / ' . print_r($value, true));
            }
        }
        
        $main->browser->set($toUse);
    }
    
    if (isset($result['engine'])) {
        
        $toUse = [];
        
        foreach ($result['engine'] as $key => $value) {
            
            if ($key == 'name') {
                $toUse['name'] = $value;
            } elseif ($key == 'version') {
                $toUse['version'] = getWhichbrowserVersion($value);
            } else {
                throw new \Exception('unknown key: ' . $key . ' / ' . print_r($value, true));
            }
        }
        
        $main->engine->set($toUse);
    }
    
    if (isset($result['os'])) {
        
        $toUse = [];
        
        foreach ($result['os'] as $key => $value) {
            
            if ($key == 'name') {
                $toUse['name'] = $value;
            } elseif ($key == 'alias') {
                $toUse['alias'] = $value;
            } elseif ($key == 'family') {
                $familyToUse = [];
                
                if (! is_array($value)) {
                    $familyToUse['name'] = $value;
                }
                if (isset($value['name'])) {
                    $familyToUse['name'] = $value['name'];
                }
                if (isset($value['version'])) {
                    $familyToUse['version'] = getWhichbrowserVersion($value['version']);
                }
                
                $toUse['family'] = new Family($familyToUse);
            } elseif ($key == 'version') {
                $toUse['version'] = getWhichbrowserVersion($value);
            } else {
                throw new \Exception('unknown key: ' . $key . ' / ' . print_r($value, true));
            }
        }
        
        $main->os->set($toUse);
    }
    
    if (isset($result['device'])) {
        
        $toUse = [];
        
        foreach ($result['device'] as $key => $value) {
            
            if ($key == 'type') {
                $toUse['type'] = $value;
            } elseif ($key == 'subtype') {
                $toUse['subtype'] = $value;
            } elseif ($key == 'manufacturer') {
                $toUse['manufacturer'] = $value;
            } elseif ($key == 'model') {
                $toUse['model'] = $value;
            } elseif ($key == 'series') {
                $toUse['series'] = $value;
            } elseif ($key == 'carrier') {
                $toUse['carrier'] = $value;
            } else {
                throw new \Exception('unknown key: ' . $key . ' / ' . $value);
            }
        }
        
        $main->device->setIdentification($toUse);
    }
    
    if (isset($result['camouflage'])) {
        $main->camouflage = $result['camouflage'];
    }
    
    $resultArray = $main->toArray();
    
    $provider = new WhichBrowser();
    $resultParser = $provider->parse($headers['User-Agent'], $headers);
    
    $compare1 = $main->toArray();
    $compare2 = $resultParser->getProviderResultRaw();
    
    if ($compare1 !== $compare2) {
        if (count($headers) > 1) {
            echo 'O';
        } else {
            echo 'E';
        }
    }
    
    /*
     * convert to our result
     */
    if ($main->getType() === 'bot') {
        $data['resBotIsBot'] = 1;
        
        if ($main->browser->getName() != '') {
            $data['resBotName'] = $main->browser->getName();
        }
        
        return $data;
    }
    
    if ($main->browser->getName() != '') {
        $data['resBrowserName'] = $main->browser->getName();
        
        if ($main->browser->getVersion() != '') {
            $data['resBrowserVersion'] = $main->browser->getVersion();
        }
    } elseif (isset($main->browser->using) && $main->browser->using instanceof \WhichBrowser\Model\Using && $main->browser->using->getName() != '') {
        $data['resBrowserName'] = $main->browser->using->getName();
        
        if ($main->browser->using->getVersion() != '') {
            $data['resBrowserVersion'] = $main->browser->using->getVersion();
        }
    }
    
    if ($main->engine->getName() != '') {
        $data['resEngineName'] = $main->engine->getName();
    }
    if ($main->engine->getVersion() != '') {
        $data['resEngineVersion'] = $main->engine->getVersion();
    }
    
    if ($main->os->getName() != '') {
        $data['resOsName'] = $main->os->getName();
    }
    if ($main->os->getVersion() != '') {
        $data['resOsVersion'] = $main->os->getVersion();
    }
    
    if ($main->device->getModel() != '') {
        $data['resDeviceModel'] = $main->device->getModel();
    }
    if ($main->device->getManufacturer() != '') {
        $data['resDeviceBrand'] = $main->device->getManufacturer();
    }
    if ($main->getType() != '') {
        $data['resDeviceType'] = $main->getType();
    }
    if ($main->isMobile() != '') {
        $data['resDeviceIsMobile'] = $main->isMobile();
    }
    
    return $data;
}

/*
 * Load function
 */
if (! function_exists('http_parse_headers')) {
    require_once 'vendor/whichbrowser/parser/tests/src/polyfills.php';
}

/*
 * Fixtures 1)
 */
$path = 'vendor/whichbrowser/parser/tests/data';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
$files = new RegexIterator($iterator, '/^.+\.yaml$/i', RecursiveRegexIterator::GET_MATCH);

$userAgents = [];

foreach ($files as $file) {
    
    $file = $file[0];
    
    $fixtureData = Yaml::parse(file_get_contents($file));
    
    if (! is_array($fixtureData)) {
        throw new \Exception('wrong result!');
    }
    
    foreach ($fixtureData as $row) {
        
        if (! isset($row['headers']['User-Agent'])) {
            $headers = http_parse_headers($row['headers']);
            
            if (! isset($headers['User-Agent'])) {
                echo 'S';
                continue;
            }
        } else {
            $headers = $row['headers'];
        }
        
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
            $result = hydrateWhichbrowser($data, $row, $headers);
            
            $key = bin2hex(sha1($headers['User-Agent'], true));
            
            $toInsert = [
                'uaString' => $headers['User-Agent'],
                'result' => $result
            ];
            
            if(count($headers) > 1){
                unset($headers['User-Agent']);
                
                $toInsert['uaAdditionalHeaders'] = $headers;
            }
            
            $userAgents[$key] = $toInsert;
            
            echo '.';
        } catch (\Exception $ex) {
            // skip this UA
            echo 'S';
        }
    }
}

return [
    'provider' => [
        'proType' => 'testSuite',
        'proName' => 'WhichBrowser',
        'proPackageName' => 'whichbrowser/parser',
        'proHomepage' => 'https://github.com/WhichBrowser/Parser',
        
        'proCanDetectBrowserName' => 1,
        'proCanDetectBrowserVersion' => 1,
        
        'proCanDetectEngineName' => 1,
        'proCanDetectEngineVersion' => 1,
        
        'proCanDetectOsName' => 1,
        'proCanDetectOsVersion' => 1,
        
        'proCanDetectDeviceModel' => 1,
        'proCanDetectDeviceBrand' => 1,
        'proCanDetectDeviceType' => 1,
        'proCanDetectDeviceIsMobile' => 0,
        'proCanDetectDeviceIsTouch' => 0,
        
        'proCanDetectBotIsBot' => 1,
        'proCanDetectBotName' => 1,
        'proCanDetectBotType' => 0
    ],
    
    'userAgents' => $userAgents
];
