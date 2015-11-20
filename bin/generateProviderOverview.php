<?php
use UserAgentParserComparison\GenerateHtmlList;

include_once 'bootstrap.php';

$pdo = new PDO('sqlite:data/results.sqlite3');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/*
 * Get total UserAgents
 */
$sql = "
    SELECT
        COUNT(1)
    FROM userAgent
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();

$totalUserAgents = $result[0];
$totalUserAgentsOnePercent = $totalUserAgents / 100;

/*
 * Get total "should be bot"
 */
$sql = "
    SELECT
        COUNT(1)
    FROM userAgent
    WHERE
        `group` = 'bot'
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();

$totalShouldBeBot = $result[0];
$totalShouldBeBotOnePercent = $totalShouldBeBot / 100;

/*
 * Provider list
 */
$sql = "
    SELECT
        providerName
    FROM vendorResult
    GROUP BY
        providerName
    ORDER BY
        providerName
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$providers = array_column($result, 'providerName');

foreach ($providers as $providerName) {
    /*
     * Do quering per provider
     */
    /*
     * Get statistic data
     */
    $sql = "
        SELECT
            providerName,
            providerPackageName,
            providerVersion,
        
            SUM(resultFound) as resultFound,
            SUM(browserResultFound) as browserResultFound,
            SUM(engineResultFound) as engineResultFound,
            SUM(osResultFound) as osResultFound,
        
            SUM(deviceResultFound) as deviceResultFound,
            SUM(deviceModelFound) as deviceModelFound,
            SUM(deviceBrandFound) as deviceBrandFound,
            SUM(deviceTypeFound) as deviceTypeFound,
        
            SUM(deviceIsMobile) as asMobileDetected,
            
            SUM(botIsBot) as asBotDetected,
            COUNT(botName) as botName,
            COUNT(botType) as botType,
        
            AVG(parseTime) as avgParseTime
        FROM vendorResult
        WHERE providerName = :providerName
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':providerName', $providerName);
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    ob_start();
    ?>
<html>
<head>
<title>ThaDafinser/UserAgentParserComparison</title>

<link rel="stylesheet"
	href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.min.css">

</head>

<body>
	<div class="container">

		<div class="section">
			<h1 class="header center orange-text"><?= $row['providerName']; ?> - overview</h1>
			<div class="row center">
				<h5 class="header col s12 light">Here you find the details of this
					provider<br />
					<br />
					We analyzed <strong><?= $totalUserAgents; ?></strong> user agents</h5>
			</div>
		</div>

		<div class="section">

			<table class="striped">
				<!-- header -->
				<tr>
					<th>Group</th>
					<th>Percent</th>
					<th>Total</th>
					<th>Actions</th>
				</tr>
				
				<!-- results -->
				<tr>
				    <td>Results found</td>
				    <td>
                        <?= round($row['resultFound'] / $totalUserAgentsOnePercent, 2); ?> %
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['resultFound'] / $totalUserAgentsOnePercent, 2); ?>"></div>
						</div>
					</td>
				    <td>
                        <?= $row['resultFound']; ?>
					</td>
					<td>
					   <a href="noResult/atAll.html" class="btn waves-effect waves-light">Not found</a>
					</td>
				</tr>
				
				<tr>
				    <td>Browser found</td>
				    <td>
                        <?= round($row['browserResultFound'] / $totalUserAgentsOnePercent, 2); ?> %
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['browserResultFound'] / $totalUserAgentsOnePercent, 2); ?>"></div>
						</div>
					</td>
				    <td>
                        <?= $row['browserResultFound']; ?>
					</td>
					<td>
						<a href="grouped/browser.html" class="btn waves-effect waves-light">Detected names</a>
						<a href="noResult/browser.html"
						class="btn waves-effect waves-light">Not found</a>
					</td>
				</tr>
				<tr>
				    <td>Engine found</td>
				    <td>
                        <?= round($row['engineResultFound'] / $totalUserAgentsOnePercent, 2); ?> %
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['engineResultFound'] / $totalUserAgentsOnePercent, 2); ?>"></div>
						</div>
					</td>
				    <td>
                        <?= $row['engineResultFound']; ?>
					</td>
					<td>
					   <a href="grouped/engine.html" class="btn waves-effect waves-light">Detected names</a>
					   <a href="noResult/engine.html"
						class="btn waves-effect waves-light">Not found</a>
					</td>
				</tr>
				<tr>
				    <td>Operating system found</td>
				    <td>
                        <?= round($row['osResultFound'] / $totalUserAgentsOnePercent, 2); ?> %
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['osResultFound'] / $totalUserAgentsOnePercent, 2); ?>"></div>
						</div>
					</td>
				    <td>
                        <?= $row['osResultFound']; ?>
					</td>
					<td>
					   <a href="grouped/os.html" class="btn waves-effect waves-light">Detected names</a>
					   <a href="noResult/os.html"
						class="btn waves-effect waves-light">Not found</a>
					</td>
				</tr>
				
				<tr>
				    <td>Device detected</td>
				    <td>
                        <?= round($row['deviceResultFound'] / $totalUserAgentsOnePercent, 2); ?> %
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['deviceResultFound'] / $totalUserAgentsOnePercent, 2); ?>"></div>
						</div>
					</td>
				    <td>
                        <?= $row['deviceResultFound']; ?>
					</td>
					<td>
				       
					</td>
				</tr>
				<tr>
				    <td>Device model found</td>
				    <td>
                        <?= round($row['deviceModelFound'] / $totalUserAgentsOnePercent, 2); ?> %
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['deviceModelFound'] / $totalUserAgentsOnePercent, 2); ?>"></div>
						</div>
					</td>
				    <td>
                        <?= $row['deviceModelFound']; ?>
					</td>
					<td>
					   <a href="grouped/deviceModel.html" class="btn waves-effect waves-light">Detected models</a>
					</td>
				</tr>
				<tr>
				    <td>Device brand found</td>
				    <td>
                        <?= round($row['deviceBrandFound'] / $totalUserAgentsOnePercent, 2); ?> %
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['deviceBrandFound'] / $totalUserAgentsOnePercent, 2); ?>"></div>
						</div>
					</td>
				    <td>
                        <?= $row['deviceBrandFound']; ?>
					</td>
					<td>
					   <a href="grouped/deviceBrand.html" class="btn waves-effect waves-light">Detected brands</a>
					</td>
				</tr>
				<tr>
				    <td>Device type found</td>
				    <td>
                        <?= round($row['deviceTypeFound'] / $totalUserAgentsOnePercent, 2); ?> %
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['deviceTypeFound'] / $totalUserAgentsOnePercent, 2); ?>"></div>
						</div>
					</td>
				    <td>
                        <?= $row['deviceTypeFound']; ?>
					</td>
					<td>
					   <a href="grouped/deviceType.html" class="btn waves-effect waves-light">Detected types</a>
					</td>
				</tr>
				<tr>
				    <td>As mobile detected</td>
				    <td>
                        <?= round($row['asMobileDetected'] / $totalUserAgentsOnePercent, 2); ?> %
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['asMobileDetected'] / $totalUserAgentsOnePercent, 2); ?>"></div>
						</div>
					</td>
				    <td>
                        <?= $row['asMobileDetected']; ?>
					</td>
					<td>
					   
					</td>
				</tr>
				
				<tr>
				    <td>As bot detected</td>
				    <td>
                        <?= round($row['asBotDetected'] / $totalShouldBeBotOnePercent, 2); ?> %
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['asBotDetected'] / $totalShouldBeBotOnePercent, 2); ?>"></div>
						</div>
					</td>
				    <td>
                        <?= $row['asBotDetected']; ?>
					</td>
					<td>
					   <a href="bot/shouldBeABot.html"
						class="btn waves-effect waves-light">Not detected as bot</a>
					</td>
				</tr>
				<tr>
				    <td>Bot name found</td>
				    <td>
                        <?= round($row['botName'] / $totalShouldBeBotOnePercent, 2); ?> %
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['botName'] / $totalShouldBeBotOnePercent, 2); ?>"></div>
						</div>
					</td>
				    <td>
                        <?= $row['botName']; ?>
					</td>
					<td>
					   
					</td>
				</tr>
				<tr>
				    <td>Bot type found</td>
				    <td>
                        <?= round($row['botType'] / $totalShouldBeBotOnePercent, 2); ?> %
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['botType'] / $totalShouldBeBotOnePercent, 2); ?>"></div>
						</div>
					</td>
				    <td>
                        <?= $row['botType']; ?>
					</td>
					<td>
					   
					</td>
				</tr>
				
			</table>
		</div>

		<div class="section">
			<h2 class="header center orange-text">I'm done here</h2>
			<div class="row center">
				<a href="../index.html" class="btn-large waves-effect waves-light">
					Back to the overview </a>
			</div>
		</div>

		<div class="section" id="informations">
			<h2 class="header center orange-text">More informations</h2>
			<div class="row center">
				<h5 class="header light">
					The primary goal of this project is simple. I wanted to know which
					user agent parser is the most accurate in each part - device
					detection, bot detection and so on...<br /> <br /> The secondary
					goal is to provide a source for all user agent parsers to improve
					their detection based on this results.<br /> <br /> You can also
					improve this further, by suggesting ideads at <a
						href="https://github.com/ThaDafinser/UserAgentParserComparison/">ThaDafinser/UserAgentParserComparison</a><br />
					<br /> The comparison is based on the abstraction by <a
						href="https://github.com/ThaDafinser/UserAgentParser">ThaDafinser/UserAgentParser</a>
				</h5>
			</div>

			<div class="card">
				<div class="card-content">
					Comparison created <i><?= date('Y-m-d H:i:s'); ?></i> | by <a
						href="https://github.com/ThaDafinser">ThaDafinser 
				
				</div>
			</div>
		</div>
	</div>
</body>
    <?php
    $path = 'results/' . $providerName;
    
    if (! file_exists($path)) {
        mkdir($path, null, true);
    }
    
    file_put_contents($path . '/index.html', ob_get_contents());
    ob_end_clean();
}

echo 'finished...';
