<html lang="pl">
	<?php require_once("templates/header.php"); ?>
	<body class="body">
		<div class="wrapper">

			<div class="header">
				<h1><i class="far fa-chart-bar"></i>RCE importer</h1>
				<div class="logout">
					<a href="./logout.php">logout</a>
				</div>
			</div>

			<div class="container">
				<div class="menu">
				<ul>
					<li><a <?php echo $active = ($page=="main") ? 'class="active"' : null; ?> href="./">Main</a></li>
					<li><a <?php echo $active = ($page=="prices") ? 'class="active"' : null; ?> href="./?page=prices">Prices</a></li>
					<li><a <?php echo $active = ($page=="import") ? 'class="active"' : null; ?> href="./?page=import">Import</a></li>
					<li><a <?php echo $active = ($page=="logs") ? 'class="active"' : null; ?> href="./?page=logs">Logs</a></li>
					<li><a <?php echo $active = ($page=="errors") ? 'class="active"' : null; ?> href="./?page=errors">Errors</a></li>
				</ul>
				</div>

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