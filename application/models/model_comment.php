<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_comment extends CI_Model {

	function get_comments()
	{
		$this->db->select('com_id, content.c_id, content.c_title, com_nickname, com_content, com_status')
				 ->from('comment')
				 ->join('content', 'content.c_id = comment.c_id');

		$query = $this->db->get();
		return $query;
	}

	function get_comment($c_id)
	{
		$this->db->select('com_id, com_nickname, com_content, com_date')
				 ->from('comment')
				 ->where('c_id', $c_id)
				 ->where('com_status <>	', 2)
				 ->order_by('com_date', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	function check_comment($com_id)
	{
		$this->db->select('com_id')
				 ->from('comment')
				 ->where('com_id', $com_id);

		$query = $this->db->get();
		return $query;
	}

	function get_unmoderate_comments()
	{
		$this->db->select('com_id')
				 ->from('comment')
				 ->where('com_status', 0);

		$query = $this->db->get();
		return $query;
	}
	
	function create_comment($c_id, $com_nickname, $com_content)
	{
		$data = array(
			'c_id'		   => $c_id,
			'com_nickname' => $com_nickname,
			'com_content'  => $com_content,
			'com_date'	   => unix_to_human(now(), TRUE, 'eu')
		);

		$this->db->insert('comment', $data);
	}

	function update_comment($com_id, $com_status)
	{
		$data = array(
			'com_status' => $com_status
		);

		$this->db->where('com_id', $com_id);
		$this->db->update('comment', $data);
	}

	function delete_comment($com_id)
	{
		$this->db->where('com_id', $com_id)
				 ->delete('comment'); 
	}

}


/* End of file model_comment.php */
/* Location: ./application/models/admin/model_comment.php */