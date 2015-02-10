			<aside class="col-md-3 hidden-sm hidden-xs">

				<?php if (!empty($about)): ?>
				<h3>About</h3>
				<?php echo $about; ?>
				<?php endif; ?>

				<?php if (isset($all_content) && $all_content->num_rows > 0): ?>
				<h3>Archives</h3>
				<ul class="list-unstyled">
 				<?php foreach ($all_content->result() as $row): ?>
					<li><?php echo content_url($row->r_url_rw, $row->c_url_rw, $row->c_title); ?></li>
				<?php endforeach;?>
				</ul>
				<?php endif; ?>

				<?php if (isset($all_authors) && $all_authors->num_rows > 0): ?>
				<h3>Auteurs</h3>
				<ul class="list-unstyled">
 				<?php foreach ($all_authors->result() as $row): ?>
					<li><?php echo author_url($row->u_login); ?></li>
				<?php endforeach;?>
				</ul>
				<?php endif; ?>

				<?php if (isset($all_tags) && count($all_tags) > 0): ?>
				<h3>Tags</h3>
				<ul class="list-unstyled">
 				<?php foreach ($all_tags as $row): ?>
					<li><?php echo tag_url($row); ?></li>
				<?php endforeach;?>
				</ul>
				<?php endif; ?>

				<h3>RSS</h3>
				<a href="<?php echo base_url('rss'); ?>"><img src="<?php echo base_url('assets/images/rss.jpg'); ?>" alt="Flux RSS" /></a>

			</aside><!-- end of .col-md-3 -->
