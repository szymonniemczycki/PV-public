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

$request = new Request($_GET, $_POST);

try {
  Controller::initConfiguration($configuration);
  (new Controller($request))->run();   
} catch (ConfigurationException $e) {
  echo '<h1>Wystąpił błąd w aplikacji. </h1>';
  echo '<h3>ConfigurationException</h3>';
  dump($e);
} catch (StorageException $e) {
  echo '<h1>Wystąpił błąd w aplikacji. </h1>';
  echo '<h3>StorageException</h3>';
  dump($e);
} catch (NotFoundException $e) {
  echo '<h1>Wystąpił błąd w aplikacji. </h1>';
  echo "<h3>NotFoundException</h3>";
  dump($e);
} catch (AppException $e) {
  echo '<h1>Wystąpił błąd w aplikacji. </h1>';
  echo "<h3>AppException</h3>";
  dump($e);
} catch (PDOException $e) {
  echo '<h1>Wystąpił błąd w aplikacji. </h1>';
  echo "<h3>PDOException</h3>";
  dump($e);
} catch (Exception $e) {
  echo '<h1>Wystąpił błąd w aplikacji.... </h1>';
  echo "<h3>Exception</h3>";
  dump($e);
}
  
  ?>
