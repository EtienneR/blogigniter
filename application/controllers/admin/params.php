<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Params extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('model_content', 'model_comment', 'model_params'));
		$this->load->library(array('form_validation', 'session', 'admin/functions'));
		$this->load->helper(array('form', 'functions'));
		define('URL_LAYOUT'		 , 'admin/view_dashboard');
		define('URL_HOME_CONTENT', 'admin');
		session_start();
		if (isset($_GET["profiler"])):
			$this->output->enable_profiler(TRUE);
		endif;
	}

	// Display all params
	public function index()
	{
		if ($this->functions->get_loged()):

			$this->load->library('form_validation');

			$data['user_data']   = $this->functions->get_user_data();
			$data['nb_comments'] = $this->functions->get_comments();

			// If user with "admin" level
			if ($data['user_data']['level'] == 1):

				$data['page']  = 'params';
				$data['title'] = 'Administration des paramètres';

				$this->form_validation->set_rules('p_title', 'Titre', 'trim|required');
				$this->form_validation->set_rules('p_m_description', 'Méta description', 'required');
				$this->form_validation->set_rules('p_about', 'A propos', 'required');
				$this->form_validation->set_rules('p_email', 'Email', 'required|valid_email');
				$this->form_validation->set_rules('p_nb_listing', 'Nombre d\'article', 'numeric');
				$this->form_validation->set_rules('p_nb_listing_f', 'Nombre d\'article RSS', 'numeric');
				$this->form_validation->set_rules('p_twitter', 'Twitter', 'trim');

				$p_title 		 = $this->input->post('p_title');
				$p_m_description = $this->input->post('p_m_description');
				$p_about  		 = $this->input->post('p_about');
				$p_email  		 = $this->input->post('p_email');
				$p_nb_listing  	 = $this->input->post('p_nb_listing');
				$p_nb_listing_f  = $this->input->post('p_nb_listing_f');
				$p_twitter  	 = $this->input->post('p_twitter');
				$p_google		 = $this->input->post('p_google');
				$params 		 = $this->model_params->get_params()->row();

				if (!empty($params)):
					$data['p_title'] 		 = $params->p_title;
					$data['p_m_description'] = $params->p_m_description;
					$data['p_about']  		 = $params->p_about;
					$data['p_email']  		 = $params->p_email;
					$data['p_nb_listing']  	 = $params->p_nb_listing;
					$data['p_nb_listing_f']  = $params->p_nb_listing_f;
					$data['p_twitter']  	 = $params->p_twitter;
					$data['p_google']		 = $params->p_google;
				endif;

				if ($this->form_validation->run() !== FALSE):

					if (empty($params)):
						$this->model_params->insert_params($p_title, $p_m_description, $p_about, $p_email, $p_nb_listing, $p_nb_listing_f, $p_twitter, $p_google);
					else:
						$this->model_params->update_params($p_title, $p_m_description, $p_about, $p_email, $p_nb_listing, $p_nb_listing_f, $p_twitter, $p_google);
					endif;

					$this->session->set_flashdata('success', 'Les paramêtres ont été mis à jour.');
					redirect(current_url());

				endif;

				$this->load->view(URL_LAYOUT, $data);

			else:
				$this->session->set_flashdata('alert', 'Vous ne disposez pas des droits nécessaires pour accèder à cette partie.');
				redirect(base_url(URL_HOME_CONTENT));
			endif;

		endif;
	}

	// Display all search
	public function search()
	{
		if ($this->functions->get_loged()):

			$this->load->model('model_search');

			$data['user_data'] = $this->functions->get_user_data();

			$data['page']  = 'search';
			$data['title'] = 'Mots recherchés';

			$data['query']			= $this->model_search->get_words();
			$data['distinct_words'] = $this->model_search->distinct_words();

			$this->load->view(URL_LAYOUT, $data);

		endif;
	}

}


/* End of file params.php */
/* Location: ./application/controllers/admin/params.php */