<?php
    if (!empty($viewParams['filters'])) {
        foreach ($viewParams['filters'] as $key => $value) {
            if (!empty($value)) {
                $selected[$key][$value] =  "selected";
            }
        }
        $countPage = $viewParams['filters']['pageNr'] ? $viewParams['filters']['pageNr'] : 1;
    }
?>

<?php
?>


<div class="parameters">
    <form class="settings-form" action="./?page=<?php echo $page; ?>" method="GET">
        <input type="hidden" name="page" value="<?php echo $page; ?>"/>
        
        <div class="parameter">
            <div class="filters">  

                <div class="date">
                    <div class="filterDate">Data:</div>
                    <input 
                        type="date" 
                        name="date" 
                        value="<?php echo htmlentities((string) $viewParams['filters']['date']); ?>" 
                        min="2018-01-01" 
                        max="<?php echo date('Y-m-d');?>" 
                    />
                </div>

                <?php if ($page == "logs"): ?>
                    <div class="log">
                        <div class="filterLog">Log:</div>
                            <select name="log" id="log">
                                <?php
                                foreach ($viewParams['logTypes'] as $key => $value) { ?>
                                    <option 
                                        value="<?php echo $value; ?>" 
                                        <?php echo $showSelected = !empty($selected['log'][$value]) ? "selected" : "";?>
                                        >
                                        <?php echo $key; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div> 
                    <?php endif; ?>
                </div>


            <div class="search">
                <label>Wyszukaj: <br />
                    <input 
                        type="search" 
                        name="phrase" 
                        value="<?php echo $viewParams['filters']['phrase'];?>"
                    />
                <label>
            </div>
        </div>
        
        <div class="cta">
            <input type="submit" value="filtruj"/>
            <div class="sort">
                <label>sortuj od: </label><br />
                <select name="sort" id="date">
                    <option 
                        value="desc" 
                        <?php echo $showSelected = !empty($selected['sort']['desc']) ? "selected" : "";?>
                        >
                        najnowszych
                    </option>
                    <option 
                        value="asc" 
                        <?php echo $showSelected = !empty($selected['sort']['asc']) ? "selected" : "";?>
                        >
                        najstarszych
                    </option>
                </select> 
            </div>
        </div>

        <div class="reset">
            <a href="./?page=<?php echo $page; ?>">[x] reset</a>
        </div>

    </form>
</div>