<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title><?php echo $title; ?></title>
		<meta name="description" content="<?php echo $title ; ?>" />
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
		<?php echo css_url('material-wfont.min'); ?>
	</head>
	<body>

		<div class="container">

			<div class="col-md-6 col-md-offset-3">
				<br /><!-- Not pretty :( -->
				<br /><!-- Not pretty too:( -->
				<?php if($this->session->flashdata('success')): ?>
				<div class="alert alert-success">
					<?php echo $this->session->flashdata('success'); ?> <a class="close" data-dismiss="alert" href="#">&times;</a>
				</div>
				<?php endif; ?>	

				<?php if($this->session->flashdata('alert')): ?>
				<div class="alert alert-danger">
					<?php echo $this->session->flashdata('alert'); ?> <a class="close" data-dismiss="alert" href="#">&times;</a>
				</div>
				<?php endif; ?>
				
				<div class="well bs-component">

					<h1 class="text-center"><?php echo $title; ?></h1>

					<?php echo form_open(base_url('admin')); ?>
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-lg-1 col-lg-offset-1 control-label" for="username">
									<i class="mdi-social-person"></i>
								</label>
								<div class="col-lg-9">
									<input type="text" class="form-control input-lg" placeholder="Username" name="username" id="username" autocomplete="off" required />
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-1 col-lg-offset-1 control-label" for="password">
									<i class="mdi-communication-vpn-key"></i>
								</label>
								<div class="col-lg-9">
									<input type="password" class="form-control input-lg" placeholder="Password" name="password" id="password" autocomplete="off" required />
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 col-md-offset-2">
									<button class="col-md4 btn" onclick="window.location.href='<?php echo base_url('admin/reset_password'); ?>'">Mot de passe oublié</button>	
									<button class="col-md-4 btn btn-primary" type="submit">Login</button>
								</div>
							</div>
						</div>
					</form>

				</div>

				<p class="text-center">
					<a href="<?php echo base_url(); ?>">Retourner sur le blog</a>
				</p>

			</div>

		</div>

	<?php 
		echo js_url('jquery.min');
		echo js_url('bootstrap.min');
		echo js_url('material.min');
		echo js_url('ripples.min');
	?>
		<script>
			$.material.init();
		</script>
	</body>

</html>
