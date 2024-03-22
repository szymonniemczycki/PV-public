<form class="note-form" action="./?page=prices" method="post">
	<ul>
		<li>
			<!-- input for data select -->
      		<label for="choosePricesData">Choose data <span class="required">*</span></label>
      		<input 
        		id="chooseDate"
				type="date" 
        		name="formatedDate" 
        		value="<?php echo $date = ($viewParams['formatedDate']) ?: date('Y-m-d'); ?>" 
        		min="2018-01-01" 
        		max="<?php echo date('Y-m-d'); ?>" 
        		data-input-format="%d/%m/%y"
      		/>
    	</li>
		<li>
      		<?php
			//show button/s
			if (isset($viewParams['listPrices']['error']) && $viewParams['listPrices']['error'] == "noDataInDB") {
				echo "
					<input id='showPriceAgain' class='btn-cta-white' type='submit' value='Show price' />
					<button class='btn-cta-green' type='submit' name='page' value='forceImport'>Import</button>
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
	require_once("templates/pages/table.php");
}
?>
  