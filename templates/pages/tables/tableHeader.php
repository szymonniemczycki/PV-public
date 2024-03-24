<!-- first header with table -->
<div class="tbl-header">
    <table cellpadding="0" cellspacing="0" border="0" class="<?php echo $page; ?>">
        <thead>
            <tr>
                <?php
                for ($i=0; $i < count($columnName); $i++) {
                    echo "<th>" . $columnName[$i] . "</th>";
                }
                ?>
            </tr>
        </thead>
    </table>
</div>