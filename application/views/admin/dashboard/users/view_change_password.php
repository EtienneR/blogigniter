<div class="row">

	<div class="col-lg-8">

		<?php 
			echo form_open(base_url(uri_string()));
		?>

			<div class="form-group">
				<label for="u_old_pass">Ancien Password :</label>
				<input type="password" id="u_old_pass" class="form-control" name="u_old_pass" value="" required />
			</div><!-- end .form-group -->

			<div class="form-group">
				<label for="u_pass">Nouveau Password :</label>
				<input type="password" id="u_pass" class="form-control" name="u_pass" value="" required />
			</div><!-- end .form-group -->

			<div class="form-group">
				<label for="u_pass_2">Nouveau Password (confirmation) :</label>
				<input type="password" id="u_pass_2" class="form-control" name="u_pass_2" value="" required />
			</div><!-- end .form-group -->
			
			<input type="submit" class="btn btn-success" value="Modifier" ?>

		</form>

</div><!-- end .col-lg-8 -->

	<div class="col-lg-4">
		<h4>Autre option</h4>
		<ul>
			<li>
				<a href="<?php echo base_url('admin/user/edit/'. $user_data['id_user']); ?>">Editer mon profil</a>
			</li>
		</ul>
	</div>

</div><!-- end .col-fluid -->