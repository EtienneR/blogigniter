
<?php 
	echo form_open(current_url());
?>
    <ul class="nav nav-tabs">
        <li class="active">
        	<a href="#options" data-toggle="tab">Options</a>
        </li>
        <li>
        	<a href="#content" data-toggle="tab">Contenu</a>
        </li>
    </ul>

    <div class="tab-content">
    	
    	<div class="tab-pane active" id="options">

    		<?php if ($page == 'edit_content'): ?>
    			<p>
    				<?php if ($c_cdate == $c_pdate): ?>
    					Cet article a été rédigé et publié le <?php echo date("d/m/Y à H:i:s", strtotime($c_cdate)); ?>.
    				<?php else: ?>
    					Cet article a été rédigé le <?php echo date("d/m/Y à H:i:s", strtotime($c_cdate)); ?> et publié le <?php echo date("d/m/Y à H:i:s", strtotime($c_pdate)); ?>.
    				<?php endif; ?>
    				<?php if ($c_cdate !== $c_udate): ?>
    					Dernière mise à jour le <?php echo date("d/m/Y à H:i:s", strtotime($c_udate)); ?>
    				<?php endif; ?>
    			</p>
				<div class="togglebutton">
					<label for="c_udate">
						<input type="checkbox" name="c_udate" id="c_udate" value="1" <?php echo set_checkbox('c_udate', '1'); ?> checked="checked" />
						Mettre à jour la date de modification
					</label>
				</div>
			<?php endif; ?>

			<div class="form-group">
				<?php
				$array_state = array(0 => "Brouillon", "Publié");
				foreach ($array_state as $key => $value): ?>
					<div class="radio radio-success">
						<label class="radio" for="<?php echo strtolower($value); ?>"><?php echo $value; ?>
							<input type="radio" id="<?php echo strtolower($value); ?>" name="c_status" value="<?php echo $key; ?>" <?php if(isset($c_status) and $c_status == $key or set_value('c_status') == $key) echo 'checked="checked"'; ?> />
						</label>
					</div>
				<?php endforeach; ?>
			</div><!-- end .form-group -->

			<div class="form-group">
				<label for="c_title">Titre de l'article *</label>
				<?php 
				$input_c_title = array(
					'name'	=> 'c_title',
					'id'	=> 'c_title',
					'value' => isset($c_title)?$c_title:set_value('c_title'),
					'class' => 'form-control'
				);
				?>
				<?php echo form_input($input_c_title); ?>
			</div><!-- end .form-group -->

			<?php if ($page == 'add_content'): ?>
				<div id="datetimepicker" class="form-group" data-date="<?php echo date("d-m-y"); ?>" data-date-format="dd-mm-yyyy">
				<label for="c_pdate">Date de publication</label>
					<input type="text" class="form-control" name="c_pdate" id="c_pdate" value="">
				</div>
			<?php endif; ?>

			<div class="form-group">
			
				<p class="show_img"><b>Image de l'article</b></p>
				<?php if ($page == 'edit_content'): ?>
				<p>
					<?php if (!empty($c_image)): ?>
						Image actuelle
					<br />
					<img  src="<?php echo $c_image; ?>" alt="" width="128px" heigth="128" />
					<?php else: ?>
					<em>Aucune image</em>
					<?php endif; ?>
				</p>
				<?php endif; ?>

				<div class="display_img">
				<?php foreach ($images as $image): ?>
					<?php
						$var		= $image['relative_path'];
						$var		= strstr($var, 'assets');
						$var		= str_replace("\\","/", $var);
						$link_image = base_url($var . '/' . $image['name']);
					?>
					<input type="radio" name="c_image" id="<?php echo $image['name']; ?>" value="<?php echo $link_image; ?>" <?php if (isset($c_image) && $c_image == $link_image) echo 'checked="checked"'; ?>>
					<label for="<?php echo $image['name']; ?>">
						<img class="img-thumbnail" src="<?php echo $link_image; ?>" alt="<?php echo $image['name']; ?>" />
					</label>
				<?php endforeach; ?>
				<?php if (!empty($c_image)): ?>
				<input type="radio" name="c_image" id="none" value="" />
				<label for="none">Pas d'image</label>
				<?php endif; ?>
				</div>

			</div><!-- end .form-group -->

		<div class="row">

			<div class="col-md-6">
				<p><b>Rubriques *</b></p>
				<?php foreach ($rubrics->result() as $row): ?>
				<?php
					if ($page == 'edit_content' and isset($rubrics) and $row->r_id == $r_id or set_value('rubrique') == $row->r_id):
						$checked = TRUE;
					else: 
						$checked = FALSE;
					endif;
				?>
				<div class="radio radio-success">
					<label>
					<?php
						$input_rubrics = array(
							'type'	  => 'radio',
							'name'	  => 'rubric',
							'id'	  => $row->r_title,
							'value'   => $row->r_id,
							'checked' => $checked
						);
					?>
					<?php echo form_radio($input_rubrics); ?>
					<?php echo $row->r_title; ?>
					</label>
				</div><!-- end .radio -->
				<?php endforeach; ?>
			</div><!-- end .col-md-6 -->

			<div class="col-md-6">
				<label for="u_login">Auteur *</label>
				<select class="form-control" id="u_login" name="u_id">
				<?php foreach ($users->result() as $user): ?>
					<option value="<?php echo $user->u_id; ?>" <?php if ($page == 'edit_content' and isset($users) and $user->u_id == $u_id or set_value('u_id') == $user->u_id) echo 'selected="selected"'; ?>><?php echo $user->u_login; ?></option>
				<?php endforeach; ?>
				</select>
			</div><!-- end .col-md-6 -->

			<div class="col-md-6">
				<div class="form-group">
					<label for="c_tags">Tag</label>
					<?php 
					$input_c_title = array(
						'name'	=> 'c_tags',
						'id'	=> 'c_tags',
						'value' => isset($c_tags)?$c_tags:set_value('c_tags'),
						'class' => 'form-control'
					);
					?>
					<?php echo form_input($input_c_title); ?>
				</div><!-- end .form-group -->
			</div><!-- end .col-md-6 -->

			<?php if ($page == 'edit_content'): ?>
			<div class="col-md-6">
				<div class="form-group">
					<label for="c_url_rw">Url de l'article *</label>
					<?php 
					$input_c_url_rw = array(
						'name'	=> 'c_url_rw',
						'id'	=> 'c_url_rw',
						'value' => isset($c_url_rw)?$c_url_rw:set_value('c_url_rw'),
						'class' => 'form-control'
					);
					?>
					<?php echo form_input($input_c_url_rw); ?>
				</div><!-- end .form-group -->
			</div><!-- end .col-md-6 -->
			<?php endif; ?>
		
		</div><!-- end .row -->

		</div>


        <div class="tab-pane" id="content">

			<div class="form-group">
				<label for="c_url_rw">Contenu de l'article *</label>
				<?php 
				$input_c_content = array(
					'name'	=> 'c_content',
					'id'	=> 'c_content',
					'value' => isset($c_content)?$c_content:set_value('c_content'),
					'class' => 'form-control'
				);
				?>
				<?php echo form_textarea($input_c_content); ?>
			</div><!-- end .form-group -->

		</div>

		<button type="submit" class="btn btn-success"> <?php if ($page == 'add_content') echo 'Ajouter'; else echo 'Modifier'; ?></button>

	</form>


<?php if ($page == 'edit_content'): ?>
	<?php if ($comments->num_rows() > 0): ?>
	<h3 id="comments">Commentaires (<?php echo $comments->num_rows(); ?>)</h3>
	<table class="table table-hover">
		<tr>
			<th>Pseudo</th>
			<th>Contenu</th>
			<th>Date</th>
		</tr>
		<?php foreach ($comments->result() as $row): ?>
		<tr>
			<td><?php echo $row->com_nickname; ?></td>
			<td><?php echo $row->com_content; ?></td>
			<td><?php echo $row->com_date; ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
<?php endif; ?>

