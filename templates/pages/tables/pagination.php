<ul class="pagination">
    <?php if ($currentPage != 1) : ?>
    <li>
        <a href="
        <?php echo $paginationUrl . "&pageNr=" . $currentPage - 1; ?>
        ">
        <button>
            <?php echo "<<"; ?>
        </button>
        </a>
    </li>
    <?php endif; ?>

    <?php
    if($countPage <= 9) {
    for ($i = 1; $i <= $countPage; $i++) : 
        if($i == $currentPage) {
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
    <?php endfor; 
    } elseif ($countPage > 9) {
    for ($i = 1; $i < 4; $i++) : 
        if($i == $currentPage) {
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
    <?php endfor; ?>

    <li>
        ...
    </li>

    <?php 
        for ($i=$countPage - 2; $i <= $countPage; $i++) :
        if($i == $currentPage) {
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
    <?php endfor; 
    }?>

    <?php if ($currentPage < $countPage && $countPage != 1) : ?>
    <li>
        <a href="
        <?php echo $paginationUrl . "&pageNr=" . $currentPage + 1; ?>
        ">
        <button><?php echo ">>"; ?></button>
        </a>
    </li>
    <?php endif; ?>
</ul>