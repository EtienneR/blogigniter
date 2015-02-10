<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_search extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('date');
	}

	function get_words()
	{
		$this->db->select('s_id, s_word, s_date, s_score')
				 ->from('search')
				 ->order_by('s_id', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	function distinct_words()
	{
		$this->db->distinct()
				 ->select('s_word')
				 ->from('search');

		$query = $this->db->get();
		return $query;
	}

	function get_research($research, $number_page, $per_page)
	{
		$where = "(c_title LIKE '%" . $research . "%'
				OR  c_content LIKE '%". $research . "%'
				OR  c_tags LIKE '%". $research . "%')
				AND c_status = 1
				AND c_cdate <= '" . unix_to_human(now(), TRUE, 'eu') . "'";

		$this->db->select('c_id, c_title, c_content, c_image, c_tags, c_cdate, c_url_rw, r_title, r_url_rw');
		$this->db->from('content');
		$this->db->join('rubric', 'rubric.r_id = content.r_id');
		$this->db->where($where);
		$this->db->order_by('c_cdate', 'DESC');
		if ($per_page and $number_page):
			$this->db->limit($per_page, ($number_page-1) * $per_page);
		elseif ($per_page):
			$this->db->limit($per_page);
		endif;

		$query = $this->db->get();
		return $query;
	}

	function insert_search($s_word, $s_score)
	{
		$data = array(
			's_word'  => $s_word,
			's_date'  => $this->date->get_date_format(),
			's_score' => $s_score
		);

		$this->db->insert('search', $data);

	}

}


/* End of file model_user.php */
/* Location: ./application/models/admin/model_user.php */