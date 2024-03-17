<?php
declare(strict_types=1);

session_start();

require_once("src/Utils/debug.php");  

spl_autoload_register(function (string $classNamespace) {
	$path = str_replace(['\\', 'App/'], ['/', ''], $classNamespace);
	$path = "src/$path.php";
	require_once($path);
});

use App\Exception\Exception;
use App\Exception\Throwable;
use App\Model\UserModel;
use App\ErrorLogs;

$errorLogs = new ErrorLogs();

try {
	$configuration = require_once("config/config.php");
} catch (Error $e) { 
	$errorLogs->saveErrorLog(
		$e->getFile() . " <br />line: " . $e->getLine(),
		$e->getMessage()
	);
  	header('Location: ./404.php');
}


if (!empty($_POST['login']) && !empty($_POST['password'])) {
  	$postSaveLogin = htmlentities((string) $_POST['login']);
  	$postSavePass = htmlentities((string) $_POST['password']);
  
  	try {
    	$userModel = new UserModel($configuration['db']);
    
    	if ($userModel->status) {
      		$userExist = $userModel->checkCredential($postSaveLogin, $postSavePass);
   		} else {
      		$userExist = null;
      		throw new Error('something bad with db configuration');
    	}
    
    	if ($userExist) {
      		$_SESSION['userName'] = $postSaveLogin;
      		$userModel->updateLastLogin($postSaveLogin);
      		$userModel->userLoginLog($postSaveLogin, "successful");
      		header("Location: ./");
    	} elseif (!$userModel->status) {
      		$msg = "appProblem";
      		include("templates/pages/showInfo.php");
    	} else {
      		$msg = "wrongHash";
      		include("templates/pages/showInfo.php");  
      		$userModel->userLoginLog($postSaveLogin, "incorrect credentials");
    	}

  	} catch (Error $e) { 
    	$errorLogs->saveErrorLog(
    		$e->getFile() . " <br />line: " . $e->getLine(),
    		$e->getMessage()
    	);
    	header('Location: ./404.php');
  	}

} elseif ((empty($_POST['login']) || empty($_POST['password'])) && isset($_POST['tried'])) {
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
          			<input type="text" name="login" placeholder="login" /> 
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