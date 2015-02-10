<?php $this->load->view('front/include/view_header.php'); ?>


	<div class="row">
		<div class="col-md-9">
			<!-- Start breadcrumb -->
			<ol class="breadcrumb" itemtype="http://data-vocabulary.org/Breadcrumb" itemscope="">
				<?php if ($page == 'home'): ?>
				<li class="active">
					<?php echo $breadcrumb; ?>
				</li>
				<?php else: ?>
				<li>
					<a href="<?php echo base_url(); ?>">Home</a>
				</li>
				<?php if ($page == 'content'): ?>
				<li>
					<a href="<?php echo base_url($r_url_rw); ?>"><?php echo $r_title; ?></a>
				</li>
				<?php endif; ?>
				<li class="active">
					<?php echo $breadcrumb; ?>
				</li>
				<?php endif; ?>
			</ol>
			<!-- End breadcrumb -->

			<?php if ($page !== 'home'): ?>
			<div class="page-header">
				<h1 class="text-center"><?php echo $title; ?></h1>
			</div>
			<?php endif; ?>


			<?php 
			switch ($page) {
				case 'home':
				case 'rubric':
				case 'author':
				case 'search':
						$this->load->view('front/view_listing.php');
					break;

				case 'content':
						$this->load->view('front/view_content.php');
					break;

				case 'contact':
						$this->load->view('front/view_contact.php');
					break;

				case '404':
						$this->load->view('front/view_404.php');
					break;

				default:
					break;
			}
			?>
		</div><!-- end of .col-md-9 -->

		<?php $this->load->view('front/include/view_sidebar.php'); ?>

	</div><!-- end of .row -->


<?php $this->load->view('front/include/view_footer.php'); ?>