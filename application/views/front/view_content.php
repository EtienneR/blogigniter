		<article class="thumbnail">
			<div class="caption">
				<p class="row">
					<span class="col-md-2 hidden-sm hidden-xs">
						<i class="glyphicon glyphicon-tag"></i> <?php echo rubric_url($r_url_rw, $r_title); ?>
					</span>
					<?php if (!empty($tags['0'])): ?>
					<span class="col-md-3 col-xs-12 col-md-offset-2">
						<i class="glyphicon glyphicon-tags"></i>&nbsp;
						<?php 
							foreach ($tags as $tag):
								echo tag_url($tag);
							endforeach;
						?>
					</span>
					<?php endif; ?>
					<span class="col-md-4 col-xs-12 col-md-offset-1 text-right">
						<i class="glyphicon glyphicon-calendar"></i> 
						<em><?php echo $c_date; ?></em> - <?php echo $nb_comments; ?> avis
					</span>
				</p><!-- end of .row -->
				<p>
					Rédigé par <a href="<?php echo base_url('author/' . $u_login); ?>" title="Voir tous les autres articles de cet auteur"><?php echo $u_login; ?></a>
					<?php if ($c_pdate !== $c_udate): ?>, mis à jour le 
						<em><?php echo $udate;?></em>
					<?php endif; ?>
				</p>

				<?php if ($c_image): ?>
					<?php echo img_thumb($c_image); ?>
					<br />
				<?php endif; ?>
				<?php echo str_replace('<pre', '<pre class="line-numbers"', $c_content); ?>

				<?php if (!empty($twitter)): ?>
					<br />
					<a href="https://twitter.com/share" class="twitter-share-button" data-via="<?php echo $twitter; ?>" data-lang="fr">Tweeter</a>
					<script>
					!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
					</script>
				<?php endif; ?>

				<?php if (!empty($google)): ?>
					<script src="https://apis.google.com/js/platform.js" async defer>
						{lang: 'fr'}
					</script>
					<div class="g-plusone" data-href="<?php echo current_url(); ?>"></div>
				<?php endif; ?>

			</div><!-- end of .caption -->
		</article><!-- end of .thumbnail -->

		<!-- Nav tabs -->
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#home" data-toggle="tab">Bio</a>
			</li>
			<?php if ($query_same_user->num_rows() > 0): ?>
			<li>
				<a href="#profile" data-toggle="tab">Ses derniers articles</a>
			</li>
			<?php endif; ?>
			<?php if ($query_same_rubric->num_rows() > 0): ?>
			<li>
				<a href="#same_articles" data-toggle="tab">
					Article<?php if ($query_same_rubric->num_rows() > 1){ echo 's';} ?> de la même catégorie
				</a>
			</li>
			<?php endif; ?>
			<?php if ($query_same_tag->num_rows() > 0): ?>
			<li>
				<a href="#same_tag" data-toggle="tab">
					Article<?php if ($query_same_tag->num_rows() > 1){ echo 's';} ?> recommandé<?php if ($query_same_rubric->num_rows() > 1){ echo 's';} ?>
				</a>
			</li>
			<?php endif; ?>
			<li>
				<a href="#comments" data-toggle="tab">Avis (<?php echo $comments->num_rows(); ?>)</a>
			</li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">

			<div class="tab-pane active" id="home">
				<p><?php echo $u_login; ?></p>
				<?php if (!empty($u_biography)): ?>
				<p><?php echo $u_biography; ?></p>
				<?php else: ?>
				<p><em>Cet auteur n'a pas rédigé sa biographie</em></p>
				<?php endif; ?>
				<?php if (!empty($u_twitter)): ?>
					<p>Compte twitter : <a href="http://twitter.com/<?php echo $u_twitter; ?>" rel="nofollow" target="_blank"><?php echo $u_twitter; ?></a></p>
				<?php endif; ?>
				<?php if (!empty($u_google)): ?>
					<p>Compte Google Plus : <a href="https://plus.google.com/<?php echo $u_google; ?>" rel="nofollow" target="_blank">https://plus.google.com/<?php echo $u_google; ?></a></p>
				<?php endif; ?>
			</div>

			<?php if ($query_same_user->num_rows() > 0): ?>
			<div class="tab-pane" id="profile">
				<ul class="list-unstyled">
				<?php foreach ($query_same_user->result() as $row): ?>
					<li>
						<?php echo content_url($row->r_url_rw, $row->c_url_rw, $row->c_title); ?>
						<br />
						<?php echo $row->c_pdate; ?>
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>

			<?php if ($query_same_rubric->num_rows() > 0): ?>
			<div class="tab-pane" id="same_articles">
				<ul class="list-unstyled">
				<?php foreach ($query_same_rubric->result() as $row): ?>
					<li><?php echo content_url($row->r_url_rw, $row->c_url_rw, $row->c_title); ?></li>
				<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>

			<?php if ($query_same_tag->num_rows() > 0): ?>
			<div class="tab-pane" id="same_tag">
				<ul class="list-unstyled">
				<?php foreach ($query_same_tag->result() as $row): ?>
					<li><?php echo content_url($row->r_url_rw, $row->c_url_rw, $row->c_title); ?></li>
				<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>

			<div class="tab-pane" id="comments">
				<?php if (isset($comments) && $comments->num_rows() > 0): ?>
				<ul class="list-group">
				<?php foreach ($comments->result() as $comment): ?>
					 <li class="list-group-item">
						<span class="badge"><?php echo date_fr(date("d", strtotime($comment->com_date)), date("m", strtotime($comment->com_date)), date("Y", strtotime($comment->com_date))); ?> à <?php echo date("H", strtotime($comment->com_date)); ?>h<?php echo date("m", strtotime($comment->com_date)); ?></span>
						<p>Posté par <em><?php echo $comment->com_nickname; ?></em>
						<br />
						<br />
						<?php echo $comment->com_content; ?></p>
					</li>
				<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>

		</div>


		<h3 id="post">Poster un avis</h3>

		<?php if ($this->session->flashdata('success')): ?>
		<div class="alert alert-success">
			<?php echo $this->session->flashdata('success'); ?> <a class="close" data-dismiss="alert" href="#">&times;</a>
		</div>
		<?php endif; ?>

		<?php if (validation_errors()): ?>
			<?php echo validation_errors('<div class="alert alert-danger">', ' <a class="close" data-dismiss="alert" href="#">&times;</a></div>'); ?>
		<?php endif; ?>

		<?php 
			echo form_open('');
		?>

			<div class="form-group">
				<label for="com_nickname">Pseudo :</label>
				<input type="text" class="form-control" id="com_nickname" name="com_nickname" value="<?php if (isset($com_nickname)) echo $com_nickname; echo set_value('com_nickname'); ?>" required />
			</div><!-- end .form-group -->

			<div class="form-group">
				<label for="com_content">Contenu :</label>
				<textarea type="text" id="com_content" class="form-control" name="com_content" required><?php if (isset($com_content)) echo $com_content; echo set_value('com_content'); ?></textarea>
			</div><!-- end .form-group -->

			<div class="form-group">
				<label for="captcha">Captcha <?php echo $captcha_image; ?></label>
				<input type="text" class="form-control" id="captcha" name="captcha" value="" required />
			</div><!-- end .form-group -->


			<input type="submit" class="btn btn-success" value="Envoyer" />

		</form>