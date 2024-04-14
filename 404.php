<html lang="pl">

<?php
//stop session
session_start();
unset($_SESSION['userName']);
unset($_SESSION['userId']);
session_destroy();

require_once("templates/header.php"); 

?>

	<body class="body">
		<?php
		$msg = "appProblem";
		include("templates/pages/showInfo.php");
		?>

		<!-- show login form -->
			<?php require_once("templates/loginForm.php"); ?>
  	</body>
	
</html>