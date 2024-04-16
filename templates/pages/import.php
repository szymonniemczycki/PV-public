<form class="note-form" action="./?page=import" method="post" >
	<ul>
		<li>
			<?php  
				$labelFor = "importData";
				$inputID = "importValue";
				require_once("templates/pages/tables/selectForm.php"); 
			?>
   		</li>
    
    	<li>
      		<?php
			//show button/s
			if (isset($viewParams['listPrices']['error']) && $viewParams['listPrices']['error'] == "dataExist" && (in_array("forceDownload", $userPerm))) {
				echo "
					<button class='btn-cta-green' type='submit' name='page' value='prices'>Show Prices</button>
					<button class='btn-cta-white' type='submit' name='page' value='forceDownload'>Overwrite</button>
				";
			} else {
				echo "<input id='importPrices' type='submit' value='import prices' />";
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
