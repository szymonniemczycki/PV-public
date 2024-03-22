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

<!-- section with filters -->
<div class="filtersPanel">
    <form class="settings-form" action="./?page=<?php echo $page; ?>" method="GET">
        <input id="pageID" type="hidden" name="page" value="<?php echo $page; ?>"/>
        
        <div class="filtersPanel">
            <div class="filters">  

                <!-- input with data filter -->
                <div class="filterDate">
                    <div class="filterLabelDate">Data:</div>
                    <input 
                        id="filterDate"
                        type="date" 
                        name="date" 
                        value="<?php echo htmlentities((string) $viewParams['filters']['date']); ?>" 
                        min="2018-01-01" 
                        max="<?php echo date('Y-m-d');?>"
                    />
                </div>

                <?php
                //show log filter for app logs page
                if ($page == "logs") { ?>
                    <div class="filterLog">
                        <div class="filterLabelLog">Log:</div>
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
                    <?php 
                } ?>
            </div>

            <!-- input with search -->
            <div class="search">
                <label for="searchLabel">Search: </label>
                <input 
                    id="filterSearch"
                    type="search" 
                    name="phrase" 
                    value="<?php echo $viewParams['filters']['phrase'];?>"
                />
            </div>
        </div>
        
        <!--CTA button -->
        <div class="button-cta">
            <input id="goFilter" type="submit" value="filter"/>
        </div>

        <!-- resset form option -->
        <div class="reset">
            <a href="./?page=<?php echo $page; ?>">[x] reset</a>
        </div>

        <!-- input with sort -->
        <div class="sort">
            <label for="sortLabel">sort from: </label>
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
        
    </form>
</div>