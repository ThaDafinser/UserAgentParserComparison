<?php
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
 * Get total sources
 */
$sql = "
    SELECT
        COUNT(DISTINCT source)
    FROM userAgent
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();

$totalSources = $result[0];

/*
 * testsuites
 */
$sql = "
    SELECT
        COUNT(1) count,
        source name
    FROM userAgent
    GROUP BY 
        source
";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$sourceTestsuites = $stmt->fetchAll();

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
    
        AVG(parseTime) as avgParseTime
    FROM vendorResult
    GROUP BY
        providerName
    ORDER BY 
        providerName
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalVendors = count($result);

ob_start();
?>
<html>
<head>
<title>ThaDafinser/UserAgentParserComparison</title>

<!--Import Google Icon Font-->
<link href="http://fonts.googleapis.com/icon?family=Material+Icons"
	rel="stylesheet">

<!-- Compiled and minified CSS -->
<link rel="stylesheet"
	href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.min.css">
</head>

<body>
	<div class="container">

		<div class="section">
			<h1 class="header center orange-text">UserAgent parser comparison</h1>
			<div class="row center">
				<h5 class="header light">
					We took <strong><?= $totalUserAgents; ?></strong> user agents <br />
					from <strong><?= $totalSources?></strong> test suites<br /> and
					analyzed them with <strong><?= $totalVendors; ?> providers</strong>.<br />
					Here you can see the results!<br />
				</h5>
			</div>
		</div>


		<div class="section">

			<table class="striped">

				<!-- header -->
				<tr>
					<th>Provider</th>

					<th>Results</th>
					<th>Browser</th>
					<th>Rendering engine</th>
					<th>Operating system</th>

					<th>Device</th>
					<th>Model</th>
					<th>Brand</th>
					<th>Type</th>

					<th>Is mobile</th>
					<th><a class="tooltipped" data-position="bottom" data-delay="50"
						data-tooltip="<?= $totalShouldBeBot; ?> user agents are known bots! Number of detected bots can still be higher">
							Is bot <i class="material-icons right">info_outline</i>
					</a></th>

					<th><a class="tooltipped" data-position="bottom" data-delay="50"
						data-tooltip="Stock provider or only file cache is used and it was generated on Windows! So this can be in the real world a lot faster...">
							Parse time <i class="material-icons right">info_outline</i>
					</a></th>

					<th>Actions</th>
				</tr>

				<!-- result(s) -->
            <?php
            foreach ($result as $row) {
                ?>
                <tr>
					<th><a
						href="https://github.com/<?= $row['providerPackageName']; ?>"><?= $row['providerName']; ?></a><br />
						<small><?= $row['providerVersion']; ?></small></th>

					<td>
                        <?= $row['resultFound']; ?>
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['resultFound'] / $totalUserAgentsOnePercent, 0); ?>"></div>
						</div>
					</td>
					<td>
                        <?= $row['browserResultFound']; ?>
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['browserResultFound'] / $totalUserAgentsOnePercent, 0); ?>"></div>
						</div>
					</td>
					<td>
                        <?= $row['engineResultFound']; ?>
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['engineResultFound'] / $totalUserAgentsOnePercent, 0); ?>"></div>
						</div>
					</td>
					<td>
                        <?= $row['osResultFound']; ?>
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['osResultFound'] / $totalUserAgentsOnePercent, 0); ?>"></div>
						</div>
					</td>

					<td>
                        <?= $row['deviceResultFound']; ?>
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['deviceResultFound'] / $totalUserAgentsOnePercent, 0); ?>"></div>
						</div>
					</td>
					<td>
                        <?= $row['deviceModelFound']; ?>
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['deviceModelFound'] / $totalUserAgentsOnePercent, 0); ?>"></div>
						</div>
					</td>
					<td>
                        <?= $row['deviceBrandFound']; ?>
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['deviceBrandFound'] / $totalUserAgentsOnePercent, 0); ?>"></div>
						</div>
					</td>
					<td>
                        <?= $row['deviceTypeFound']; ?>
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['deviceTypeFound'] / $totalUserAgentsOnePercent, 0); ?>"></div>
						</div>
					</td>
					<td>
                        <?= $row['asMobileDetected']; ?>
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['asMobileDetected'] / $totalUserAgentsOnePercent, 0); ?>"></div>
						</div>
					</td>

					<td>
                        <?= $row['asBotDetected']; ?>
					    <div class="progress">
							<div class="determinate" style="width: <?= round($row['asBotDetected'] / $totalShouldBeBotOnePercent, 0); ?>"></div>
						</div>
					</td>

					<td>
				        <?= round($row['avgParseTime'], 5); ?> 
		              </td>

					<td><a href="<?= $row['providerName']; ?>/index.html"
						class="btn waves-effect waves-light">Details</a></td>
				</tr>
            <?php
            }
            ?>
		</table>
		</div>

		<div class="section">
			<h2 class="header center orange-text">Not enough?</h2>
			<div class="row center">
				<h5 class="header col s12 light">
					<p>You can go to the details of each provider, or see here the
						results of all analyzed user agents</p>

					<a href="detail.html" class="btn-large waves-effect waves-light">View
						all results</a>
				</h5>
			</div>
		</div>

		<div class="section">
			<h2 class="header center orange-text">Source of user agents</h2>
			<div class="row center">
				<h5 class="header col s12 light">The user agents were taken mainly
					out of the testsuites of the providers above. Thanks to all who
					provided them!</h5>
			</div>

			<table class="striped">
				<tr>
					<th>Name</th>
					<th>Number of user agents</th>
				</tr>
				
	           <?php foreach($sourceTestsuites as $source): ?>
	           <tr>
					<td><a href="https://github.com/<?= $source['name']; ?>"><?= $source['name']; ?></a></td>
					<td><?= $source['count']; ?></td>
				</tr>
	           <?php endforeach; ?>
	           
	       </table>
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

	<!-- Compiled and minified JavaScript -->
	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	<script
		src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js"></script>

	<script>
$(document).ready(function(){
    $('.tooltipped').tooltip();
});
</script>
</body>

</html>
<?php

file_put_contents('results/index.html', ob_get_contents());
ob_end_clean();

echo 'finished...';
