<?php 
	echo form_open(base_url(uri_string()));
?>

	<div class="form-group">
		<label for="r_title" class="col-lg-4 control-label">Titre de la rubrique :</label>
		<div class="col-lg-8">
			<input type="text" class="form-control" id="r_title" name="r_title" value="<?php if (isset($r_title)) echo $r_title; echo set_value('r_title'); ?>" required />
		</div>
	</div><!-- end .form-group -->

	<div class="form-group">
		<label for="r_description" class="col-lg-4 control-label">Description (256 caractères) de la rubrique :</label>
		<div class="col-lg-8">
			<input type="text" id="r_description" class="form-control" name="r_description" value="<?php if (isset($r_description)) echo $r_description; echo set_value('r_description'); ?>" required />
		</div>
	</div><!-- end .form-group -->

	<?php if ($page == 'edit_rubric'): ?>
	<div class="form-group">
		<label for="r_url_rw" class="col-lg-4 control-label">Url de la rubrique :</label>
		<div class="col-lg-8">
			<input type="text" id="r_url_rw" class="form-control" name="r_url_rw" value="<?php if (isset($r_url_rw)) echo $r_url_rw; echo set_value('r_url_rw'); ?>" required />
		</div>
	</div><!-- end .form-group -->
	<?php endif; ?>

	<input type="submit" class="btn btn-success" value="<?php if ($page == 'add_rubric') echo 'Ajouter'; else echo 'Modifier'; ?>" />

</form>

	<?php if ($page == 'edit_rubric'): ?>
		<?php if ($content->num_rows > 0): ?>
			<h4 id="others"><?php echo $content->num_rows; ?> Article(s) associé(s) à cette rubrique</h4>
			<ul class="unstyled">
			<?php foreach ($content->result() as $row): ?>
				<li><a href="<?php echo base_url('admin/content/edit/' . $row->c_id); ?>"><?php echo $row->c_title; ?></a></li>
			<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<h4>Aucun article n'est rattaché à cette catégorie</h4>
		<?php endif; ?>

	<?php endif; ?>