<div class="row">

	<div class="col-lg-8">

		<?php 
			echo form_open(base_url(uri_string()));
		?>

		<div class="form-group">
			<label for="u_login">Login* :</label>
			<input type="text" class="form-control input-lg" id="u_login" name="u_login" value="<?php if (isset($u_login)) echo $u_login; echo set_value('u_login'); ?>" required />
		</div>

		<?php if ($page == 'add_user'): ?>
		<div class="form-group">
			<label for="u_pass">Password* :</label>
			<input type="password" id="u_pass" class="form-control input-lg" name="u_pass" value="<?php if (isset($u_pass)) echo $u_pass; echo set_value('u_pass'); ?>" required />
		</div>
		<?php endif; ?>

		<div class="form-group">
			<label for="u_email">Email* :</label>
			<input type="text" class="form-control input-lg" id="u_email" name="u_email" value="<?php if (isset($u_email)) echo $u_email; echo set_value('u_email'); ?>" required />
		</div>

		<div class="form-group">
			<label for="u_biography">Biographie* :</label>
			<textarea class="form-control input-lg" id="u_biography" name="u_biography"><?php if (isset($u_biography)) echo $u_biography; echo set_value('u_biography'); ?></textarea>
		</div>

		<div class="form-group">
			<label for="u_twitter">Compte Twitter :</label>
			<input type="text" class="form-control input-lg" id="u_twitter" name="u_twitter" value="<?php if (isset($u_twitter)) echo $u_twitter; echo set_value('u_twitter'); ?>" />
		</div>

		<div class="form-group">
			<label for="u_google">Compte Google + :</label>
			<input type="text" class="form-control input-lg" id="u_google" name="u_google" value="<?php if (isset($u_google)) echo $u_google; echo set_value('u_google'); ?>" />
		</div>

		<div class="form-group">
			<p>Level*  :</p>
			<?php
			$array_level = array(0 => "Modérateur", "Admin");
			foreach ($array_level as $key => $value): ?>
				<div class="radio radio-success">
					<label class="radio" for="<?php echo strtolower($value); ?>"><?php echo $value; ?>
						<input type="radio" id="<?php echo strtolower($value); ?>" name="u_level" value="<?php echo $key; ?>" <?php if(isset($u_level) and $u_level == $key or set_value('u_level') == $key) echo 'checked="checked"'; ?> required />
					</label>
				</div>
			<?php endforeach; ?>
		</div>


		<?php if ($page == "edit_user"): ?>
		<?php 
			if ($user_data['id_user'] == $u_id):
				$adjective = "mon";
			else:
				$adjective = "ce";
			endif; 
		?>
		<div class="form-group">
			<?php if ($u_status == 1): ?>
			<div class="radio radio-success">
				<label class="radio" for="desactivate">Désactiver <?php echo $adjective; ?> compte
					<input type="radio" id="desactivate" name="u_status" value="0" />
				</label>
			</div>
			<?php else: ?>
			<div class="radio radio-success">
				<label class="radio" for="activate">Activer <?php echo $adjective; ?> compte
					<input type="radio" id="activate" name="u_status" value="1" />
				</label>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<input type="submit" class="btn btn-success" value="<?php if ($page == 'add_user') echo 'Ajouter'; else echo 'Modifier'; ?>" />

	</form>

	</div>

	<div class="col-lg-4">
	<?php if ($page == "add_user"): ?>
		<p>* : champs obligatoires</p>
	
	<?php elseif ($user_data['id_user'] == $u_id): ?>
		<h4>Autre option</h4>
		<ul>
			<li>
				<a href="<?php echo base_url('admin/user/change_password'); ?>">Changer mon mot de passe</a>
			</li>
		</ul>
	<?php endif; ?>
	</div>

</div><!-- end .col-fluid -->