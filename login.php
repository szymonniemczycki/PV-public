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

use App\Model\UserModel;

if (!empty($_POST['name']) && !empty($_POST['password'])) {
  $postSaveLogin = htmlentities((string) $_POST['name']);
  $postSavePass = htmlentities((string) $_POST['password']);
  
  $usersModel = (new UserModel($configuration['db']));
  $userExist = $usersModel->checkCredential($postSaveLogin, $postSavePass);
  
  if ((int) $userExist) {
//  if ((int) $userExist) { bez (int)
    $_SESSION['userName'] = $postSaveLogin;
    $usersModel->updateLastLogin($postSaveLogin);
    $usersModel->userLoginLog($postSaveLogin, "successful");
    header("Location: ./");
  } else {
    $msg = "wrongHash";
    $usersModel->userLoginLog($postSaveLogin, "incorrect credentials");
    include("templates/pages/showInfo.php");  
  }
  
} elseif ((empty($_POST['name']) || empty($_POST['password'])) && isset($_POST['tried'])) {
  $usersModel = (new UserModel($configuration['db']));
  $usersModel->userLoginLog($_POST['name'], "missing data");
  //dodać obsługę pass
  $msg = "noHash";
  include("templates/pages/showInfo.php");
}
?>

<html lang="pl">

<?php require_once("templates/header.php"); ?>

  <body class="body">
    <div class="login">
      <?php if (empty($_SESSION['user'])) { ?>
        <form class="loginForm" action="login.php" method="post">
          <input type="text" name="name" placeholder="login" /> 
          <br/> 
          <input type="password" name="password" placeholder="password" /> 
          <br/>  
          <input type="hidden" name="tried" value="true" />
          <button class="btnLogin" type="submit">LOG IN</button>
        </form>
      <?php } ?>
      </div>
  </body>
</html>