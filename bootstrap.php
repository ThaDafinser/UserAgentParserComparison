<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once 'vendor/autoload.php';

/*
 * General settings
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

set_time_limit(- 1);
ini_set('memory_limit', '1024M');

require_once 'config.php';

/*
 * Doctrine
 */
$isDevMode = true;

$config = Setup::createAnnotationMetadataConfiguration(array(
    __DIR__ . '/src/Entity'
), $isDevMode);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);

\Doctrine\DBAL\Types\Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');
$entityManager->getConnection()
    ->getDatabasePlatform()
    ->registerDoctrineTypeMapping('uuid', 'uuid');
   