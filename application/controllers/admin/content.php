<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('model_comment', 'model_content', 'model_rubric', 'model_user'));
		$this->load->library(array('admin/functions', 'session'));
		$this->load->helper(array('form', 'functions', 'text'));
		define('URL_LAYOUT'      , 'admin/view_dashboard');
		define('URL_HOME_CONTENT', 'admin/content');
		session_start();
		if (isset($_GET['profiler'])):
			$this->output->enable_profiler(TRUE);
		endif;
	}

	private function _all_tags()
	{
		$tags = $this->model_content->get_all_tags()->result()[0]->tags;
		return array_unique(explode(';', $tags));
	}

	// Display all contents
	public function index()
	{
		if ($this->functions->get_loged()):

			$data['user_data'] = $this->functions->get_user_data();

			$data['page']	 = 'home';
			$data['title']	 = 'Tous les articles';

			$data['rubrics'] 	 = $this->functions->get_all_rubrics();
			$data['nb_comments'] = $this->functions->get_comments();
			$data['tags']	 	 = $this->_all_tags();
			$data['users']	 	 = $this->model_user->get_users();
			$data['query']	 	 = $this->functions->get_all_content();

			$this->load->view(URL_LAYOUT, $data);

		endif;
	}

	// Add or edit a content
	public function edit($c_id = '')
	{
		if ($this->functions->get_loged()):

			$this->load->library('form_validation');
			$this->load->helper('file');

			$data['user_data']	 = $this->functions->get_user_data();
			$data['rubrics'] 	 = $this->functions->get_all_rubrics();
			$data['nb_comments'] = $this->functions->get_comments();
			$data['users']		 = $this->model_user->get_users();
			$data['images'] 	 = get_dir_file_info('./assets/img/thumb', $top_level_only = FALSE);

			$this->form_validation->set_rules('c_title', 'Titre', 'trim|required|callback_check_content');
			$this->form_validation->set_rules('c_content', 'Contenu', 'trim|required');
			$this->form_validation->set_rules('c_image', 'Image d\'illustration', 'trim');
			$this->form_validation->set_rules('c_status', 'Etat', 'required');
			$this->form_validation->set_rules('rubric', 'Rubrique', 'required');
			$this->form_validation->set_rules('u_id', 'u_id', 'required');

			$c_title   = $this->input->post('c_title');
			$c_content = $this->input->post('c_content');
			$c_image   = $this->input->post('c_image');
			$c_status  = $this->input->post('c_status');
			$r_id	   = $this->input->post('rubric');
			$c_tags	   = $this->input->post('c_tags');
			$c_pdate   = $this->input->post('c_pdate');
			$u_id	   = $this->input->post('u_id');

			// Add a content
			if ($this->uri->total_segments() == 3):
				$data['page']  = 'add_content';
				$data['title'] = 'Ajouter un article';

				// URL generated
				$c_url_rw = url_title(convert_accented_characters($c_title), '-', TRUE);

				if ($this->form_validation->run() !== FALSE):
					$this->model_content->create_content($r_id, $u_id, $c_title, $c_content, $c_image, $c_tags, $p_date, $c_status, $c_url_rw);
					$this->session->set_flashdata('success', 'Article "' . $c_title . '" ajouté.');
					redirect(URL_HOME_CONTENT);
				endif;

			else:
				$content = $this->model_content->get_content($c_id, '')->row();

				// Content exist
				if (!empty($content)):

					$c_udate		   = (isset($_POST['c_udate']))?true:false;
					$data['page']	   = 'edit_content';
					$data['c_id']	   = $content->c_id;
					$data['r_id']	   = $content->r_id;
					$data['u_id']	   = $content->u_id;
					$data['c_title']   = $content->c_title;
					$data['c_content'] = $content->c_content;
					$data['c_image']   = $content->c_image;
					$data['c_tags']	   = $content->c_tags;
					$data['c_status']  = $content->c_status;
					$data['c_url_rw']  = $content->c_url_rw;
					$data['c_cdate']   = $content->c_cdate;
					$data['c_pdate']   = $content->c_pdate;
					$data['c_udate']   = $content->c_udate;
					$data['r_url_rw']  = $content->r_url_rw;

					$data['title']	   = 'Modifier l\'article ' . $data['c_title'];

					// Comments
					$data['comments'] = $this->model_comment->get_comment($data['c_id']);

					$this->form_validation->set_rules('c_udate', 'Date de mise à jour');
					$this->form_validation->set_rules('c_url_rw', 'Url', 'trim|required|callback_check_url_rw');
					$c_url_rw = $this->input->post('c_url_rw');
					$c_url_rw = url_title(convert_accented_characters($c_url_rw), '-', TRUE);

					if ($this->form_validation->run() !== FALSE):
						$this->model_content->update_content($r_id, $u_id, $c_title, $c_content, $c_image, $c_tags, $c_status, $c_url_rw, $c_udate, $c_id);
						$this->session->set_flashdata('success', 'Article "' . $c_title . '" modifié.');
						redirect(URL_HOME_CONTENT);
					endif;

				// Content unknown
				else:
					$this->session->set_flashdata('alert', 'Cette article (#' . $c_id . ') n\'existe plus ou n\'a jamais existé');
					redirect(URL_HOME_CONTENT);
				endif;

			endif;

			$this->load->view(URL_LAYOUT, $data);

		endif;
	}

	// Check if the URL content already exists
	public function check_url_rw($c_url_rw)
	{
		$c_id = $this->uri->segment(4);

		if ($this->model_content->check_url_rw($c_id, $c_url_rw)->num_rows() == 1):
			$this->form_validation->set_message('check_url_rw', 'Impossible d\'attribuer l\'url "' . $c_url_rw . '" car cette dernière existe déjà.');
			return FALSE;
		else:
			return TRUE;
		endif;
	}

	// Delete a content
	public function delete($id = '')
	{
		if ($this->functions->get_loged()):

			// Content exists
			if ($this->model_content->get_content($id)->num_rows() == 1):
				$this->model_content->delete_content($id);
				$this->session->set_flashdata('success', 'L\'article a bien été supprimé');
				redirect(URL_HOME_CONTENT);

			// Content unknown
			else:
				$this->session->set_flashdata('alert', 'Cette article (#' . $id . ') n\'existe plus ou n\'a jamais existé');
				redirect(base_url(URL_HOME_CONTENT));
			endif;

		endif;
	}

	// Get contents by rubric
	public function rubric()
	{
		if ($this->functions->get_loged()):
			$data['user_data']   = $this->functions->get_user_data();
			$data['rubrics']	 = $this->functions->get_all_rubrics();
			$data['nb_comments'] = $this->functions->get_comments();
			$data['users']		 = $this->model_user->get_users();

			$rubric = $this->input->get('q');
			
			if (!empty($rubric)):
				$query = $this->model_content->get_contents_by_rubric($rubric);

				if ($query->num_rows > 0):
					$data['page']  = 'c_rubric';
					$data['title'] = 'Rubrique <em>' . $rubric . '</em>';
					$data['tags']  = $this->_all_tags();
					$data['query'] = $query;
					$this->load->view(URL_LAYOUT, $data);
				else:
					$this->session->set_flashdata('warning', 'Pas d\'articles avec cette rubrique "' . $rubric . '"');
					redirect(base_url(URL_HOME_CONTENT));
				endif;

			else:
				redirect(base_url(URL_HOME_CONTENT));
			endif;

		endif;
	}

	// Display all contents from one user
	public function author($id = '')
	{
		if ($this->functions->get_loged() and !empty($id)):

			$user = $this->model_user->get_user($id, '');
			
			if ($user->num_rows() == 1): 
				$data['user_data']	 = $this->functions->get_user_data();
				$data['nb_comments'] = $this->functions->get_comments();
				$data['rubrics']	 = $this->functions->get_all_rubrics();
				$data['tags']		 = $this->_all_tags();

				$user = $user->row()->u_login;

				$data['page'] = 'author';

				if ($data['user_data']['id_user'] == $id):
					$data['title'] = "Tous mes articles";
				else:
					$data['title'] = "Tous les articles de " . $user;
				endif;

				$data['query'] = $this->model_content->get_content_by_user($id, '', '');

				$this->load->view(URL_LAYOUT, $data);

			else:
				$this->session->set_flashdata('alert', 'Cette auteur (#' . $id . ') n\'existe pour ou n\'a jamais existé');
				redirect(base_url(URL_HOME_CONTENT));
			endif;

		endif;
	}

	// Get contents by tag
	public function tag()
	{
		if ($this->functions->get_loged()):
			$data['user_data']   = $this->functions->get_user_data();
			$data['rubrics'] 	 = $this->functions->get_all_rubrics();
			$data['nb_comments'] = $this->functions->get_comments();
			$data['users']		 = $this->model_user->get_users();

			$data['tag_tag'] = $tag	   = $this->input->get('q');
			$data['option']  = $option = $this->input->get('option');
			$query			 = $this->model_content->get_tag_admin($tag, $option, '');

			if (!empty($tag)):

				if ($query->num_rows > 0):
					$data['page'] = 'tags';
					$count		  = count($tag);
					if (is_array($tag)):
						$tag = implode(' ', $tag);
					endif;
					$data['title'] = 'Tag <em>' . $tag . '</em>';
					$data['tags']  = $this->_all_tags();
					$data['query'] = $query;
					$this->load->view(URL_LAYOUT, $data);
				else:
					if (is_array($tag)):
						$count_tag = count($tag);
						$tag	   = implode(' ', $tag);
						if ($count_tag > 1):
							$this->session->set_flashdata('warning', 'Pas d\'articles avec les ' . $count_tag . ' tags suivants : "' . $tag . '"');
						else:
							$this->session->set_flashdata('warning', 'Pas d\'articles avec le tag "' . $tag . '"');
						endif;
					else:
						$this->session->set_flashdata('alert', 'Pas d\'articles avec ce tag "' . $tag . '"');
					endif;
					redirect(base_url(URL_HOME_CONTENT));
				endif;

			else:
				redirect(base_url(URL_HOME_CONTENT));
			endif;

		endif;
	}

	// Check if a content already exists
	public function check_content($c_title)
	{
		$c_id = $this->uri->segment(4);

		if ($this->model_content->check_title($c_id, $c_title)->num_rows() == 1):
			$this->form_validation->set_message('check_content', 'Impossible de rajouter l\'article "' . $c_title . '" car ce dernier existe déjà.');
			return FALSE;
		else:
			return TRUE;
		endif;
	}

	// Multi options for : delete, export
	public function multi_options($id = '')
	{
		if ($this->functions->get_loged()):
			$id = $this->input->post('content_choice');

			if (!empty($id)):

				// Multi delete
				if ($this->input->post('multi_delete') == 'multi_delete'):
					$this->model_content->delete_content($id);

					if (count($id) > 1):
						$this->session->set_flashdata('success', 'Les articles ont bien été supprimé');
					else:
						$this->session->set_flashdata('success', 'L\'article a bien été supprimé');
					endif;

				// Multi export
				elseif ($this->input->post('multi_export') == 'multi_export'):
					$this->load->dbutil();
					$this->load->helper('download');
					$query	   = $this->model_content->export_content($id);
					$delimiter = ",";
					$newline   = "\r\n";
					$csv	   = $this->dbutil->csv_from_result($query, $delimiter, $newline);
					$name	   = 'test.csv';
					force_download($name, utf8_decode($csv));
				endif;

			else:
				$this->session->set_flashdata('warning', 'Aucun contenu sélectionné');
			endif;

			redirect(URL_HOME_CONTENT);

		endif;
	}

}

/* End of file content.php */
/* Location: ./application/controllers/admin/content.php */