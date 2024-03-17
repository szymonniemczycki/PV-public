<?php
declare(strict_types=1);


session_start();

if (empty($_SESSION['userName'])) {
	header("Location: ./login.php");
} 

spl_autoload_register(function (string $classNamespace) {
	$path = str_replace(['\\', 'App/'], ['/', ''], $classNamespace);
	$path = "src/$path.php";
	require_once($path);
});

require_once("src/Utils/debug.php");  
$configuration = require_once("config/config.php");

use App\Exception\AppException;
use App\Exception\ConfigurationException;
use App\Controller;
use App\Request;
use App\Model\PriceModel;
use App\ErrorLogs;
use App\View;

$request = new Request($_GET, $_POST);
$errorLogs = new ErrorLogs();
$view = new View();

try {
	Controller::initConfiguration($configuration);
	(new Controller($request))->run();   
} catch (Throwable $e) {
	$errorLogs->saveErrorLog(
		$e->getFile() . " <br />line: " . $e->getLine(),
		$e->getMessage()
	);
	header("Location: ./?page=404");
}

?>
