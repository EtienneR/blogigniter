<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_rubric extends CI_Model {

	function get_rubrics()
	{
		$this->db->select('r_id, r_title, r_description, r_url_rw')
				 ->from('rubric')
				 ->order_by('r_id', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	function get_rubric($r_id, $r_title)
	{
		$this->db->select('r_title, r_description, r_url_rw');
		$this->db->from('rubric');
		if (empty($r_title)):
			$this->db->where('r_id', $r_id);
		else:
			$this->db->where('r_title', $r_title);
		endif;
		$this->db->limit(1);

		$query = $this->db->get();
		return $query;
	}

	function check_title($r_id, $r_title)
	{
		$this->db->select('r_title')
				 ->from('rubric')
				 ->where('r_id <>', $r_id)
				 ->where('r_title', $r_title);

		$query = $this->db->get();
		return $query;
	}

	function check_url_rw($r_id, $r_url_rw)
	{
		$this->db->select('r_url_rw')
				 ->from('rubric')
				 ->where('r_id <>', $r_id)
				 ->where('r_url_rw', $r_url_rw);

		$query = $this->db->get();
		return $query;
	}

	function create_rubric($r_title, $r_description, $r_url_rw)
	{
		$data = array(
			'r_title'       => $r_title,
			'r_description' => $r_description,
			'r_url_rw'      => $r_url_rw
		);

		$this->db->insert('rubric', $data);
	}

	function update_rubric($r_title, $r_description, $r_url_rw, $r_id)
	{
		$data = array(
			'r_title'		=> $r_title,
			'r_description' => $r_description,
			'r_url_rw' 		=> $r_url_rw
		);

		$this->db->where('r_id', $r_id)
				 ->update('rubric', $data);
	}

	function delete_rubric($r_id)
	{
		$this->db->where('r_id', $r_id)
				 ->delete('rubric');
	}

	// Check if content exists and is not draft statement
	function get_rubrics_front()
	{
		$get_rubrics = $this->db->distinct()
								->select('rubric.r_id')
								->join('content', 'content.r_id = rubric.r_id')
								->from('rubric')
								->where('c_pdate <=', unix_to_human(now(), TRUE, 'eu') )
								->where('c_status', 1)
								->get();

		if ($get_rubrics->num_rows() > 0):

			foreach ($get_rubrics->result() as $row):
				$r_id[]  = ' OR r_id = '. $row->r_id;
				$r_id[0] = current($r_id);
			endforeach;

			$first	  = current($r_id);
			$explode  = explode(" OR", $first);
			$first_id = $explode[1];
			$params	  = implode('', $r_id );

			$query = $this->db->query('SELECT r_id, r_title, r_description, r_url_rw
										FROM rubric
										WHERE ' . $first_id . ' ' . $params . ' 
										ORDER BY r_title ASC');
			return $query;

		else:
			return false;
		endif;

	}

}


/* End of file model_rubric.php */
/* Location: ./application/models/admin/model_rubric.php */