<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

class App
{
    private $isDevMode = true;
    private $config;
// database configuration parameters
    private $conn = array(
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/db.sqlite',
    );
    public $entityManager;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/model"), $this->isDevMode);
        $this->entityManager = EntityManager::create($this->conn, $this->config);
    }

}
