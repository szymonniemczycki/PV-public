<html lang="pl">

<?php require_once("templates/header.php"); ?>

	<body class="body">
		
		<?php
			$msg = "appProblem";
			include("templates/pages/showInfo.php");
		?>

		<!-- show login form -->
		<div class="login">
			<?php require_once("templates/loginForm.php"); ?>
		</div>

  	</body>
</html>