<form class="note-form" action="./?page=prices" method="post" >
  <ul>
    <li>
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
      <input type="hidden" name="usedForm" value="1"/>
      <input type="submit" value="Show prices" />
      <?php
      if(isset($viewParams['listPrices']['error']) && $viewParams['listPrices']['error'] == "noDataInDB") {
        echo "<button class='btn-cta-white' type='submit' name='page' value='forceImport'>Import</button>";
      }
      ?>
    </li>
  </ul>
</form>

  <?php
    if (!empty($viewParams['listPrices']['error'])) {
      require_once("templates/pages/errorMessage.php");
    } elseif (empty($viewParams['listPrices']['error']) && !empty($viewParams['niceDate'])) {
      require_once("templates/pages/table.php");
    }
  ?>
  