<?php 
require_once("src/Utils/debug.php");

//get filters
require_once("templates/pages/tables/filtersPanel.php"); ?>

<div class="list">
	<section>
		<?php
		$columnName = ["Date", "Hour", "Type", "Status - Info", "User"];
		require_once("templates/pages/tables/tableHeader.php"); 
		
		//prepare date for table
		for ($i = 0; $i < count($viewParams[$page]); $i++) {
			$dataForTable[$i][0] = explode(" ", $viewParams[$page][$i]['created'])[0];
			$dataForTable[$i][1] = explode(" ", $viewParams[$page][$i]['created'])[1];
			$dataForTable[$i][2] = $viewParams[$page][$i]['log'];
			$dataForTable[$i][3] = $viewParams[$page][$i]['status'] .  " - " . $viewParams[$page][$i]['info'];
			$dataForTable[$i][4] = $viewParams[$page][$i]['name'];
		}
		
		//render table
		require_once("templates/pages/tables/tableContent.php"); 

		//get pagination section
		require_once("templates/pages/tables/pagination.php"); 
		?>
  	</section>
</div>
