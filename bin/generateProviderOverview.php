<?php
use UserAgentParserComparison\GenerateHtmlList;

include_once 'bootstrap.php';

$pdo = new PDO('sqlite:data/results.sqlite3');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

foreach($providers as $providerName){
    ob_start();
    
    ?>
<html>
<head>
    <title>ThaDafinser/UserAgentParserComparison</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.min.css">

</head>

<body>
<div class="container">

	<div class="section">
		<h1 class="header center orange-text"><?= $providerName; ?> - overview</h1>
		<div class="row center">
			<h5 class="header col s12 light">
			    Here you will find all details of this provider (work in progress)<br />
			    Currently you only find the result lists
			</h5>
			
			<p>
			 <a href="../index.html" class="btn-large waves-effect waves-light">
                Back to the overview
            </a>
			</p>
		</div>
	</div>
	
	<div class="section center">
	   <h2>
	       Details
	   </h2>
        <p>
        <a href="noResultFound.html" class="btn-large waves-effect waves-light">
            No result found
        </a>
        </p>
        
        <p>
        <a href="notDetectedAsBot.html" class="btn-large waves-effect waves-light">
            Not detected as bot
        </a>
        </p>
        
        <p>
        <a href="noBrowserResultFound.html" class="btn-large waves-effect waves-light">
            Browser not detected
        </a>
        </p>
        
        <p>
        <a href="noRenderingEngineResultFound.html" class="btn-large waves-effect waves-light">
            Engine not detected
        </a>
        </p>
        
        <p>
        <a href="noOperatingSystemResultFound.html" class="btn-large waves-effect waves-light">
            Operating system not detected
        </a>
        </p>
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
