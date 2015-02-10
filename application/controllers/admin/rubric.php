<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rubric extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('model_comment', 'model_content', 'model_rubric'));
		$this->load->library(array('admin/functions', 'session'));
		$this->load->helper(array('form', 'functions', 'text'));
		define('URL_LAYOUT'      , 'admin/view_dashboard');
		define('URL_HOME_CONTENT', 'admin/content');
		define('URL_HOME_RUBRIC' , 'admin/rubric');
		session_start();
		if (isset($_GET["profiler"])):
			$this->output->enable_profiler(TRUE);
		endif;
	}

	// Display all rubrics
	public function index()
	{
		if ($this->functions->get_loged()):

			$data['user_data'] = $this->functions->get_user_data();

			$data['page']  = 'rubric';
			$data['title'] = 'Rubriques';

			$rubrics = $data['query'] = $this->functions->get_all_rubrics();

			$data['nb_comments'] = $this->functions->get_comments();

			$this->load->view(URL_LAYOUT, $data);
		endif;
	}

	// Add or edit a rubric
	public function edit($r_id = '')
	{
		if ($this->functions->get_loged()):

			$this->load->library('form_validation');

			$data['user_data']	 = $this->functions->get_user_data();
			$data['nb_comments'] = $this->functions->get_comments();

			$this->form_validation->set_rules('r_title', 'Titre', 'trim|required|callback_check_rubric_title');
			$this->form_validation->set_rules('r_description', 'Description', 'trim|required');

			$r_title	   = $this->input->post('r_title');
			$r_description = $this->input->post('r_description');

			// Add a rubric
			if ($this->uri->total_segments() == 3):
				$data['page']  = 'add_rubric';
				$data['title'] = 'Ajouter une rubrique';

				$r_url_rw = url_title(convert_accented_characters($r_title), '-', TRUE);

				if ($this->form_validation->run() !== FALSE):
					$this->model_rubric->create_rubric($r_title, $r_description, $r_url_rw);
					$this->session->set_flashdata('success', 'Rubrique "' . $r_title . '" ajoutée');
					redirect(base_url(URL_HOME_RUBRIC));
				endif;

			else:
				$rubric = $this->model_rubric->get_rubric($r_id, '')->row();

				// Rubric exists
				if (!empty($rubric)):
					$data['page']  		   = 'edit_rubric';
					$data['content']	   = $this->model_content->get_content_by_rubric($r_id);
					$data['r_id']	   	   = $r_id;
					$data['r_title']	   = $rubric->r_title;
					$data['r_description'] = $rubric->r_description;
					$data['r_url_rw'] 	   = $rubric->r_url_rw;
					$data['title'] 		   = 'Modifier la rubrique ' . $data['r_title'];

					// Vérification pour l'url
					$this->form_validation->set_rules('r_url_rw', 'Url', 'trim|required|callback_check_rubric_url_rw');
					$r_url_rw = $this->input->post('r_url_rw');

					if($this->form_validation->run() !== FALSE):
						$this->model_rubric->update_rubric($r_title, $r_description, $r_url_rw, $r_id);
						$this->session->set_flashdata('success', 'Rubrique "' . $r_title . '" modifiée.');
						redirect(base_url(URL_HOME_RUBRIC));
					endif;

				// Rubric unknown 
				else:
					$this->session->set_flashdata('alert', 'Cette rubrique (#' . $r_id . ') n\'existe plus ou n\'a jamais existé.');
					redirect(URL_HOME_RUBRIC);
				endif;

			endif;

			$this->load->view(URL_LAYOUT, $data);

		endif;
	}


	// Check if a rubric already exists
	public function check_rubric_title($r_title)
	{
		$r_id = $this->uri->segment(4);

		if ($this->model_rubric->check_title($r_id, $r_title)->num_rows() == 1):
			$this->form_validation->set_message('check_rubric_title', 'Impossible de rajouter la rubrique "' . $r_title . '" car cette dernière existe déjà.');
			return FALSE;
		else:
			return TRUE;
		endif;
	}

	// Check if the URL rubric already exists
	public function check_rubric_url_rw($r_url_rw)
	{
		$r_id = $this->uri->segment(4);

		if ($this->model_rubric->check_url_rw($r_id, $r_url_rw)->num_rows() == 1):
			$this->form_validation->set_message('check_rubric_url_rw', 'Impossible d\'attribuer l\'url "' . $r_url_rw . '" car cette dernière existe déjà.');
			return FALSE;
		else:
			return TRUE;
		endif;
	}

	// Delete a rubric
	public function delete($r_id)
	{
		if ($this->functions->get_loged()):
			// Rubric exists
			if ($this->model_rubric->get_rubric($r_id)->num_rows() == 1):

				// No content attached to this rubrics
				if ($this->model_content->get_content_by_rubric($r_id)->num_rows() == 0):
					$this->model_rubric->delete_rubric($r_id);
					$this->session->set_flashdata('success', 'Rubrique supprimée.');
				// Content(s) attached to this rubrics
				else:
					$r_title = $this->db->select('r_title')->from('rubric')->where('r_id', $r_id)->limit(1)->get()->result()[0]->r_title;
					$url = base_url('admin/content/r?q=' . $r_title);
					$this->session->set_flashdata('alert', 'Impossible de supprimer cette rubrique car il y a un ou plusieurs article(s) rattaché(s). <a href="' . $url . '">Afficher</a>');
				endif;

			// Rubric unknown
			else:
				$this->session->set_flashdata('alert', 'Cette rubrique (#' . $r_id . ') n\'existe plus ou n\'a jamais existé.');
			endif;

			redirect(base_url(URL_HOME_RUBRIC));

		endif;
	}

}


/* End of file dashboard.php */
/* Location: ./application/controllers/admin/dashboard.php */