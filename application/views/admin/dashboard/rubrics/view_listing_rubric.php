		<?php if ($query->num_rows() > 0): ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<tr>
					<th>ID</th>
					<th>Titre</th>
					<th>Description</th>
					<th>Nb articles</th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
				<?php foreach ($query->result() as $row): ?>
				<tr>
					<td>
						<?php echo $row->r_id; ?>
					</td>
					<td>
						<a href="<?php echo base_url('admin/rubric/edit/' . $row->r_id); ?>" title="Modifier"><?php echo $row->r_title; ?></a>
					</td>
					<td>
						<?php echo character_limiter($row->r_description, 64); ?>
					</td>
					<td>
						<?php echo ($this->model_content->get_content_by_rubric($row->r_id)->num_rows); ?>
					</td>
					<td>
						<a href="<?php echo base_url('admin/rubric/edit/' . $row->r_id); ?>" title="Modifier"><i class="glyphicon glyphicon-pencil"></i></a>
					</td>
					<td>
						<a href="<?php echo base_url('admin/rubric/delete/' . $row->r_id); ?>" onclick="return deleteRubricConfirm()" title="Supprimer"><i class="glyphicon glyphicon-trash"></i></a>
					</td>
					<td>
						<a href="<?php echo base_url($row->r_url_rw); ?>" title="Aperçu" target="_blank"><i class="glyphicon glyphicon-eye-open"></i></a>
					</td>
				</tr>
				<?php endforeach; ?>
			</table><!-- end .table .table-hover -->
		</div><!-- end .table-responsive -->

		<p class="text-right"><em><?php echo $query->num_rows(); ?> rubrique(s)</em></p>

		<?php else: ?>
			<p>Aucune rubrique créée</p>
		<?php endif; ?>