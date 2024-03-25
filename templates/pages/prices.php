<form class="note-form" action="./?page=prices" method="post">
	<ul>
		<li>
			<?php  
				$labelFor = "pricesData";
				$inputID = "priceValue";
				require_once("templates/pages/tables/selectForm.php"); 
			?>
    	</li>

		<li>
      		<?php
			//show button/s
			if (isset($viewParams['listPrices']['error']) && $viewParams['listPrices']['error'] == "noDataInDB") {
				echo "
					<input id='showPriceAgain' class='btn-cta-white' type='submit' value='Show price' />
					<button class='btn-cta-green' type='submit' name='page' value='import'>Import</button>
				";
			} else {
				echo "<input id='showPrice' type='submit' value='Show price' />";
			}
      		?>
    	</li>
	</ul>
</form>

<?php
//show alert with error or table with content
if (!empty($viewParams['listPrices']['error'])) {
	$msg = $viewParams['listPrices']['error'];
	require_once("templates/pages/showInfo.php");
} elseif (empty($viewParams['listPrices']['error']) && !empty($viewParams['formatedDate'])) {
	require_once("templates/pages/tables/tablePrices.php");
}
?>
  