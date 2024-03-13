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
  <form class="settings-form" action="./?page=logs" method="GET">
    <input type="hidden" name="page" value="logs" />
      
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
        </div>


        <div class="search">
          <label>Wyszukaj: <br />
            <input type="search" name="phrase" value="<?php echo $viewParams['filters']['phrase'];?>"/>
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
                  >najnowszych
                </option>
                <option 
                  value="asc" 
                  <?php echo $showSelected = !empty($selected['sort']['asc']) ? "selected" : "";?>
                  >najstarszych
                </option>
              </select> 
          </div>
      </div>

      <div class="reset">
        <a href="./?page=logs">[x] reset</a>
      </div>

  </form>
</div>



<div class="list">
  <section>

    <div class="tbl-header">
      <table cellpadding="0" cellspacing="0" border="0" class="logs">
        <thead>
          <tr>
            <th>Data</th>
            <th>Godzina</th>
            <th>Typ</th>
            <th>Status</th>
            <th>Info</th>
          </tr>
        </thead>
      </table>
    </div>

    <div class="tbl-content">
      <table cellpadding="0" cellspacing="0" border="0" class="logs">
        <tbody>
          <?php
          if (empty($viewParams['logs'])) {
            echo '<div class="noData">Brak danych do wyświetlenia</div>';
          } else {
            for ($i = 0; $i < count($viewParams['logs']); $i++) {
              ?>
              <tr>
                <td><?php echo $viewParams['logs'][$i]['date']; ?></td>
                <td><?php echo $viewParams['logs'][$i]['hour']; ?></td>
                <td><?php echo $viewParams['logs'][$i]['log']; ?></td>
                <td><?php echo $viewParams['logs'][$i]['status']; ?></td>
                <td><?php echo $viewParams['logs'][$i]['info']; ?></td>
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
        ./?page=logs&log=" . $viewParams['filters']['log'] . 
        "&date=" . $viewParams['filters']['date'] . 
        "&phrase=" . $viewParams['filters']['phrase'] . 
        "&sort=" . $viewParams['filters']['sort'] . 
        "";

      $currentPage = ($viewParams['filters']['pageNr']) ? : 1;
      $countPage = $viewParams['countPage']; 
    ?>

    <ul class="pagination">
      <?php if ($currentPage != 1) : ?>
        <li>
          <a href="
            <?php echo $paginationUrl . "&pageNr=" . $currentPage - 1; ?>
            ">
            <button><?php echo "<<"; ?></button>
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

        <?php for ($i=$countPage - 2; $i <= $countPage; $i++) :
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

  </section>
</div>
