<html lang="pl">
	<?php 
	//get meta tags
	require_once("templates/header.php"); ?>
	<body class="body">
		<div class="wrapper">

			<!-- show header -->
			<div class="header">
				<h1><i class="far fa-chart-bar"></i>RCE importer</h1>
				<div class="logout">
					<a href="./logout.php">logout</a>
				</div>
			</div>

			<!-- show menu -->
			<div class="container">
				<div class="menu">
					<ul>
						<?php
							$menuItem = ["main"=>"Main", "prices"=>"Prices", "import"=>"Import", "logs"=>"Logs", "errors"=>"Errors"];
							foreach ($menuItem as $id => $item) {
								if (in_array($id, $userPerm)) {
									echo "<li><a " . ($active = ($page==$id) ? 'class="active"' : null) . " href='./?page=".$id."'>" . $item . "</a></li>";
								}
							}
						?> 
					</ul>
				</div>

				<!-- display page -->
				<div class="page">
					<?php require_once("templates/pages/$page.php"); ?>
				</div>
			</div>

			<footer>
				<p>RCE importer - PHP</p>
			</footer>

		</div>
	</body>
</html>