<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title><?php echo strip_tags($title); ?></title>
		<meta name="description" content="<?php echo $title ; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php echo css_url('bootstrap.min'); ?>
		<?php echo css_url('material-wfont.min'); ?>
		<?php echo css_url('ripples.min'); ?>
		<?php echo css_url('ekko-lightbox.min'); ?>
	</head>
	<body class="container-fluid">

		<nav class="navbar navbar-default" role="navigation">

			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button><!-- end .navbar-toggle -->
				<a class="navbar-brand" href="<?php echo base_url('admin/content'); ?>">Dashboard</a>
			</div><!-- end .navbar-header -->

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li <?php if ($page == 'home' or $page == 'add_content' or $page == 'edit_content' or $page == 'author' or $page == 'tags' or $page == 'c_rubric'){ echo "class='active'"; }; ?>>
						<a href="<?php echo base_url('admin/content'); ?>" class="dropdown-toggle">Articles</a>
					</li>
					<li <?php if ($page == 'rubric' or $page == 'add_rubric' or $page == 'edit_rubric'){ echo "class='active'"; }; ?>>
						<a href="<?php echo base_url('admin/rubric'); ?>">Rubriques</a>
					</li>
					<li <?php if ($page == 'comment' or $page == 'add_comment' or $page == 'edit_comment'){ echo "class='active'"; }; ?>>
						<a href="<?php echo base_url('admin/comments'); ?>">Commentaires 
						<?php if (!empty($nb_comments)): ?>
							(<b><?php echo $nb_comments; ?></b>)
						<?php endif; ?>
						</a>
					</li>
					<li <?php if ($page == 'users' or $page == 'add_user' or $page == 'edit_user'){ echo "class='active'"; }; ?>>
						<a href="<?php echo base_url('admin/user'); ?>">Utilisateurs</a>
					</li>
					<li <?php if ($page == 'gallery'){ echo "class='active'"; }; ?>>
						<a href="<?php echo base_url('admin/medias'); ?>">Galerie</a>
					</li>
				</ul><!-- end .nav navbar-nav -->
				<ul class="nav navbar-nav navbar-right">
					<li><a href="<?php echo base_url(); ?>" target="_blank">Le blog</a></li>
					<li class="dropdown">
						<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?php echo $user_data['login']; ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<?php if ($user_data['level'] == 1): ?>
							<li>
								<a href="<?php echo base_url('admin/params'); ?>">
									<i class="glyphicon glyphicon-cog"></i> Paramêtres
								</a>
							</li>
							<li>
								<a href="<?php echo base_url('admin/search'); ?>">
									<i class="glyphicon glyphicon-search"></i> Mots recherches
								</a>
							</li>
							<?php endif; ?>
							<li>
								<a href="<?php echo base_url('admin/user/edit/' . $user_data['id_user']); ?>">
									<i class="glyphicon glyphicon-user"></i> Mon compte 
								</a>
							</li>
							<li>
								<a href="<?php echo base_url('admin/user/change_password'); ?>">
									<i class="glyphicon glyphicon-user"></i> Changer mot de passe
								</a>
							</li>
							<li>
								<a href="<?php echo base_url('admin/logout'); ?>">
									<i class="glyphicon glyphicon-off"></i> Se déconnecter
								</a>
							</li>
						</ul><!-- end .dropdown-menu -->
					</li><!-- end .dropdown -->
				</ul><!-- end .nav .navbar-nav .navbar-right -->
			</div><!-- end .collapse .navbar-collapse #bs-example-navbar-collapse-1 -->

		</nav><!-- end .navbar .navbar-default -->

		<?php if (validation_errors()): ?>
			<?php echo validation_errors('<div class="alert alert-danger">', ' <a class="close" data-dismiss="alert" href="#">&times;</a></div>'); ?>
		<?php endif; ?>

		<?php if ($this->session->flashdata('success')): ?>
		<div class="alert alert-success">
			<i class="mdi-action-info-outline"></i> <?php echo $this->session->flashdata('success'); ?> <a class="close" data-dismiss="alert" href="#"><i class="mdi-action-highlight-remove"></i></a>
		</div>
		<?php endif; ?>

		<?php if ($this->session->flashdata('alert')): ?>
		<div class="alert alert-danger">
			<i class="mdi-alert-error"></i> <?php echo $this->session->flashdata('alert'); ?> <a class="close" data-dismiss="alert" href="#"><i class="mdi-action-highlight-remove"></i></a>
		</div>
		<?php endif; ?>

		<?php if ($this->session->flashdata('warning')): ?>
		<div class="alert alert-warning">
			<i class="mdi-alert-warning"></i> <?php echo $this->session->flashdata('warning'); ?> <a class="close" data-dismiss="alert" href="#"><i class="mdi-action-highlight-remove"></i></a>
		</div>
		<?php endif; ?>


	<div class="row">

		<div class="col-md-12">

			<section>
				<div class="btn-group" role="group">
					<a class="btn btn-danger btn-sm" href="<?php echo base_url('admin/content/edit'); ?>">
						<i class="glyphicon glyphicon-plus"></i> Ajouter un article
					</a>
					<a class="btn btn-info btn-sm" href="<?php echo base_url('admin/rubric/edit'); ?>" >
						<i class="glyphicon glyphicon-plus"></i> Ajouter une rubrique
					</a>
					<?php if ($user_data['level'] == 1): ?>
					<a class="btn btn-sm" href="<?php echo base_url('admin/user/edit'); ?>">
						<i class="glyphicon glyphicon-plus"></i> Ajouter un utilisateur
					</a>
					<?php endif; ?>
					<?php if (current_url() !== base_url('admin/user/' . $user_data['id_user'])): ?>
						<a class="btn btn-sm" href="<?php echo base_url('admin/user/' . $user_data['id_user']); ?>">
							Mes articles (<?php echo ($this->model_content->get_content_by_user($user_data['id_user'], '', '')->num_rows); ?>)
						</a>
					<?php else:?>
						<a class="btn btn-sm" href="<?php echo base_url('admin/content'); ?>">Tous les articles</a>
					<?php endif; ?>
					<?php if ($page == 'edit_content'): ?>
						<a class="btn btn-sm" href="<?php echo base_url('admin/content/delete/' . $c_id); ?>" onclick="return deleteContentConfirm()" title="Supprimer cet article">
							<i class="glyphicon glyphicon-trash"></i>
						</a>
						<?php if ($c_status == 1): ?>
							<a class="btn btn-sm" href="<?php echo base_url($r_url_rw . '/' . $c_url_rw); ?>" title="Aperçu">
								<i class="glyphicon glyphicon-eye-open"></i>
							</a>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ($page == 'edit_rubric'): ?>
					<a class="btn btn-sm" href="<?php echo base_url('admin/rubric/delete/' . $r_id); ?>" onclick="return deleteRubricConfirm()" title="Supprimer">
						<i class="glyphicon glyphicon-trash"></i>
					</a>
					<?php endif; ?>
				</div>
			</section>

		</div><!-- end of .col-md-12 -->

	</div><!-- end of .row -->

	<div class="row-fluid ">

		<div class="col-md-12 well">
			<h1><?php echo $title; ?></h1>
			<?php switch ($page) {
				case 'home':
				case 'author':
				case 'tags':
				case 'c_rubric':
					$this->load->view('admin/dashboard/content/view_listing_content');
					break;

				case 'add_content':
				case 'edit_content':
					$this->load->view('admin/dashboard/content/view_edit_content');
					break;

				case 'rubric':
					$this->load->view('admin/dashboard/rubrics/view_listing_rubric');
					break;

				case 'add_rubric':
				case 'edit_rubric':
					$this->load->view('admin/dashboard/rubrics/view_edit_rubric');
					break;

				case 'comment':
					$this->load->view('admin/dashboard/comments/view_listing_comments');
					break;

				case 'users':
					$this->load->view('admin/dashboard/users/view_listing_users');
					break;

				case 'add_user':
				case 'edit_user':
					$this->load->view('admin/dashboard/users/view_edit_user');
					break;

				case 'change_password':
					$this->load->view('admin/dashboard/users/view_change_password');
					break;

				case 'gallery':
					$this->load->view('admin/dashboard/gallery/view_listing_gallery');
					break;

				case 'params':
					$this->load->view('admin/dashboard/params/view_edit_params');
					break;

				case 'search':
					$this->load->view('admin/dashboard/search/view_listing_search');
					break;

				default:
					$this->load->view('admin/dashboard');
					break;
			}
			?>
		</div><!-- end .col-md-10 -->

	</div><!-- end .row --> 

	<footer data-role="footer" class="container-fluid">
		<p class="footer" style="text-align: center">Propulsé par Codeigniter - Temps d'exécution : <strong>{elapsed_time}</strong> secondes</p>
	</footer>

	<?php
		echo js_url('jquery.min');
		echo js_url('ekko-lightbox.min');
		echo js_url('bootstrap.min');
		echo js_url('material.min');
		echo js_url('ripples.min');
	?>

	<script>
		$(document).ready(function ($) {
			$.material.init();

			// delegate calls to data-toggle="lightbox"
			$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
				event.preventDefault();
				return $(this).ekkoLightbox({
					always_show_close: true
				});
			});

		});
	</script>

	<?php
		if ($page == 'add_content'):
		echo js_url('bootstrap-datepicker');
	?>

	<script>
		$('#datetimepicker input').datepicker({
	});
	</script>

	<?php
		endif;
		if ($page == 'add_content' or $page == 'edit_content' or $page == 'home'):
			echo js_url('redactor.min');
	?>
	<script>
		function deleteContentConfirm() {
			var a = confirm("Etes-vous sur de vouloir supprimer cet article ?!");
			if (a){
				return true;
			}
			else{
				return false;
			}
		}
	</script>
	<script>
		$('.show_img').css('cursor', 'pointer');
		$('.display_img').css('display', 'none');
		$('.show_img').click(function() {
			$( ".display_img" ).toggle();
		});
	</script>
	<?php
		endif;
	?>

	<?php if ($page == 'rubric' or $page =='edit_rubric'): ?>
	<script>
		function deleteRubricConfirm() {
			var a = confirm("Etes-vous sur de vouloir supprimer cette catégorie ?!");
			if (a){
				return true;
			}
			else{
				return false;
			}
		}
	</script>
	<?php endif; ?>

	</body>

</html>