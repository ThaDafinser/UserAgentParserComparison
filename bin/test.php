<?php
use UserAgentParser\Exception\NoResultFoundException;

include_once 'bootstrap.php';

// $userAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0_1 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A523 Safari/8536.25';
// $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36';
// $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0';
$userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';
// $userAgent = 'Mozilla/5.0 (compatible; Yahoo! Slurp/3.0; http://help.yahoo.com/help/us/ysearch/slurp) NOT Firefox/3.5';
// $userAgent = '';
// $userAgent = 'Mozilla/5.0 (iPod; U; CPU iPhone OS 4_2_1 like Mac OS X; ja-jp) AppleWebKit/533.17.9 (KHTML, like Gecko) Mobile/8C148';

// errors
// $userAgent = 'Kodi/14.0 (Macintosh; Intel Mac OS X 10_10_3) App_Bitness/64 Version/14.0-Git:2014-12-23-ad747d9-dirty';
// $userAgent = 'Microsoft Office/14.0 (Windows NT 5.1; Microsoft Outlook 14.0.4536; Pro; MSOffice 14)';
// $userAgent = 'Mozilla/5.0 (Linux; U; Android 4.1.1; el-gr; MB525 Build/JRO03H; CyanogenMod-10) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30';
// $userAgent = 'Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25';

// $userAgent = 'Mozilla/5.0 (Linux; U; Android 4.1.2; fr-fr; Archos 53 Platinum Build/JZO54K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30';
// $userAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; Crazy Browser 3.0.0 Beta2)';
// $userAgent = 'Mozilla/5.0 (Linux; Android 4.3; AOSP on iC-DPC Build/OTI-Rel11.4_2015-04-07) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.96 Mobile Safari/537.36';

// version with alias
// $userAgent = 'Kodi/14.0 (Macintosh; Intel Mac OS X 10_10_3) App_Bitness/64 Version/14.0-Git:2014-12-23-ad747d9-dirty';
// $userAgent = 'Microsoft Office/14.0 (Windows NT 5.1; Microsoft Outlook 14.0.4536; Pro; MSOffice 14)';
// $userAgent = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; InfoPath.2; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET4.0C; .NET4.0E; TheWorld)';

// BETA / ALPHA
// $userAgent = 'Mozilla/5.0 (X11; U; Linux i686; xx; rv:1.9b5) Gecko/2008052519 CentOS/3.0b5-0.beta5.6.el5.centos Firefox/3.0b5';
// $userAgent = 'Mozilla/5.0 (Android 4.3.1; rv:6.0) Gecko/20100101 Firefox/26.0prealpha';

// $userAgent = 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.3; Trident/4.0)';

/* @var $chain \UserAgentParser\Provider\Chain */
$chain = include 'bin/getChainProvider.php';

foreach ($chain->getProviders() as $provider) {
    echo '~~~' . $provider->getName() . '~~~' . PHP_EOL;
    
    try {
        $result = $provider->parse($userAgent);
        
        $resultArray = $result->toArray(true);
        
        var_dump($result->getProviderResultRaw());
//         var_dump($resultArray['providerResultRaw']);
    } catch (NoResultFoundException $ex) {
        echo 'not found...' . PHP_EOL;
    }
}
