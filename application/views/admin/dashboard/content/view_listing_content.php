		<?php if ($query->num_rows() > 0): ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<tr>
					<th></th>
					<th>Titre</th>
					<th class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							Rubrique <span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<?php foreach ($rubrics->result() as $rubric): ?>
							<li>
								<a href="content/r?q=<?php echo $rubric->r_url_rw; ?>"><?php echo $rubric->r_title; ?></a>
							</li>
							<?php endforeach; ?>
						</ul>
					</th>
					<th class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						Tags <span class="caret"></span>
						</a>
						<form class="dropdown-menu" role="menu" action="<?php echo base_url('admin/content/t?'); ?>" method="GET">
							<li class="radio radio-success">
								<label for="or">
									<input type="radio" value="or" name="option" id="or" <?php if (!isset($option) OR ($option && $option == 'or')) echo 'checked'; ?>>
								No strict
								</label>
							</li>
							<li class="radio radio-success">
								<label for="and">
									<input type="radio" value="and" name="option" id="and" <?php if (isset($option) && $option == 'and') echo 'checked'; ?>>
									Strict
								</label>
							</li>
							<?php foreach ($tags as $tag): ?>
							<?php if (!empty($tag)): ?>
							<li>
								<div class="checkbox">
								<label for="<?php echo $tag; ?>">
									<input type="checkbox" id="<?php echo $tag; ?>" value="<?php echo $tag; ?>" name="q[]"
									<?php if (isset($_GET['q'])): ?>
									<?php echo get_active_tags($_GET['q'], $tag); ?>
									<?php endif; ?>
									>
									<?php echo $tag; ?></label>
								</div>
							</li>
							<?php endif; ?>
							<?php endforeach; ?>
							<button type="submit" class="btn btn-primary btn-small pull-right" />Ok</button>
						</form>
					</th>
					<th class="text-center">Création</th>
					<th class="text-center">MAJ</th>
					<th class="text-center">Publication</th>
					<?php if ($page !== 'author'): ?>
					<th class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							Auteur <span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<?php foreach ($users->result() as $user): ?>
							<li>
								<a href="user/<?php echo $user->u_id; ?>"><?php echo $user->u_login; ?></a>
							</li>
							<?php endforeach; ?>
						</ul>
					</th>
					<?php endif; ?>
					<th>Etat</th>
					<th>Commentaires</th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
				<form method="POST" action="<?php echo base_url('admin/content/multi_options'); ?>">
					<ul class="list-inline">
						<li>
							<button class="btn" type="submit" name="multi_delete" value="multi_delete" onclick="return deleteContentConfirm()">
								<i class="glyphicon glyphicon-trash"></i> Supprimer
							</button>
						</li>
						<li>
							<button class="btn" type="submit" name="multi_export" value="multi_export">
								<i class="glyphicon glyphicon-export"></i> Exporter
							</button>
						</li>
					</ul>
					<?php foreach ($query->result() as $row): ?>
					<?php if ($row->c_status == 0): ?>
					<tr class="warning">
					<?php else: ?>
					<tr>
					<?php endif; ?>
						<td>
							<div class="checkbox" style="margin-top: 0">
								<label for="content_<?php echo $row->c_id; ?>">
									<?php echo form_checkbox('content_choice[]', $row->c_id, "", "id=\"content_" . $row->c_id ."\""); ?> <?php echo $row->c_id; ?>
								</label>
							</div>
						</td>
						<!-- Title -->
						<td>
							<a href="<?php echo base_url('admin/content/edit/' . $row->c_id); ?>" title="Modifier"><?php echo $row->c_title; ?></a>
						</td>
						<!-- Name rubric -->
						<td>
							<a href="<?php echo base_url('admin/content/r?q=' . $row->r_url_rw); ?>" title="Tous les articles de cette rubrique"><?php echo $row->r_title; ?></a>
						</td>
						<!-- Tags -->
						<td>
							<?php echo get_tags($row->c_tags); ?>
						</td>
						<!-- Created Date -->
						<td class="text-center">
							<?php echo date("d/m/Y à H:i:s", strtotime($row->c_cdate)); ?>
						</td>
						<!-- Updated date -->
						<?php if ($row->c_cdate !== $row->c_udate): ?>
						<td class="text-center">
							<?php echo date("d/m/Y à H:i:s", strtotime($row->c_udate)); ?>
						</td>
						<?php else: ?>
							<td class="text-center">-</td>
						<?php endif; ?>
						<!-- Published date -->
						<?php if ($row->c_pdate !== NULL): ?>
						<td class="text-center">
							<?php echo date("d/m/Y à H:i:s", strtotime($row->c_pdate)); ?>
						</td>
						<?php else: ?>
						<td class="text-center">-</td>
						<?php endif; ?>
						<?php if ($page !== 'author'): ?>
						<td>
							<a href="<?php echo base_url('admin/user/' . $row->u_id); ?>" title="Tous ses articles"><?php echo $row->u_login; ?></a>
						</td>
						<?php endif; ?>
						<!-- Status -->
						<td>
						<?php 
						switch ($row->c_status) {
							case '0':
								echo 'Brouillon';
								break;

							case '1':
								echo 'Publié';
								break;
							
							default:
								echo 'Error';
								break;
						}
						?>
						</td>
						<!-- Nb comments -->
						<td>
							<?php echo ($this->model_comment->get_comment($row->c_id)->num_rows); ?>
						</td>
						<td>
							<a href="<?php echo base_url('admin/content/edit/' . $row->c_id); ?>" title="Modifier">
								<i class="glyphicon glyphicon-pencil"></i>
							</a>
						</td>
						<td>
							<a href="<?php echo base_url('admin/content/delete/' . $row->c_id); ?>" onclick="return deleteContentConfirm()" title="Supprimer cet article">
								<i class="glyphicon glyphicon-trash"></i>
							</a>
						</td>
						<td>
							<?php if ($row->c_status == 1): ?>
							<a href="<?php echo base_url($row->r_url_rw . '/' . $row->c_url_rw); ?>" title="Aperçu" target="_blank">
								<i class="glyphicon glyphicon-eye-open"></i>
							</a>
							<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</form>
			</table><!-- end .table .table-hover -->
		</div><!-- end .table-responsive -->

		<p class="text-right">
			<em><?php echo $query->num_rows(); ?> article(s)</em>
		</p>

		<?php else: ?>
			<p>Aucun article rédigé</p>
		<?php endif; ?>