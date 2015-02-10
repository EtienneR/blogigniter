		<article class="thumbnail">
			<?php echo form_open(current_url()); ?>
			<div class="form-group">
				<label for="name">Nom</label> <?php echo form_error('name', '<span style="color: red;">', '</span>'); ?>
				<input type="text" class="form-control" name="name" id="name" placeholder="Votre nom" value="<?php echo set_value('name'); ?>" />
			</div>
			<div class="form-group">
				<label for="email">Email</label> <?php echo form_error('email', '<span style="color: red">', '</span>'); ?>
				<input type="text" class="form-control" name="email" id="email" placeholder="Votre email" value="<?php echo set_value('email'); ?>" />
			</div>
			<div class="form-group">
				<label for="message">Message</label> <?php echo form_error('message', '<span style="color: red">', '</span>'); ?>
				<textarea name="message" class="form-control" id="message" placeholder="Votre message"><?php echo set_value('message'); ?></textarea>
			</div>
				<input type="submit" class="btn btn-primary" value="Envoyer">
			</form>
		</article><!-- end of .thumbnail -->
