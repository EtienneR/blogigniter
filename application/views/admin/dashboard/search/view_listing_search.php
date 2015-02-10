		<?php if ($query->num_rows() > 0): ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<tr>
					<th>ID</th>
					<th>Mot</th>
					<th>Date</th>
					<th>Nombre d'articles trouvés</th>
				</tr>
				<?php foreach ($query->result() as $row): ?>
				<tr>
					<td><?php echo $row->s_id; ?></td>
					<td><?php echo strip_tags($row->s_word); ?></td>
					<td><?php echo $row->s_date; ?></td>
					<td><?php echo $row->s_score; ?></td>
				<?php endforeach; ?>
			</table><!-- end .table .table-hover -->
		</div><!-- end .table-responsive -->

		<p class="text-right"><em><?php echo $query->num_rows(); ?> mot(s) dont <?php echo $distinct_words->num_rows(); ?> unique(s)</em></p>

		<?php else: ?>
			<p>Aucun mot recherché</p>
		<?php endif; ?>