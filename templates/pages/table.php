<div class="list">
	<section>
		
	<!-- table header -->
		<div class="tbl-header">
			<table cellpadding="0" cellspacing="0" border="0">
				<thead>
					<tr>
						<th>Data</th>
						<th>Hour</th>
						<th>RCE<span class="unitPrice">[zł/MWh]</span></th>
						<th>Downloaded</th>
					</tr>
				</thead>
			</table>
		</div>

		<!-- table content -->
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0">
				<tbody>
					<?php 
					foreach ($viewParams['listPrices']['prices'] ?? [] as $price) {
					?>
					<tr>
						<td><?php echo $viewParams['formatedDate']; ?></td>
						<td><?php echo htmlentities($price['hour']); ?></td>
						<td><?php echo htmlentities($price['price']); ?></td>
						<td><?php echo htmlentities($price['created']); ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>

	</section>
</div>

