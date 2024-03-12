<?php
    foreach ($viewParams['filters'] as $key => $value) {
      if (!empty($value)) {
        $selected[$key][$value] =  "selected";
      }
    }
?>

<?php
  $countPage = $viewParams['filters']['pageNr'] ? $viewParams['filters']['pageNr'] : 1;
?>

<div class="parameters">
  <form class="settings-form" action="./?page=errors" method="GET">
    <input type="hidden" name="page" value="errors"/>
      
    <div class="parameter">
      <div class="filters">  
        <div class="date">
          <div class="filterDate">Data:</div>
            <input 
              type="date" 
              name="date" 
              value="<?php echo $viewParams['filters']['date'];?>" 
              min="2018-01-01" 
              max="<?php echo date('Y-m-d');?>"
              />
        </div>
      </div>

      <div class="search">
        <label>Wyszukaj: <br />
          <input 
            type="search" 
            name="phrase" 
            value="<?php echo $viewParams['filters']['phrase']; ?>"
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
      <a href="./?page=errors">[x] reset</a>
    </div>

  </form>
</div>



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
      <table cellpadding="0" cellspacing="0" border="0" class="errors">
        <tbody>
          <?php
            if (empty($viewParams['data'])) {
              echo '<div class="noData">Brak danych do wyświetlenia</div>';
            } else {
              for ($i=0; $i < count($viewParams['data'][$countPage]); $i++) {
                  ?>
                  <tr>
                    <td><?php echo $viewParams['data'][$countPage][$i][1]; ?></td>
                    <td><?php echo $viewParams['data'][$countPage][$i][2]; ?></td>
                    <td><?php echo $viewParams['data'][$countPage][$i][3]; ?></td>
                    <td><?php echo $viewParams['data'][$countPage][$i][4]; ?></td>
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
      $pages = sizeof($viewParams['data']) ?? 1;
    ?>

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
        if(sizeof($viewParams['data']) <= 9) {
          for ($i = 1; $i <= sizeof($viewParams['data']); $i++) : 
            if ($i == $currentPage) {
              $isActive = 'class="active"';
            } else {
              $isActive = "";
            }
            ?>
            <li>
              <a href="<?php echo $paginationUrl . "&pageNr=" . $i;?>">
                <button <?php echo $isActive;?>><?php echo $i; ?></button>
              </a>
            </li>
          <?php endfor; 
        } elseif(sizeof($viewParams['data']) > 9) {
          for ($i = 1; $i < 4; $i++) : 
            if ($i == $currentPage) {
              $isActive = 'class="active"';
            } else {
              $isActive = "";
            }
              ?>
            <li>
              <a href="<?php echo $paginationUrl . "&pageNr=" . $i;?>">
                <button <?php echo $isActive;?>><?php echo $i; ?></button>
              </a>
            </li>
          <?php endfor; ?>

          <li>
              ...
          </li>

          <?php 
            for ($i = sizeof($viewParams['data']) - 2; $i <= sizeof($viewParams['data']); $i++) :
              if ($i == $currentPage) {
                $isActive = 'class="active"';
              } else {
                $isActive = "";
              }
          ?>
            <li>
              <a href="<?php echo $paginationUrl . "&pageNr=" . $i;?>">
                <button <?php echo $isActive; ?>><?php echo $i; ?></button>
              </a>
            </li>
          <?php endfor; 
        }?>


      <?php if ($currentPage < $pages && $pages != 1) : ?>
      <li>
        <a href="
          <?php echo $paginationUrl . "&pageNr=" . $currentPage + 1; ?>
          ">
            <button><?php echo ">>"; ?></button>
        </a>
      </li>
      <?php endif; ?>
    </ul>

  </section>
</div>
