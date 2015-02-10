<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, maximum-scale=1" />
		<title><?php echo $meta_title; ?></title>
		<meta name="description" content="<?php echo $meta_desc; ?>" />

		<?php if ($page !== '404'): ?>
			<?php if ($page == 'search'): ?>
				<link rel="canonical" href="<?php echo base_url('s?q=' . $research); ?>" />
			<?php else: ?>
				<link rel="canonical" href="<?php echo current_url(); ?>" />
			<?php endif; ?>
		<?php endif; ?>

		<?php if (isset($meta_pagination)): ?>
			<?php echo $meta_pagination; ?>
		<?php endif; ?>

		<?php if ( !empty($facebook) and ($page == 'content' or $page == 'rubric') ): ?>
			<!-- Facebook metas (start) -->
			<meta property="og:type" content="article" />
			<meta property="og:title" content="<?php echo $meta_title; ?>" />
			<meta property="og:description" content="<?php echo $meta_desc; ?>" />
			<?php if ($page == 'content'): ?>
				<?php if ($c_image): ?>
					<meta property="og:image" content="<?php echo $c_image; ?>" />
				<?php endif; ?>
			<?php endif; ?>
			<meta property="og:url" content="<?php echo current_url(); ?>" />
			<meta property="og:site_name" content="<?php echo base_url(); ?>" />
			<!-- Facebook metas (end) -->
		<?php endif; ?>

		<?php if ( !empty($twitter) and ($page == 'content') ): ?>
			<!-- Twitter card metas (start) -->
			<?php echo meta_twitter($twitter, $meta_title, $meta_desc, $c_image, $page); ?>
			<!-- Twitter card metas (end) -->
		<?php endif; ?>

		<?php if ( !empty($google) and ($page == 'content') ): ?>
			<?php echo meta_google($google); ?>
		<?php endif; ?>

		<?php echo css_url('bootstrap.min'); ?>
		<?php echo css_url('material-wfont.min'); ?>
		<?php echo css_url('ripples.min'); ?>

		<?php if ($page == 'content'): ?>
		<?php echo css_url('prismjs'); ?>
		<?php endif; ?>

	</head>
	<body>

	<div class="container">

		<h1>
			<a href="<?php echo base_url(); ?>">
				<?php echo $p_title; ?>
			</a>
		</h1>
		
		<nav class="navbar navbar-default" role="navigation">

			<div class="container-fluid">

				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

					<ul class="nav navbar-nav navbar-left" role="navigation">
					<?php foreach ($query_all_rubrics->result() as $row): ?>
						<li <?php if($this->uri->segment(1) == $row->r_url_rw){ echo 'class="active"'; }?>>
							<a href="<?php echo base_url($row->r_url_rw); ?>" <?php if($this->uri->segment(1) == $row->r_url_rw){ echo 'title="Rubrique actuelle"';}?>>
								<?php echo $row->r_title; ?>
							</a>
						</li>
					<?php endforeach; ?>
					</ul>
					<form action="<?php echo base_url('s'); ?>" method="get" class="navbar-form navbar-right" role="search">
						<div class="form-group">
							<input name="q" type="search" class="form-control" placeholder="Rechercher" required>
						</div>
					</form>

				</div><!-- /.navbar-collapse -->

			</div><!-- /.container-fluid -->

		</nav>