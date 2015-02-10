<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error404 extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		// Chargement des ressources pour ce controller
		$this->load->model(array('model_comment', 'model_content', 'model_params', 'model_rubric', 'model_search', 'model_user'));
		$this->load->library(array('pagination', 'form_validation', 'front', 'session'));
		$this->load->helper(array('functions', 'text', 'captcha'));
		define('URL_LAYOUT', 'front/view_layout');
		if (isset($_GET["profiler"])):
			$this->output->enable_profiler(TRUE);
		endif;
	}

	public function index()
	{
		echo '404';
/*		$params = $this->about();
		$data['about'] = $params->p_about;

		// Retourne tous les articles (sidebar)
		$data['all_content'] = $this->get_all_content();

		// Retourne toutes les rubriques (sidebar)
		$data['query_all_rubrics'] = $this->get_all_rubrics();

		// Retourne tous les auteurs (sidebar)
		$data['all_authors'] = $this->get_all_authors();

		$data['page']  = '404';
		$data['title'] = $data['meta_title'] = $data['meta_desc'] = 'Erreur 404 sur la page demandée';
		
		// Instancie une vraie erreur 404
		http_response_code(404);

		// Chargement des données dans la vue
		$this->load->view(URL_LAYOUT, $data);*/
	}

}


/* End of file blog.php */
/* Location: ./application/controllers/blog.php */