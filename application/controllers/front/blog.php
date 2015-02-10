<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model(array('model_comment', 'model_content', 'model_params', 'model_rubric', 'model_search', 'model_user'));
		$this->load->library(array('pagination', 'form_validation', 'front', 'parsedown'));
		$this->load->helper(array('functions', 'text', 'captcha'));
		define('URL_LAYOUT', 'front/view_layout');
		$this->all_content = $this->front->get_all_content();
		$this->all_rubrics = $this->front->get_all_rubrics();
		$this->all_authors = $this->front->get_all_authors();
		$this->all_tags	   = $this->front->get_all_tags();
		if (isset($_GET["profiler"])):
			$this->output->enable_profiler(TRUE);
		endif;
	}

	public function index($page_number = 0)
	{
		$data['page']			   = 'home';
		$data['breadcrumb']		   = 'Home';
		$data['all_content']	   = $this->all_content;
		$data['query_all_rubrics'] = $this->all_rubrics;
		$data['all_authors']	   = $this->all_authors;
		$data['all_tags']		   = $this->all_tags;

		$params	= $this->front->about();

		if (!empty($params)):
			$data['title']	   = $data['meta_title'] = $params->p_title;
			$data['p_title']   = $params->p_title;
			$data['meta_desc'] = $params->p_m_description;
			$data['about']	   = $params->p_about;
		else:
			$data['title'] = $data['meta_title'] = $data['p_title'] = $data['meta_desc'] = $data['about'] = '';
		endif;

		$breadcrumb = '<a href="' . base_url() . '">' . $data['breadcrumb'] . '</a>';

		if ($this->uri->total_segments() == 2 && $page_number <= 1):
			redirect(base_url(), 302);
		elseif ($page_number == 0):
			$data['breadcrumb'] = $data['breadcrumb'];
		else:
			$data['page_number'] = $page_number;
			$data['meta_title'] .= ' - page ' . $page_number;
			$data['breadcrumb']  = $breadcrumb . ' - page ' . $page_number;
		endif;

		$config = pagination_custom($params->p_nb_listing);

		$total_rows = $data['all_content']->num_rows();

		// Config for pagination : base_url, first_url, total_rows, num_link, uri_segment
		$pagination = pagination_links(base_url('page'), 
									   base_url(), 
									   $total_rows,
									   round(($total_rows / $config['per_page']) + 1),
									   2
					  );

		$this->pagination->initialize(array_merge($config, $pagination));

		// Check number page for the pagination
		if ($page_number > $pagination['num_links']):
			redirect(show_404());
		else:
			$data['query']		= $this->model_content->get_contents_listing('', $page_number, $config['per_page']);
			$data['pagination']	= $this->pagination->create_links();

			// Nb comments
			foreach ($data['query']->result() as $row):
				$nb_comments[$row->c_id] = $this->front->get_comments($row->c_id)->num_rows();
			endforeach;
			$data['nb_comments'] = $nb_comments;

			//$data['meta_pagination'] = $this->front->get_pagination_seo($pagination['base_url'], $pagination['first_url'], $page_number, $pagination['total_rows'], $config['per_page'], $type='POST');
		endif;

		$this->load->view(URL_LAYOUT, $data);
	}

	public function view($slug_rubric = '', $slug_content = '', $page_number = 0)
	{
		$data['query_all_rubrics'] = $this->all_rubrics;
		$data['all_authors']	   = $this->all_authors;
		$data['all_tags']		   = $this->all_tags;

		$params = $this->front->about();

		if (!empty($params)):
			$data['p_title'] = $params->p_title;
			$data['about'] 	 = $params->p_about;
			$data['twitter'] = $params->p_twitter;
			$data['google']  = $params->p_google;
		else:
			$data['p_title'] = $data['about'] = $data['twitter'] = $data['google'] = '';
		endif;

		// Rubric case
		if ( ($this->uri->total_segments() == 1) or ($this->uri->total_segments() == 3) ):

			$data['all_content'] = $this->front->get_all_content();

			$config = pagination_custom($params->p_nb_listing);

			$total_rows = $this->model_content->get_contents_rubric_listing($slug_rubric, '', '')->num_rows();
			// Config for pagination : base_url, first_url, total_rows, num_link, uri_segment
			$pagination = pagination_links(base_url($slug_rubric . '/page'), 
										   base_url($this->uri->segment(1)),
										   $total_rows,
										   round(($total_rows / $config['per_page']) + 1),
										   3
						  );

			$this->pagination->initialize(array_merge($config, $pagination));

			if ($page_number > $pagination['num_links']):
				redirect(show_404());
			else:
				$data['query'] = $this->model_content->get_contents_rubric_listing($slug_rubric, $page_number, $config['per_page']);
				if ($data['query']->num_rows == 0):
					redirect(show_404());
				endif;
				$data['pagination'] = $this->pagination->create_links();
			endif;

			// Nb comments
			foreach ($data['all_content']->result() as $row):
				$nb_comments[$row->c_id] = $this->front->get_comments($row->c_id)->num_rows();
			endforeach;

			$data['nb_comments'] = $nb_comments;

			$row = $data['query']->row(); 

			$data['page']  = 'rubric';
			$data['title'] = $row->r_title;

			if (!empty($params)):
				$data['meta_title'] = $row->r_title . ' - ' . $params->p_title;
			else:
				$data['meta_title'] = $row->r_title;
			endif;

			if ($this->uri->total_segments() == 3 && $page_number <= 1):
				redirect(base_url($slug_rubric), 302);
			elseif ($page_number == 0):
				$data['breadcrumb'] = $data['title'];
			else:
				$data['page_number'] = $page_number;
				$data['meta_title'] .= ' - page ' . $page_number;
				$data['breadcrumb']  = '<a href="' . base_url($slug_rubric) . '">' . $data['title'] . '</a> - page ' . $page_number;
			endif;
			$data['meta_desc'] = $row->r_description;

			//$data['meta_pagination'] = $this->front->get_pagination_seo($pagination['base_url'], $pagination['first_url'], $page_number, $total_rows, $config['per_page'], $type='POST');

		// Article case
		elseif ($this->uri->total_segments() <= 2):
			$query_article = $this->model_content->get_content_by_slug($slug_rubric, $slug_content);

			if ($query_article->num_rows() == 1):
				$data['page']		= 'content';
				$row 				= $query_article->row();
				$row->c_content 	= Parsedown::instance()->parse($row->c_content);
				$c_id				= $row->c_id;
				$data['title']		= $data['c_title'] = $row->c_title;
				$data['c_content']	= $row->c_content;
				$data['c_image']	= $row->c_image;
				$data['c_pdate']	= $row->c_pdate;
				$data['c_date']		= date_fr(date("d", strtotime($row->c_pdate)),
											   date("m", strtotime($row->c_pdate)),
											   date("Y", strtotime($row->c_pdate))
											  );
				$data['c_udate']    = $row->c_udate;
				$data['udate']		= date_complete_fr(date("d", strtotime($row->c_udate)),
														date("m", strtotime($row->c_udate)),
														date("Y", strtotime($row->c_udate)),
														date("h", strtotime($row->c_udate)),
														date("i", strtotime($row->c_udate))
													   );
				$data['c_url_rw']	= $row->c_url_rw;
				$data['r_title']	= $row->r_title;
				$data['r_url_rw']	= $row->r_url_rw;
				$data['u_id']		= $row->u_id;
				$data['u_login']	= $row->u_login;
				$data['u_biography']= $row->u_biography;
				$data['u_twitter']  = $row->u_twitter;
				$data['u_google']   = $row->u_google;

				$data['nb_comments'] = $this->front->get_comments($row->c_id)->num_rows();

				if (!empty($params)):
					$data['meta_title'] = $row->c_title . ' - ' . $params->p_title;
				else:
					$data['meta_title'] = $row->c_title;
				endif;

				$data['meta_desc']  = character_limiter(strip_tags($row->c_content), 254);
				$data['breadcrumb'] = $row->c_title;

				if (isset($row->c_tags)):
					$data['tags'] = explode(';', $row->c_tags);
				endif;

				$data['query_same_user']   = $this->model_content->get_content_by_user($data['u_id'], 5, $c_id);
				$data['query_same_rubric'] = $this->model_content->get_contents_same_rubric($slug_rubric, $slug_content);
				$c_tags					   = array_values(array_filter(explode(';', $row->c_tags)));
				$data['query_same_tag']	   = $this->model_content->get_contents_same_tag($slug_rubric, $slug_content, $c_tags);
				$data['all_content']	   = $this->model_content->get_contents_others($slug_content);
				$data['comments']		   = $this->model_comment->get_comment($c_id);

				$this->form_validation->set_rules('com_nickname', 'Nom', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('com_content', 'Contenu', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('captcha', 'Captcha', 'callback_check_captcha');

				$com_nickname = $this->input->post('com_nickname');
				$com_content  = $this->input->post('com_content');
				$captcha	  = $this->input->post('captcha');

				$this->form_validation->set_message('com_nickname', 'Le pseudo doit faire 2 caractères mininum');
				$this->form_validation->set_message('com_content', 'Le pseudo doit faire 2 caractères mininum');

				$this->load->library('session');

				if ($this->form_validation->run() !== FALSE):
					$this->model_comment->create_comment($c_id, $com_nickname, $com_content);
					$this->session->set_flashdata('success', 'Commentaire ajouté.');
					redirect(current_url());
				else:
					// Génération du captcha
					$word 	      = substr(sha1(rand()),-5);
					$path_captcha = 'assets/captcha/';
					$the_captcha  = array(
						'word'		 => $word,
						'img_path'	 => $path_captcha,
						'img_url'	 => site_url() . $path_captcha,
						'img_width'	 => '150',
						'img_height' => 30,
						'expiration' => 60
						);
					$this->session->set_userdata('captcha', $word);
					$this->session->set_userdata('image', $the_captcha['img_url']);
					$data['captcha']	   = create_captcha($the_captcha);
					$data['captcha_image'] = $data['captcha']['image'];
					//$this->session->unset_userdata('captcha');
				endif;

			else:
				redirect(show_404());
			endif;

		else:
			redirect(show_404());
		endif;

		$this->load->view(URL_LAYOUT, $data);
	}

	public function check_captcha($word)
	{
		if ($this->input->post('captcha') != $this->session->userdata['captcha']):
			$this->form_validation->set_message('check_captcha', 'Captcha invalide, envoi du commentaire impossible');
			return FALSE;
		else:
			return TRUE;
		endif;
	}

	public function author($u_login = '', $page_number = 0)
	{
		$get_user = $this->model_user->get_user('', $u_login);

		if ($get_user->num_rows() == 1):
			$data['title']			   = 'A propos de ' . $u_login . ' <br /><small>' . $get_user->row()->u_biography . ' </small>';
			if (!empty($get_user->row()->u_twitter)):
				$data['title']		  .= '<small><br /><a href="http://twitter.com/' . $get_user->row()->u_twitter . '" rel="nofollow">' . $get_user->row()->u_twitter .'</a></small>';
			endif;
			$data['meta_title']		   = $data['meta_desc'] = 'Tous les articles de ' . $u_login;
			$data['page']			   = 'author';
			$data['u_login']		   = $u_login;
			$data['all_content']	   = $this->all_content;
			$data['query_all_rubrics'] = $this->all_rubrics;
			$data['all_authors']	   = $this->all_authors;
			$data['all_tags']		   = $this->all_tags;

			$params = $this->front->about();
			if (!empty($params)):
				$data['p_title'] = $params->p_title;
				$data['about'] 	 = $params->p_about;
			else:
				$data['p_title'] = $data['about'] = '';
			endif;

			$config = pagination_custom($params->p_nb_listing);

			$config['base_url']    = base_url('author/' . $u_login .'/page');
			$config['first_url']   = base_url('author/' . $u_login);
			$config['total_rows']  = $this->model_content->get_contents_listing($u_login, '', '')->num_rows();
			$config['num_links']   = round(($config['total_rows'] / $config['per_page']) + 1);
			$config['uri_segment'] = 4;

			$this->pagination->initialize($config);

			$page_number = $this->uri->segment(4);
			if ($page_number > $config['num_links']):
				redirect(show_404());
			else:
				$data['query'] = $this->model_content->get_contents_listing($u_login, $page_number, $config['per_page']);
				// Génération de la pagination
				$data['pagination'] = $this->pagination->create_links();
				//$data['meta_pagination'] = $this->front->get_pagination_seo($config['base_url'], $config['first_url'], $page_number, $config['total_rows'], $config['per_page'], $type='POST');
				if ($data['query']->num_rows() > 0):
					// Nb comments
					foreach ($data['query']->result() as $row):
						$nb_comments[$row->c_id] = $this->front->get_comments($row->c_id)->num_rows();
					endforeach;
					$data['nb_comments'] = $nb_comments;
				endif;
			endif;

			$data['breadcrumb'] = 'Auteur : ';

			if ($page_number == 0):
				$data['breadcrumb'] .= $u_login;
			else:
				$data['breadcrumb'] .= '<a href="'. base_url('auteur/' . $u_login) .'">' . $u_login . '</a> - page ' . $page_number;
			endif;

			$this->load->view(URL_LAYOUT, $data);

		else:
			redirect(show_404());
		endif;
	}

	public function tags($page_number = 0)
	{
		$data['page'] 			   = 'rubric';
		$data['query_all_rubrics'] = $this->all_rubrics;
		$data['all_content']	   = $this->all_content;
		$data['all_authors']	   = $this->all_authors;
		$data['all_tags']		   = $this->all_tags;

		$params	= $this->front->about();

		if (!empty($params)):
			$data['p_title'] = $params->p_title;
			$data['about']	 = $params->p_about;
		else:
			$data['p_title'] = $data['about'] = '';
		endif;

		if ($this->input->get('q')):

			$data['tag'] = $this->input->get('q');
			
			$data['meta_desc']  = 'Tag ' . $data['tag'];
			$data['title'] 		= $data['meta_title'] = 'Tag ' . $data['tag'];
			$data['breadcrumb'] = 'Tag ' . $data['tag'];

			$query = $this->model_content->get_content_by_tag_name($data['tag'], '', '');

			if ($query->num_rows() > 0):
				$config						 = pagination_custom($params->p_nb_listing);
				$config['base_url']			 = base_url('t?q=' . $data['tag']);
				$config['first_url']		 = base_url('t?q=' . $data['tag']);
				$config['total_rows']		 = $query->num_rows();
				$config['num_links']		 = round(($config['total_rows'] / $config['per_page']));
				$config['uri_segment']		 = 3;
				$config['page_query_string'] = TRUE;

				$this->pagination->initialize($config);

				$data['breadcrumb'] = 'Tag : ';

				if ($page_number == 0):
					$data['breadcrumb'] .= $data['tag'];
					$data['meta_desc']	 = 'Tags' . $data['tag'];
				else:
					$data['breadcrumb'] .= '<a href="' . $config['base_url'] . '">' . $data['tag'] . '</a> - page ' . $page_number;
					$data['meta_desc']	 = 'Tag ' . $data['tag'] . ' - page ' . $page_number;
				endif;


				if (isset($_GET['page']) && $_GET['page'] > $config['num_links']):
					redirect(show_404());
				elseif (isset($_GET['page']) && $_GET['page'] <= 1):
					redirect(base_url('/t?q=' . $data['tag']));
				else:
					// Nb comments
					foreach ($query->result() as $row) {
						$nb_comments[$row->c_id] = $this->front->get_comments($row->c_id)->num_rows();
					}
					$data['nb_comments'] = $nb_comments;

					$data['query'] = $this->model_content->get_content_by_tag_name($data['tag'], $page_number, $config['per_page']);
					// Génération de la pagination
					$data['pagination'] = $this->pagination->create_links();
					//$data['meta_pagination'] = $this->front->get_pagination_seo($config['base_url'], $config['first_url'], $page_number, $config['total_rows'], $config['per_page'], $type='get');
				endif;

			else:
				$data['title']		= 'Erreur de tag';
				$data['breadcrumb'] = 'tag inéxistant';
				$data['error']		= $data['meta_title'] = 'Aucun article correspondant à ce tag.';
			endif;

		endif;

		$this->load->view(URL_LAYOUT, $data);
	}

	public function search($page_number = 0)
	{
		$data['meta_desc']		   = 'Page de recherche';
		$data['page']			   = 'search';
		$data['breadcrumb']		   = '';
		$data['query_all_rubrics'] = $this->all_rubrics;
		$data['all_content']	   = $this->all_content;
		$data['all_authors']	   = $this->all_authors;
		$data['all_tags']		   = $this->all_tags;

		$params = $this->front->about();
		if (!empty($params)):
			$data['p_title'] = $params->p_title;
			$data['about']	 = $params->p_about;
		else:
			$data['p_title'] = $data['about'] = '';
		endif;

		$input = $this->input->get('q');

		if (!empty($input)):

			$input = strip_tags($input);

			if (strlen(trim($input)) > 2):
				$data['research'] = $research = $input;
				$explode = explode(' ', $research);

				if (count($explode) > 1):
					foreach ($explode as $words):
						if (strlen($words) > 2):
							$data['words'][] = $words;
						endif;
					endforeach;
				endif;

				$config 					 = pagination_custom($params->p_nb_listing);
				$config['base_url']			 = base_url('s?q=' . $research);
				$config['first_url']		 = base_url('s?q=' . $research);
				$config['total_rows']		 = $this->model_search->get_research($research, '', '')->num_rows();
				$config['num_links']		 = round(($config['total_rows'] / $config['per_page']) + 1);
				$config['uri_segment']		 = 3;
				$config['page_query_string'] = TRUE;

				$this->pagination->initialize($config);

				$data['breadcrumb']  = 'Recherche : ';

				if ($page_number == 0):
					$data['breadcrumb'] .= $data['research'];
					$data['meta_desc']	 = 'Résultats pour ' . $data['research'];
				else:
					$data['breadcrumb'] .= '<a href="' . $config['base_url'] . '">' . $data['research'] . '</a> - page ' . $page_number;
					$data['meta_desc']	 = 'Résultats pour ' . $data['research'] . ' - page ' . $page_number;
				endif;

				if (isset($_GET['page']) && $_GET['page'] > $config['num_links']):
					redirect(show_404());
				elseif (isset($_GET['page']) && $_GET['page'] <= 1):
					redirect(base_url('/s?q=' . $input));
				else:
					$data['query']			 = $this->model_search->get_research($research, $page_number, $config['per_page']);
					$data['pagination']		 = $this->pagination->create_links();
					//$data['meta_pagination'] = $this->front->get_pagination_seo($config['base_url'], $config['first_url'], $page_number, $config['total_rows'], $config['per_page'], $type='get');

					// Nb comments
					if ($data['query']->num_rows > 0):
						foreach ($data['query']->result() as $row):
							$nb_comments[$row->c_id] = $this->front->get_comments($row->c_id)->num_rows();
						endforeach;
						$data['nb_comments'] = $nb_comments;
					endif;

				endif;

				$this->model_search->insert_search($research, $config['total_rows']);

				$data['title'] = $data['meta_title'] = 'Recherche ' . $research . ' (' . $config['total_rows'] . ')' ;

			else:
				$data['title']		= 'Erreur dans votre recherche';
				$data['breadcrumb'] = 'Recherche : ' . strtolower($data['title']);
				$data['research']	= '';
				$data['error']		= $data['meta_title'] = 'Votre requête ne peut aboutir car le mot rentré doit comporté au minimum 2 mots.';
			endif;

		else:
			$data['research'] 	= '';
			$data['title'] 		= 'Recherche';
			$data['breadcrumb'] = 'Recherche : ' . strtolower($data['title']);
		endif;

		$this->load->view('front/view_layout', $data);
	}

}

/* End of file blog.php */
/* Location: ./application/controllers/blog.php */