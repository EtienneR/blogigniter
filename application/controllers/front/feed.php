<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feed extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('text');
		$this->load->model(array('model_feed', 'model_params'));
		$this->load->library('front');
		$this->load->helper('xml');
	}

	public function index()
	{
		$params = $this->front->about();

		$limit = $this->model_params->get_params()->row()->p_nb_listing_f;

		if (!empty($limit)):
			$limit = $limit;
		else:
			$limit = 10;
		endif;

		$data['site_name']		  = $params->p_title;
		$data['site_link']		  = base_url();
		$data['site_description'] = 'Les flux RSS de mes articles';
		$data['encoding']		  = 'utf-8';
		$data['feed_url']		  = base_url() . '/feed';
		$data['page_language']	  = 'fr-fr';
		$data['posts']			  = $this->model_feed->getRecentPosts($limit);

		header("Content-Type: application/rss+xml");

		$this->load->view('front/view_feed', $data);
	}

}


/* End of file feed.php */
/* Location: ./application/controllers/front/feed.php */