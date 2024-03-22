<ul class="pagination">
    <?php 
    //show button for previous page
    if ($currentPage != 1) { ?>
        <li>
            <a href="<?php echo $paginationUrl . "&pageNr=" . $currentPage - 1; ?>">
                <button>
                    <?php echo "<<"; ?>
                </button>
            </a>
        </li>
        <?php 
    } ?>

    <?php
    //show all pages if less than 10
    if ($countPage <= 9) {
        for ($i = 1; $i <= $countPage; $i++) {
            if ($i == $currentPage) {
                $isActive = 'class="active"';
            } else {
                $isActive = "";
            }
            ?>
            <li>
                <a href="<?php echo $paginationUrl . "&pageNr=" . $i; ?>">
                    <button <?php echo $isActive; ?>><?php echo $i; ?></button>
                </a>
            </li>
            <?php 
        }
    //group quantity of pages if more than 10
    } elseif ($countPage > 9) {
        for ($i = 1; $i < 4; $i++) {
            if ($i == $currentPage) {
                $isActive = 'class="active"';
            } else {
                $isActive = "";
            }
            ?>
            <li>
                <a href="<?php echo $paginationUrl . "&pageNr=" . $i; ?>">
                    <button <?php echo $isActive; ?>><?php echo $i; ?></button>
                </a>
            </li>
            <?php 
        } ?>
            <li>
                ...
            </li>
        <?php 
            for ($i=$countPage - 2; $i <= $countPage; $i++) {
                if ($i == $currentPage) {
                    $isActive = 'class="active"';
                } else {
                    $isActive = "";
                }
                ?>
                <li>
                    <a href="<?php echo $paginationUrl . "&pageNr=" . $i; ?>">
                        <button <?php echo $isActive; ?>><?php echo $i; ?></button>
                    </a>
                </li>
                <?php 
            }
    }?>

    <?php 
    //show button for next page 
    if ($currentPage < $countPage && $countPage != 1) { ?>
        <li>
            <a href="<?php echo $paginationUrl . "&pageNr=" . $currentPage + 1; ?>">
                <button><?php echo ">>"; ?></button>
            </a>
        </li>
        <?php 
    } ?>
</ul>