<div class="list">
  <section>

    <div class="tbl-header">
      <table cellpadding="0" cellspacing="0" border="0">
        <thead>
          <tr>
            <th>dzień</th>
            <th>godzina</th>
            <th>RCE<span class="unitPrice">[zł/MWh]</span></th>
            <th>pobrano</th>
          </tr>
        </thead>
      </table>
    </div>

    <div class="tbl-content">
      <table cellpadding="0" cellspacing="0" border="0">
        <tbody>
          <?php 
            foreach ($viewParams['listPrices']['prices'] ?? [] as $price) : ?>
              <tr>
                <td><?php echo $viewParams['niceDate']; ?></td>
                <!--<td><?php 
                  // if(strlen(htmlentities($price['hour'])) == 1) {
                  //   echo "0";
                  // }
                  // echo htmlentities($price['hour']) . ":00";
                ?></td> -->
                <td><?php echo htmlentities($price['hour']); ?></td>
                <td><?php echo htmlentities($price['price']); ?></td>
                <td><?php echo htmlentities($price['created']); ?></td>
              </tr>
            <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </section>
</div>

