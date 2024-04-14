<!-- table content -->
<div class="tbl-content">
    <table cellpadding="0" cellspacing="0" border="0" class="<?php echo $page; ?>">
        <tbody>
            <?php 
            //if no data in table
            if (empty($dataForTable)) {
                echo '<div class="noData">Brak danych do wyświetlenia</div>';
            } else {
                //fill data in table
                for ($ii = 0; $ii < count($dataForTable); $ii++) {
                    echo "<tr>";
                    for ($i = 0; $i < count($dataForTable[$ii]); $i++) {
                        echo "<td>" . $dataForTable[$ii][$i] . "</td>";
                    }
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>