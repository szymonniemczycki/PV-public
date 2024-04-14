<?php 
//get filters form
require_once("templates/pages/tables/filtersPanel.php"); ?>

<div class="list">
	<section>
		<?php
		$columnName = ["Date", "Hour", "Location", "Info"];
		require_once("templates/pages/tables/tableHeader.php"); 

		//prepare date for table
		if (!empty($viewParams[$page])) {
			for ($i = 0; $i < count($viewParams[$page][$countPage]); $i++) {
				$dataForTable[$i][0] = $viewParams[$page][$countPage][$i][1];
				$dataForTable[$i][1] = $viewParams[$page][$countPage][$i][2];
				$dataForTable[$i][2] = $viewParams[$page][$countPage][$i][3];
				$dataForTable[$i][3] = $viewParams[$page][$countPage][$i][4];
			}
		}
		
		//render table
		require_once("templates/pages/tables/tableContent.php"); 

		//get pagination section
		require_once("templates/pages/tables/pagination.php"); 
		?>
  	</section>
</div>
