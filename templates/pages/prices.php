<form class="note-form" action="./?page=prices" method="post" >
	<ul>
		<li>
			<?php //input for data select ?>
      		<label>Choose data <span class="required">*</span></label>
      		<input 
        		type="date" 
        		name="niceDate" 
        		value="<?php echo $date = ($viewParams['niceDate']) ?: date('Y-m-d'); ?>" 
        		min="2018-01-01" 
        		max="<?php echo date('Y-m-d'); ?>" 
        		data-input-format="%d/%m/%y"
      		/>
    	</li>

		<li>
      		<?php
				//show button/s
      			if(isset($viewParams['listPrices']['error']) && $viewParams['listPrices']['error'] == "noDataInDB") {
    				echo "<input class='btn-cta-white' type='submit' value='Show price' />";
        			echo "<button class='btn-cta-green' type='submit' name='page' value='forceImport'>Import</button>";
      			} else {
					echo "<input type='submit' value='Show price' />";
      			}
      		?>
    	</li>
	</ul>
</form>

<?php
	//show alert with error or table with content
	if (!empty($viewParams['listPrices']['error'])) {
		require_once("templates/pages/errorMessage.php");
	} elseif (empty($viewParams['listPrices']['error']) && !empty($viewParams['niceDate'])) {
		require_once("templates/pages/table.php");
	}
?>
  