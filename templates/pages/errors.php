<?php require_once("templates/pages/tables/parameters.php"); ?>

<div class="list">
  <section>

    <div class="tbl-header">
      <table cellpadding="0" cellspacing="0" border="0" class="errors">
        <thead>
          <tr>
            <th>Data</th>
            <th>Godzina</th>
            <th>Lokalizacja</th>
            <th>Info</th>
          </tr>
        </thead>
      </table>
    </div>


    <div class="tbl-content">
      <table cellpadding="0" cellspacing="0" border="0" class="<?php echo $page; ?>">
        <tbody>
            <?php
            if (empty($viewParams[$page])) {
                echo '<div class="noData">Brak danych do wyświetlenia</div>';
            } else {
                for ($i=0; $i < count($viewParams[$page][$countPage]); $i++) {
                    ?>
                    <tr>
                    <td><?php echo $viewParams[$page][$countPage][$i][1]; ?></td>
                    <td><?php echo $viewParams[$page][$countPage][$i][2]; ?></td>
                    <td><?php echo $viewParams[$page][$countPage][$i][3]; ?></td>
                    <td><?php echo $viewParams[$page][$countPage][$i][4]; ?></td>
                    </tr>
                <?php 
                }
            }
            ?>
        </tbody>
      </table>
    </div>

    
    <?php
      $paginationUrl = "
        ./?page=errors&date=" . $viewParams['filters']['date'] . 
        "&phrase=" . $viewParams['filters']['phrase'] . 
        "&sort=" . $viewParams['filters']['sort'] . 
        "";

      $currentPage = $viewParams['filters']['pageNr'] ?? 1;
      $currentPage = ($currentPage) ? $currentPage : 1;
      $countPage = sizeof($viewParams[$page]) ?? 1;
    ?>

    <?php require_once("templates/pages/tables/pagination.php"); ?>

  </section>
</div>
