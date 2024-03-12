<?php
declare(strict_types=1);

session_start();
require_once("src/Utils/debug.php");  

spl_autoload_register(function (string $classNamespace) {
  $path = str_replace(['\\', 'App/'], ['/', ''], $classNamespace);
  $path = "src/$path.php";
  require_once($path);
});

$configuration = require_once("config/config.php");

use App\Exception\Exception;
use App\Exception\Throwable;

use App\Model\UsersModel;

if (!empty($_POST['name']) && !empty($_POST['pass'])) {
  $postSaveLogin = htmlentities((string) $_POST['name']);
  $postSavePass = htmlentities((string) $_POST['pass']);
  
  $usersModel = (new UsersModel($configuration['db']));
  $userExist = $usersModel->checkCredential($postSaveLogin, $postSavePass);
  
  if ((int) $userExist) {
    $_SESSION['userName'] = $postSaveLogin;
    $usersModel->updateLastLogin($postSaveLogin);
    $usersModel->userLoginLog($postSaveLogin, "successful");
    header("Location: ./");
  } else {
    $msg = "wrongHash";
    $usersModel->userLoginLog($postSaveLogin, "incorrect credentials");
    include("templates/pages/showInfo.php");  
  }
  
} elseif ((empty($_POST['name']) || empty($_POST['pass'])) && isset($_POST['tried'])) {
  $usersModel = (new UsersModel($configuration['db']));
  $usersModel->userLoginLog($_POST['name'], "missing data");
  $msg = "noHash";
  include("templates/pages/showInfo.php");
}
?>

<html lang="pl">

  <head>
    <title>RCE importer</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <link href="./public/style.css" rel="stylesheet">
  </head>

  <body class="body">
  <div class="login">
    <?php if (empty($_SESSION['user'])) : ?>
      <form class="loginForm" action="login.php" method="post">
        <input type="text" name="name" placeholder="login" /> 
        <br/> 
        <input type="password" name="pass" placeholder="password" /> 
        <br/>  
        <input type="hidden" name="tried" value="true" />
        <button class="btnLogin" type="submit">LOG IN</button>
      </form>
    <?php endif; ?>
    </div>
  </body>
</html>