<?php
declare(strict_types=1);

session_start();

spl_autoload_register(function (string $classNamespace) {
  $path = str_replace(['\\', 'App/'], ['/', ''], $classNamespace);
  $path = "src/$path.php";
  require_once($path);
});

require_once("src/Utils/debug.php");  
use GetPrice;
use PDO;
use PDOException;
use DateTime;
use DateTimeZone;

use App\Exception\AppException;
use App\Exception\ConfigurationException;
use App\Controller;
use App\Request;
use App\Model\PriceModel;

$configuration = require_once("config/config.php");


Crone::initConfiguration($configuration);
(new Crone())->startImportCrone();