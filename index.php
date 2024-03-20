<?php
declare(strict_types=1);


session_start();

//check if session exist
if (empty($_SESSION['userName'])) {
	header("Location: ./login.php");
} 

//generete path for used Classes
spl_autoload_register(function (string $classNamespace) {
	$path = str_replace(['\\', 'App/'], ['/', ''], $classNamespace);
	$path = "src/$path.php";
	require_once($path);
});

require_once("src/Utils/debug.php");  
$configuration = require_once("config/config.php");

//used Classed
// use App\Exception\AppException;
// use App\Exception\ConfigurationException;
use App\Controller;
use App\Request;
use App\Model\PriceModel;
use App\ErrorLogs;
use App\View;

//create new objects
$request = new Request($_GET, $_POST);
$errorLogs = new ErrorLogs();
$view = new View();

//start render webpage - begginig of application
try {
	Controller::initConfiguration($configuration);
	(new Controller($request))->run();   
} catch (Throwable $e) {
	$errorLogs->saveErrorLog(
		$e->getFile() . " <br />line: " . $e->getLine(),
		$e->getMessage()
	);
	header("Location: ./404.php");
}

?>
