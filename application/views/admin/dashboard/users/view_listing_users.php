		<?php if ($query->num_rows() > 0): ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<tr>
					<th>ID</th>
					<th>Nom</th>
					<th>Statut</th>
					<th>Email</th>
					<th>Biograpie</th>
					<th>Articles rédigés</th>
					<th>Statut</th>
					<?php if ($user_data['level'] == 1): ?>
					<th></th>
					<th></th>
					<?php endif; ?>
					<th></th>
				</tr>
				<?php foreach ($query->result() as $row): ?>
				<?php if ($row->u_status == 0): ?>
				<tr class="warning">
				<?php else: ?>
				<tr>
				<?php endif; ?>
					<td><?php echo $row->u_id; ?></td>
					<td><?php echo $row->u_login; ?></td>
					<td>
						<?php switch ($row->u_level) {
							case 0:
								echo 'Modérateur';
								break;
							
							case 1:
								echo 'Administrateur';
								break;

							default:
								break;
						}
						?>
					</td>
					<td><?php echo $row->u_email; ?></td>
					<td><?php echo $row->u_biography; ?></td>
					<td><a href="<?php echo base_url('admin/user/' . $row->u_id); ?>" title="Tous les articles de cet auteur"><?php echo ($this->model_content->get_content_by_user($row->u_id, '', '')->num_rows); ?></a></td>
					<td>
					<?php switch ($row->u_status) {
						case '0':
							echo 'désactivé';
							break;

						case '1':
							echo 'activé';
						
						default:
							break;
					}; ?>
					</td>
					<?php if ($user_data['level'] == 1): ?>
					<td><a href="<?php echo base_url('admin/user/edit/' . $row->u_id); ?>" title="Modifier"><i class="glyphicon glyphicon-pencil"></i></a></td>
					<td><a href="<?php echo base_url('admin/user/delete/' . $row->u_id); ?>" onclick="return deleteConfirm()" title="Supprimer"><i class="glyphicon glyphicon-trash"></i></a></td>
					<?php endif; ?>
					<td><a href="<?php echo base_url('auteur/' . $row->u_login); ?>" title="Aperçu" target="_blank"><i class="glyphicon glyphicon-eye-open"></i></a></td>
				</tr>
				<?php endforeach; ?>
			</table><!-- end .table .table-hover -->
		</div><!-- end .table-responsive -->

		<p class="text-right"><em><?php echo $query->num_rows(); ?> utilisateur(s)</em></p>

		<script>
			function deleteConfirm() {
				var a = confirm("Etes-vous sur de vouloir supprimer cet utilisateur ?!");
				if (a){
					return true;
				}
				else{
					return false;
				}
			}
		</script>

		<?php else: ?>
			<p>Aucun utilisateur</p>
		<?php endif; ?>