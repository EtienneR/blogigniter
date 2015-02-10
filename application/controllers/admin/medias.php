<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medias extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('model_content', 'model_user', 'model_comment'));
		$this->load->library(array('form_validation', 'session', 'admin/functions'));
		$this->load->helper(array('file', 'form', 'functions', 'text'));
		define('URL_LAYOUT' , 'admin/view_dashboard');
		session_start();
		if (isset($_GET["profiler"])):
			$this->output->enable_profiler(TRUE);
		endif;
	}

	// Display the gallery
	public function index()
	{
		if ($this->functions->get_loged()):
			$data['user_data']	 = $this->functions->get_user_data();

			$data['nb_comments'] = $this->functions->get_comments();

			$data['page']  = 'gallery';
			$data['title'] = 'Galerie';

			$data['query'] = get_dir_file_info('./assets/img/thumb');

			if (isset($_GET['delete'])):
				unlink('./assets/img/thumb/' . $_GET['delete']);
				unlink('./assets/img/'.str_replace('_thumb', '', $_GET['delete']));
				$this->session->set_flashdata('success', 'Image ' . $_GET['delete'] . ' supprimée.');
				redirect(base_url('admin/medias'));
			endif;

			$this->load->view(URL_LAYOUT, $data);

		endif;
	}

	// Upload treatment
	public function upload($error = array())
	{
		if ($this->functions->get_loged()):
			$config['upload_path']	 = './assets/img/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['max_size']		 = '1024';
			$config['max_width']	 = '2048';
			$config['max_height']	 = '2048';

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload()):
				$error = array($this->upload->display_errors());
				$this->session->set_flashdata('alert', strip_tags($error['0'], 'p'));
				redirect(base_url('admin/medias'));
			else:
				// Resize image
				$upload_data = $this->upload->data();
				$config['image_library']  = 'gd2';
				$config['source_image']   = $upload_data["full_path"];
				$config['create_thumb']   = TRUE;
				$config['new_image']	  = './assets/img/thumb';
				$config['maintain_ratio'] = TRUE;
				$config['width']		  = 150;
				$config['height']		  = 150;
				$this->load->library('image_lib', $config);

				$this->image_lib->resize();

				$this->session->set_flashdata('success', 'Image importée.');
				redirect(base_url('admin/medias'));
			endif;

		endif;
	}

}


/* End of file medias.php */
/* Location: ./application/controllers/admin/medias.php */