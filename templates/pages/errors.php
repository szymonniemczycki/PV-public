<?php 
//get filters form
require_once("templates/pages/tables/filtersPanel.php"); ?>

<div class="list">
	<section>

		<!-- first header with table -->
		<div class="tbl-header">
      		<table cellpadding="0" cellspacing="0" border="0" id="errors">
        		<thead>
          			<tr>
            			<th>Date</th>
            			<th>Hour</th>
            			<th>Location</th>
            			<th>Info</th>
          			</tr>
        		</thead>
      		</table>
    	</div>

		<!-- table content -->
    	<div class="tbl-content">
      		<table cellpadding="0" cellspacing="0" border="0" class="<?php echo $page; ?>">
        		<tbody>
            		<?php
					//if no data in table
            		if (empty($viewParams[$page])) {
                		echo '<div class="noData">Brak danych do wyświetlenia</div>';
            		} else {
						//fill data in table
						for ($i=0; $i < count($viewParams[$page][$countPage]); $i++) {
							?>
							<tr>
							<td><?php echo $viewParams[$page][$countPage][$i][1]; ?></td>
							<td><?php echo $viewParams[$page][$countPage][$i][2]; ?></td>
							<td><?php echo $viewParams[$page][$countPage][$i][3]; ?></td>
							<td><?php echo $viewParams[$page][$countPage][$i][4]; ?></td>
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
			./?page=errors&date=" . $viewParams['filters']['date'] . 
			"&phrase=" . $viewParams['filters']['phrase'] . 
			"&sort=" . $viewParams['filters']['sort'] . 
			"";

		$currentPage = ($viewParams['filters']['pageNr']) ? $viewParams['filters']['pageNr'] : 1;
		$countPage = count($viewParams[$page]) ?? 1;

		//get pagination section
		require_once("templates/pages/tables/pagination.php"); 
		?>

  	</section>
</div>
