		<?php if ($query->num_rows() > 0): ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<tr>
					<th>ID</th>
					<th>Article</th>
					<th>Nom</th>
					<th>Contenu</th>
					<th>Statut</th>
					<th>Modération</th>
					<th></th>
				</tr>
				<?php foreach ($query->result() as $row): ?>
				<tr <?php if ($row->com_status == 0) echo 'style="font-weight: bold"'; ?>>
					<td><?php echo $row->com_id; ?></td>
					<td><a href="<?php echo base_url('admin/content/edit/' . $row->c_id); ?>"><?php echo $row->c_title; ?></a></td>
					<td><?php echo $row->com_nickname; ?></td>
					<td><?php echo $row->com_content; ?></td>
					<td>
						<?php switch ($row->com_status) {
							case '0':
								echo 'Non modéré';
								break;

							case '1':
								echo 'Modéré';
								break;

							case '2':
								echo 'Désactivé';
								break;

							default:
								break;
						}
						?>
					</td>
					<td>
					<?php 
						if ($row->com_status == 0):
							echo "<a href=". base_url('admin/comments/moderate/' . $row->com_id) . ">Modérer</a>";
							echo ' - ';
							echo "<a href=". base_url('admin/comments/desactivate/' . $row->com_id) . ">Désactiver</a>";
						elseif ($row->com_status == 2):
							echo "<a href=". base_url('admin/comments/moderate/' . $row->com_id) . ">Réactiver</a>";	
						else:
							echo "<a href=". base_url('admin/comments/desactivate/' . $row->com_id) . ">Désactiver</a>";
						endif;
					?>
					</td>
					<td><a href="<?php echo base_url('admin/comments/delete/' . $row->com_id); ?>" onclick="return deleteConfirm()" title="Supprimer"><i class="glyphicon glyphicon-trash"></i></a></td>
				</tr>
				<?php endforeach; ?>
			</table><!-- end .table .table-hover -->
		</div><!-- end .table-responsive -->

		<p class="text-right"><em><?php echo $query->num_rows(); ?> commentaire(s)</em></p>

		<script>
			function deleteConfirm() {
				var a = confirm("Etes-vous sur de vouloir supprimer ce commentaire ?!");
				if (a){
					return true;
				}
				else{
					return false;
				}
			}
		</script>

		<?php else: ?>
			<p>Aucun commentaire</p>
		<?php endif; ?>