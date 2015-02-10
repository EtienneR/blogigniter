<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_params extends CI_Model {

	function get_params()
	{
		$this->db->select('p_title, p_about, p_m_description, p_email, p_nb_listing, p_nb_listing_f, p_twitter, p_google')
				 ->from('params')
				 ->where('p_id', 0)
				 ->limit(1);

		$query = $this->db->get();
		return $query;
	}
	
	function get_params2()
	{
		$this->db->select('p_nb_listing')
				 ->from('params')
				 ->where('p_id', 0)
				 ->limit(1);

		$query = $this->db->get();
		return $query;
	}

	function insert_params($p_title, $p_m_description, $p_about, $p_email, $p_nb_listing, $p_nb_listing_f, $p_twitter, $p_google)
	{
		$data = array(
			'p_id'			  => 0,
			'p_title'		  => $p_title,
			'p_m_description' => $p_m_description,
			'p_about'		  => $p_about,
			'p_email'		  => $p_email,
			'p_nb_listing'	  => $p_nb_listing,
			'p_nb_listing_f'  => $p_nb_listing_f,
			'p_twitter'		  => $p_twitter,
			'p_google'		  => $p_google
		);

		$this->db->insert('params', $data);
	}

	function update_params($p_title, $p_m_description, $p_about, $p_email, $p_nb_listing, $p_nb_listing_f, $p_twitter, $p_google)
	{
		$data = array(
			'p_title'		  => $p_title,
			'p_m_description' => $p_m_description,
			'p_about'		  => $p_about,
			'p_email'		  => $p_email,
			'p_nb_listing'	  => $p_nb_listing,
			'p_nb_listing_f'  => $p_nb_listing_f,
			'p_twitter'		  => $p_twitter,
			'p_google'		  => $p_google
		);

		$this->db->where('p_id', 0);
		$this->db->update('params', $data);
	}

}


/* End of file model_admin.php */
/* Location: ./application/models/admin/model_admin.php */