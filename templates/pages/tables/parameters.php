<?php
    //get selected filter
    if (!empty($viewParams['filters'])) {
        foreach ($viewParams['filters'] as $key => $value) {
            if (!empty($value)) {
                $selected[$key][$value] =  "selected";
            }
        }
        $countPage = $viewParams['filters']['pageNr'] ? $viewParams['filters']['pageNr'] : 1;
    }
?>

<?php //section with parameters ?>
<div class="parameters">
    <form class="settings-form" action="./?page=<?php echo $page; ?>" method="GET">
        <input type="hidden" name="page" value="<?php echo $page; ?>"/>
        
        <div class="parameter">
            <div class="filters">  

                <?php //input with data filter ?>
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

                <?php //show log filter for app logs page ?>
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

            <?php //input with search ?>
            <div class="search">
                <label>Search: <br />
                    <input 
                        type="search" 
                        name="phrase" 
                        value="<?php echo $viewParams['filters']['phrase'];?>"
                    />
                <label>
            </div>
        </div>
        
        <?php //CTA button ?>
        <div class="cta">
            <input type="submit" value="filter"/>

            <?php //input with sort ?>
            <div class="sort">
                <label>sort from: </label><br />
                <select name="sort" id="date">
                    <option 
                        value="desc" 
                        <?php echo $showSelected = !empty($selected['sort']['desc']) ? "selected" : "";?>
                        >
                        new first
                    </option>
                    <option 
                        value="asc" 
                        <?php echo $showSelected = !empty($selected['sort']['asc']) ? "selected" : "";?>
                        >
                        old first
                    </option>
                </select> 
            </div>
        </div>

        <?php //resset form option ?>
        <div class="reset">
            <a href="./?page=<?php echo $page; ?>">[x] reset</a>
        </div>

    </form>
</div>