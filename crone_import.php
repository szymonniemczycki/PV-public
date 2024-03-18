<?php

declare(strict_types=1);

//generete path for used Classes
spl_autoload_register(function (string $classNamespace) {
  $path = str_replace(['\\', 'App/'], ['/', ''], $classNamespace);
  $path = "src/$path.php";
  require_once($path);
});

require_once("src/Utils/debug.php");  

//used Classed
use App\Exception\AppException;
use App\Exception\ConfigurationException;
use App\Controller;
use App\Request;
use App\Model\PriceModel;

$configuration = require_once("config/config.php");


//create and run object for start crone
Crone::initConfiguration($configuration);
(new Crone())->startImportCrone();