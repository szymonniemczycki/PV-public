<?php 
//get parameters form
require_once("templates/pages/tables/parameters.php"); ?>

<div class="list">
	<section>
		<?php //first header with table ?>
		<div class="tbl-header">
      		<table cellpadding="0" cellspacing="0" border="0" class="logs">
				<thead>
				<tr>
					<th>Date</th>
					<th>Hour</th>
					<th>Type</th>
					<th>Status - Info</th>
					<th>User</th>
				</tr>
				</thead>
      		</table>
    	</div>

		<?php //table content ?>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" class="<?php echo $page; ?>">
				<tbody>
					<?php
						//if no data in table
						if (empty($viewParams[$page])) {
							echo '<div class="noData">Brak danych do wyświetlenia</div>';
						} else {
							//fill data in table
							for ($i = 0; $i < count($viewParams[$page]); $i++) {
								?>
								<tr>
									<td><?php echo explode(" ", $viewParams[$page][$i]['created'])[0]; ?></td>
									<td><?php echo explode(" ", $viewParams[$page][$i]['created'])[1]; ?></td>
									<td><?php echo $viewParams[$page][$i]['log']; ?></td>
									<td><?php echo $viewParams[$page][$i]['status']; ?>
									<?php echo " - ".$viewParams[$page][$i]['info']; ?></td>
									<td><?php echo $viewParams[$page][$i]['name']; ?></td>
								</tr>
								<?php 
							}
						}
					?>
				</tbody>
			</table>
		</div>

    
		<?php
		//create variable for link with params 
		$paginationUrl = "
			./?page=logs&log=" . $viewParams['filters']['log'] . 
			"&date=" . $viewParams['filters']['date'] . 
			"&phrase=" . $viewParams['filters']['phrase'] . 
			"&sort=" . $viewParams['filters']['sort'] . 
			"";

		$currentPage = ($viewParams['filters']['pageNr']) ? : 1;
		$countPage = $viewParams['countPage']; 
		?>

    	<?php 
		//get pagination section
		require_once("templates/pages/tables/pagination.php"); 
		?>
  	</section>
</div>
