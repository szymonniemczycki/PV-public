<!-- input for data select -->
<label for="<?php echo $labelFor; ?>">Choose data <span class="required">*</span></label>
<input 
    id="<?php echo $inputID; ?>"
    type="date" 
    name="formatedDate" 
    value="<?php echo $date = ($viewParams['formatedDate']) ?: date('Y-m-d'); ?>" 
    min="2018-01-01" 
    max="<?php echo date("Y-m-d"); ?>" 
    data-input-format="%y/%m/%d"
/>