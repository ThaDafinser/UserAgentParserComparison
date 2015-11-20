<?php
include_once 'bootstrap.php';
/*
 * add some indexes for query
 */
$pdo = new PDO('sqlite:data/results.sqlite3');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/*
 * Browser
 */
$sql = "
    DROP INDEX IF EXISTS index_browserName;
    CREATE INDEX index_browserName
    ON vendorResult (browserName);
";
$pdo->exec($sql);

/*
 * Engine
 */
$sql = "
    DROP INDEX IF EXISTS index_engineName;
    CREATE INDEX index_engineName
    ON vendorResult (engineName);
";
$pdo->exec($sql);

/*
 * OS
 */
$sql = "
    DROP INDEX IF EXISTS index_osName;
    CREATE INDEX index_osName
    ON vendorResult (osName);
";
$pdo->exec($sql);

/*
 * model
 */
$sql = "
    DROP INDEX IF EXISTS index_deviceModel;
    CREATE INDEX index_deviceModel
    ON vendorResult (deviceModel);
";
$pdo->exec($sql);

/*
 * brand
 */
$sql = "
    DROP INDEX IF EXISTS index_deviceBrand;
    CREATE INDEX index_deviceBrand
    ON vendorResult (deviceBrand);
";
$pdo->exec($sql);

/*
 * type
 */
$sql = "
    DROP INDEX IF EXISTS index_deviceType;
    CREATE INDEX index_deviceType
    ON vendorResult (deviceType);
";
$pdo->exec($sql);