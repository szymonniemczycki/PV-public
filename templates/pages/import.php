<form class="note-form" action="./?page=import" method="post" >
  <ul>
    <li>
      <label>Podaj datę <span class="required">*</span></label>
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
      <input type="submit" value="importuj ceny" />
      <?php
        if(isset($viewParams['listPrices']['error']) && $viewParams['listPrices']['error'] == "dataExist") {
          echo "<button class='btn-cta-white' type='submit' name='page' value='forceDownload'>Nadpisz dane</button>";
        }
      ?>
    </li>
  </ul>
</form>

<?php
  if (!empty($viewParams['listPrices']['error'])) {
    require_once("templates/pages/errorMessage.php");
  }  
?>
