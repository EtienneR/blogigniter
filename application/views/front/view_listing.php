<?php if (isset($words)): ?>
	Séparement
<ul class="list-inline">
	<?php foreach ($words as $rows): ?>
	<?php if (strlen($rows) > 2): ?>
	<li>
		<a href="<?php echo base_url('s?q=' . $rows); ?>">
			<?php echo $rows; ?> (<?php echo $this->model_search->get_research($rows, '', '')->num_rows(); ?>)
		</a>
	</li>
	<?php endif; ?>
	<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (isset($query) && $query->num_rows() > 0): ?>

	<?php foreach ($query->result() as $row): ?>
		<article class="thumbnail">

			<div class="caption">

				<div class="row">
					<span class="col-md-3">
						<i class="glyphicon glyphicon-calendar"></i> 
						<?php $jour  = date("d", strtotime($row->c_cdate)); ?>
						<?php $mois  = date("m", strtotime($row->c_cdate)); ?>
						<?php $annee = date("Y", strtotime($row->c_cdate)); ?>
						<em><?php echo date_fr($jour, $mois, $annee); ?></em>
					</span>
					<h2 class="col-md-9" style="margin-top: 0">
						<?php echo content_url($row->r_url_rw, $row->c_url_rw, $row->c_title); ?>
					</h2>
				</div><!-- end of .row -->

				<div class="row">
					<div class="col-md-3">
					<?php if ($page !== 'rubric'): ?>
						<i class="glyphicon glyphicon-tag"></i> <?php echo rubric_url($row->r_url_rw, $row->r_title); ?>
					<?php endif; ?>
					<?php if (!empty($row->c_tags)): ?>
						<br /><i class="glyphicon glyphicon-tags"></i>&nbsp;
					<?php
						$explode_tags = explode(';', $row->c_tags);
						foreach ($explode_tags as $tag):
							echo tag_url($tag);
						endforeach; 
					?>
					<?php endif; ?>
					</div>
					<div class="col-md-9">
					<?php if (!empty($row->c_image)): ?>
						<div class="col-md-4">
							<?php echo img_thumb_url($row->r_url_rw, $row->c_url_rw, $row->c_image); ?>
						</div>
					<?php endif; ?>
						<p><?php echo strip_tags(Parsedown::instance()->parse(character_limiter($row->c_content, 256))); ?></p>
					</div>
				</div><!-- end of .row -->

				<div class="row">
					<div class="col-md-3">
						<?php if ($nb_comments[$row->c_id] > 0): ?>
						<i class="glyphicon glyphicon-comment"></i> <a href="<?php echo base_url($row->r_url_rw.'/'.$row->c_url_rw); ?>#comments"><?php echo $nb_comments[$row->c_id]; ?> avis
						<?php endif; ?>
					</div>
					<div class="col-md-9">
						<div class="col-md-offset-8 col-md-3 text-right text-right">
							<?php echo content_url_button($row->r_url_rw, $row->c_url_rw); ?>
						</div>
					</div>
				</div><!-- end of .row -->

			</div><!-- end of .caption -->

		</article><!-- end of .thumbnail -->
	<?php endforeach; ?>

	<?php echo $pagination; ?>

<?php else: ?>
	<?php if ($page == 'search'): ?>
		<?php if (isset($error)): ?>
			<p><?php echo $error; ?></p>
		<?php else: ?>
			<p>Aucun résultat trouvé ne correpond à votre recherche.</p>
		<?php endif; ?>
	<?php else: ?>
	<p>Aucun article n'est disponible pour le moment</p>
	<?php endif; ?>

<?php endif; ?>
