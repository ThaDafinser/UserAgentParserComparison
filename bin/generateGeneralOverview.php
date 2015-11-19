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
 * Get numbers
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
        SUM(botIsBot) as asBotDetected
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
				<h5 class="header col s12 light">
					We took <strong><?= $totalUserAgents; ?></strong> user agents <br />
					from <strong><?= $totalSources?></strong> test suites<br /> and
					analyzed them with <strong><?= $totalVendors; ?> parsers</strong>.<br />
					Here you can see the results!
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
					<th>Actions</th>
				</tr>

				<!-- result(s) -->
            <?php
            foreach ($result as $row) {
                ?>
            <tr>
					<th>
				   <?= $row['providerName']; ?><br /> <small><?= $row['providerVersion']; ?></small>
					</th>

					<td><?= $row['resultFound']; ?></td>
					<td><?= $row['browserResultFound']; ?></td>
					<td><?= $row['engineResultFound']; ?></td>
					<td><?= $row['osResultFound']; ?></td>

					<td><?= $row['deviceResultFound']; ?></td>
					<td><?= $row['deviceModelFound']; ?></td>
					<td><?= $row['deviceBrandFound']; ?></td>
					<td><?= $row['deviceTypeFound']; ?></td>

					<td><?= $row['asMobileDetected']; ?></td>
					<td>
				    <?= $row['asBotDetected']; ?> 
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
				<h5 class="header col s12 light">
					<p>The user agents were taken mainly out of the testsuites of the
						providers above. Thanks to all who provided them!</p>
				</h5>
			</div>

			<table class="striped">
				<tr>
					<th>Name</th>
					<th>Number of user agents</th>
					<th>Actions</th>
				</tr>
	       <?php foreach($sourceTestsuites as $source): ?>
	           <tr>
					<td><?= $source['name']; ?></td>
					<td><?= $source['count']; ?></td>
					<td><a href="https://github.com/<?= $source['name']; ?>"
						class="btn">Go to repo</a></td>
				</tr>
	       <?php endforeach; ?>
	   </table>
		</div>

		<div class="section">
			<div class="card">
				<div class="card-content">
					Comparison created by <a href="https://github.com/ThaDafinser">ThaDafinser
						(Martin Keckeis)</a><br /> Results generated with <a
						href="https://github.com/ThaDafinser/UserAgentParser">ThaDafinser/UserAgentParser</a>
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
