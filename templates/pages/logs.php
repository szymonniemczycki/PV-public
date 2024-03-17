<?php require_once("templates/pages/tables/parameters.php"); ?>

<?php
	foreach ($viewParams['filters'] as $key => $value) {
    	if (!empty($value)) {
      		$selected[$key][$value] =  "selected";
    	}
  	}
?>


<div class="list">
	<section>

		<div class="tbl-header">
      		<table cellpadding="0" cellspacing="0" border="0" class="logs">
				<thead>
				<tr>
					<th>Data</th>
					<th>Hour</th>
					<th>Type</th>
					<th>Status</th>
					<th>Info</th>
				</tr>
				</thead>
      		</table>
    	</div>


		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" class="<?php echo $page; ?>">
				<tbody>
					<?php
						if (empty($viewParams[$page])) {
							echo '<div class="noData">Brak danych do wyświetlenia</div>';
						} else {
							for ($i = 0; $i < count($viewParams[$page]); $i++) {
								?>
								<tr>
									<td><?php echo explode(" ", $viewParams[$page][$i]['created'])[0]; ?></td>
									<td><?php echo explode(" ", $viewParams[$page][$i]['created'])[1]; ?></td>
									<td><?php echo $viewParams[$page][$i]['log']; ?></td>
									<td><?php echo $viewParams[$page][$i]['status']; ?></td>
									<td><?php echo $viewParams[$page][$i]['info']; ?></td>
								</tr>
								<?php 
							}
						}
					?>
				</tbody>
			</table>
		</div>

    
		<?php
		$paginationUrl = "
			./?page=logs&log=" . $viewParams['filters']['log'] . 
			"&date=" . $viewParams['filters']['date'] . 
			"&phrase=" . $viewParams['filters']['phrase'] . 
			"&sort=" . $viewParams['filters']['sort'] . 
			"";

		$currentPage = ($viewParams['filters']['pageNr']) ? : 1;
		$countPage = $viewParams['countPage']; 
		?>

    	<?php require_once("templates/pages/tables/pagination.php"); ?>

  	</section>
</div>
