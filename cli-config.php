<?php
// cli-config.php
require_once "bootstrap.php";
$app = new App();
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($app->entityManager);
