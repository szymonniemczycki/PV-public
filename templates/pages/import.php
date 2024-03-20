<form class="note-form" action="./?page=import" method="post" >
	<ul>
		<li>
			<?php 
				//input for data select 
				?>
				<label>Choose data <span class="required">*</span></label>
					<input 
						type="date" 
						name="niceDate" 
						value="<?php echo $date = ($viewParams['niceDate']) ?: date('Y-m-d'); ?>" 
						min="2018-01-01" 
						max="<?php echo date("Y-m-d");?>" 
						data-input-format="%y/%m/%d"
      			/>
   		</li>
    
    	<li>
      		<?php
				//show button/s
				if(isset($viewParams['listPrices']['error']) && $viewParams['listPrices']['error'] == "dataExist") {
					echo "<input class='btn-cta-white' type='submit' value='import prices' />";
					echo "<button class='btn-cta-green' type='submit' name='page' value='forceDownload'>Overwrite</button>";
				} else {
					echo "<input type='submit' value='import prices' />";
				}
      		?>
    	</li>
  	</ul>
</form>

<?php
	//show alert with error
	if (!empty($viewParams['listPrices']['error'])) {
		$msg = $viewParams['listPrices']['error'];
		require_once("templates/pages/showInfo.php");
  	}  
?>
