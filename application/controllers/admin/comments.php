<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comments extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('model_comment', 'model_content'));
		$this->load->library(array('admin/functions', 'form_validation', 'session'));
		$this->load->helper(array('form', 'functions'));
		define('URL_LAYOUT'   , 'admin/view_dashboard');
		define('URL_HOME_COM' , 'admin/comments');
		session_start();
		if (isset($_GET["profiler"])):
			$this->output->enable_profiler(TRUE);
		endif;
	}

	// Display all comments
	public function index()
	{
		if ($this->functions->get_loged()):
			$data['user_data'] = $this->functions->get_user_data();

			$data['nb_comments'] = $this->functions->get_comments();

			$data['page']  = 'comment';
			$data['title'] = 'Commentaires';

			$data['query'] = $this->model_comment->get_comments();

			$this->load->view(URL_LAYOUT, $data);

		endif;
	}

	// Moderate a comment
	function moderate($com_id)
	{
		if ($this->functions->get_loged()):

			// If comment exists
			if ($this->model_comment->check_comment($com_id)->num_rows() == 1):
				$this->model_comment->update_comment($com_id, $com_status = 1);
				$this->session->set_flashdata('success', 'Commentaire n°' . $com_id . ' modéré.');
				redirect(URL_HOME_COM);

			else:
				$this->session->set_flashdata('alert', 'Ce commentaire n\'existe pour ou n\'a jamais existé');
				redirect(URL_HOME_COM);
			endif;

		endif;
	}

	// Desactivate a comment
	function desactivate($com_id)
	{
		if ($this->functions->get_loged()):

			// If comment exists
			if ($this->model_comment->check_comment($com_id)->num_rows() == 1):
				$this->model_comment->update_comment($com_id, $com_status = 2);
				$this->session->set_flashdata('success', 'Commentaire n°' . $com_id . ' désactivé (n\'apparaitra plus sur le site).');
				redirect(URL_HOME_COM);

			else:
				$this->session->set_flashdata('alert', 'Ce commentaire n\'existe pour ou n\'a jamais existé');
				redirect(URL_HOME_COM);
			endif;

		endif;
	}

	// Delete a comment
	function delete($com_id)
	{
		if ($this->functions->get_loged()):
			
			// If comment exists
			if ($this->model_comment->check_comment($com_id)->num_rows() == 1):
				$this->model_comment->delete_comment($com_id);
				$this->session->set_flashdata('success', 'Commentaire n°' . $com_id . ' supprimé.');
				redirect(URL_HOME_COM);

			else:
				$this->session->set_flashdata('alert', 'Ce commentaire n\'existe pour ou n\'a jamais existé');
				redirect(URL_HOME_COM);
			endif;

		endif;
	}

	// Comment(s) by content
	function content($c_id = '')
	{
		if ($this->functions->get_loged()):
			$tag = $this->input->get('q');

			if (!empty($tag)):
				$query = $this->model_content->get_contents_by_tag($tag);

				if ($query->num_rows > 0):
					$data['page']  = 'tags';
					$data['title'] = 'Tag <em>' . $tag . '</em>';
					$data['query'] = $query;
					$this->load->view(URL_LAYOUT, $data);
				else:
					$this->session->set_flashdata('alert', 'Pas de commentaires "' . $tag . '"');
					redirect(base_url(URL_HOME_CONTENT));
				endif;

			else:
				redirect(base_url(URL_HOME_CONTENT));
			endif;

		endif;
	}

}


/* End of file comments.php */
/* Location: ./application/controllers/admin/comments.php */