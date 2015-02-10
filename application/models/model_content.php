<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_content extends CI_Model {

	function get_contents($last_content, $state)
	{
		$this->db->select('c_id, c_title, c_content, c_image, c_tags, c_status, c_cdate, c_udate, c_pdate, c_url_rw, content.r_id, r_title, r_url_rw, content.u_id, u_login');
		$this->db->from('content');
		$this->db->join('rubric', 'rubric.r_id = content.r_id');
		$this->db->join('user', 'content.u_id = user.u_id');
		if (!empty($last_content)):
		$this->db->where('c_pdate <=', unix_to_human(now(), TRUE, 'eu') );
		endif;	
		if (!empty($state)):
		$this->db->where('c_status', 1);
		endif;
		$this->db->order_by('c_id', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	function get_content($c_id, $c_title)
	{
		$this->db->select('c_id, content.r_id, content.u_id, c_title, c_content, c_image, c_tags, c_status, c_cdate, c_udate, c_pdate, c_url_rw, r_url_rw');
		$this->db->from('content');
		$this->db->join('rubric', 'content.r_id = rubric.r_id');
		$this->db->join('user', 'content.u_id = user.u_id');
		if (empty($c_title)):
		$this->db->where('c_id', $c_id);
		else:
		$this->db->where('c_title', $c_title);
		endif;
		$this->db->limit(1);

		$query = $this->db->get();
		return $query;
	}

	function check_title($c_id, $c_title)
	{
		$this->db->select('c_title')
				 ->from('content')
				 ->where('c_id <>', $c_id)
				 ->where('c_title', $c_title);

		$query = $this->db->get();
		return $query;
	}

	function check_url_rw($c_id, $c_url_rw)
	{
		$this->db->select('c_url_rw')
				 ->from('content')
				 ->where('c_id <>', $c_id)
				 ->where('c_url_rw', $c_url_rw);

		$query = $this->db->get();
		return $query;
	}

	function create_content($r_id, $u_id, $c_title, $c_content, $c_image, $c_tags, $c_pdate, $c_status, $c_url_rw)
	{
		$date = unix_to_human(now(), TRUE, 'eu');

		if (empty($c_pdate)):
			$c_pdate = $date;
		endif;

		$data = array(
			'r_id'		=> $r_id,
			'u_id'		=> $u_id,
			'c_title'	=> $c_title,
			'c_content'	=> $c_content,
			'c_image'	=> $c_image,
			'c_tags'	=> $c_tags,
			'c_status'	=> $c_status,
			'c_cdate'	=> $date,
			'c_udate'	=> $date,
			'c_pdate'	=> $c_pdate,
			'c_url_rw'	=> $c_url_rw
		);

		$this->db->insert('content', $data);
	}
	
	function update_content($r_id, $u_id, $c_title, $c_content, $c_image, $c_tags, $c_status, $c_url_rw, $c_udate, $c_id)
	{
		if ($c_udate === TRUE):
		$data = array(
			'r_id'		=> $r_id,
			'u_id'		=> $u_id,
			'c_title'	=> $c_title,
			'c_content'	=> $c_content,
			'c_image'	=> $c_image,
			'c_tags'	=> $c_tags,
			'c_status'	=> $c_status,
			'c_url_rw'	=> $c_url_rw,
			'c_udate'	=> unix_to_human(now(), TRUE, 'eu')
		);
		else:
			$data = array(
			'r_id'		=> $r_id,
			'u_id'		=> $u_id,
			'c_title'	=> $c_title,
			'c_content'	=> $c_content,
			'c_image'	=> $c_image,
			'c_tags'	=> $c_tags,
			'c_status'	=> $c_status,
			'c_url_rw'	=> $c_url_rw
		);
		endif;

		$this->db->where('c_id', $c_id)
				 ->update('content', $data);
	}

	function delete_content($c_id)
	{
		$c_id = array($c_id);
		$this->db->where_in('c_id', $c_id[0])
				 ->delete('content'); 
	}

	function export_content($c_id)
	{
		$this->db->select('*')
				 ->from('content')
				 ->where_in('c_id', $c_id);

		$query = $this->db->get();
		return $query;
	}


	// Get content for listing
	function get_contents_listing($u_login, $number_page, $per_page)
	{
		if (!empty($u_login)):
			$author = ', u_login';
		else:
			$author = '';
		endif;
		$this->db->select('c_id, c_title, c_content, c_image, c_tags, c_cdate, c_pdate, c_url_rw, r_title, r_url_rw ' . $author . '');
		$this->db->from('content');
		$this->db->join('rubric', 'rubric.r_id = content.r_id');
		if ($u_login):
			$this->db->join('user', 'user.u_id = content.u_id');
			$this->db->where('user.u_login', $u_login);
		endif;
		$this->db->where('c_status', 1);
		$this->db->where('c_pdate <', unix_to_human(now(), TRUE, 'eu') );
		$this->db->order_by('c_id', 'DESC');

		if ($number_page and $per_page):
			$this->db->limit($per_page, ($number_page-1) * $per_page);
		elseif ($per_page):
			$this->db->limit($per_page);
		endif;

		$query = $this->db->get();
		return $query;
	}

	// Get content for a content
	function get_content_by_slug($slug_rubric, $slug_content)
	{
		$this->db->select('c_id, c_title, c_content, c_image, c_tags, c_pdate, c_udate, c_url_rw, r_title, r_url_rw, user.u_id, u_login, u_biography, u_twitter, u_google')
				 ->from('content')
				 ->join('rubric', 'content.r_id = rubric.r_id')
				 ->join('user', 'content.u_id = user.u_id')
				 ->where('r_url_rw', $slug_rubric)
				 ->where('c_url_rw', $slug_content)
				 ->where('c_status', 1)
				 ->where('c_pdate <=', unix_to_human(now(), TRUE, 'eu'));

		$query = $this->db->get();
		return $query;
	}

	function get_contents_others($slug_content)
	{
		$this->db->select('c_title, c_url_rw, r_url_rw')
				 ->join('rubric', 'rubric.r_id = content.r_id')
				 ->from('content')
				 ->where('c_url_rw <>', $slug_content)
				 ->where('c_pdate <=', unix_to_human(now(), TRUE, 'eu') )
				 ->where('c_status', 1)
				 ->order_by('c_id', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	function get_contents_same_rubric($slug_rubric, $slug_content)
	{
		$this->db->select('c_title, c_url_rw, r_url_rw')
				 ->join('rubric', 'content.r_id = rubric.r_id')
				 ->from('content')
				 ->where('rubric.r_url_rw', $slug_rubric)
				 ->where('content.c_url_rw <>', $slug_content)
				 ->where('c_status', 1)
				 ->where('c_pdate <=', unix_to_human(now(), TRUE, 'eu') )
				 ->order_by('c_id', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	function get_contents_rubric_listing($slug_rubric, $number_page, $per_page)
	{
		$this->db->select('c_id, c_title, c_content, c_image, c_tags, c_cdate, c_url_rw, r_title, r_description, r_url_rw');
		$this->db->from('content');
		$this->db->join('rubric', 'rubric.r_id = content.r_id');
		$this->db->where('rubric.r_url_rw', $slug_rubric);
		$this->db->where('c_status', 1);
		$this->db->where('c_pdate <=', unix_to_human(now(), TRUE, 'eu') );
		$this->db->order_by('content.c_id', 'DESC');
		if ($number_page and $per_page):
			$this->db->limit($per_page, ($number_page-1) * $per_page);
		elseif ($per_page):
			$this->db->limit($per_page);
		endif;

		$query = $this->db->get();
		return $query;
	} 

	function get_content_by_rubric($r_id)
	{
		$this->db->select('c_id, c_title')
				 ->from('content')
				 ->join('rubric', 'content.r_id = rubric.r_id')
				 ->where('rubric.r_id', $r_id);

		$query = $this->db->get();
		return $query;
	}

	function get_content_by_user($u_id, $limit, $c_id)
	{
		$this->db->select('c_id, c_title, c_content, c_tags, c_status, c_cdate, c_udate, c_pdate, c_url_rw, rubric.r_id, r_title, r_url_rw');
		$this->db->from('content');
		$this->db->join('user', 'content.u_id = user.u_id');
		$this->db->join('rubric', 'content.r_id = rubric.r_id');
		$this->db->where('user.u_id', $u_id);
		$this->db->where('c_status', 1);
		$this->db->where('c_pdate <=', unix_to_human(now(), TRUE, 'eu') );
		$this->db->where_not_in('c_id', $c_id);
		$this->db->order_by('c_id', 'DESC');

		if (!empty($limit)):
			$this->db->limit($limit);
		endif;

		$query = $this->db->get();
		return $query;
	}

	// tags
	function get_content_by_tag_name($t_name, $number_page, $per_page)
	{
		$this->db->select('*');
		$this->db->from('content');
		$this->db->join('rubric', 'content.r_id = rubric.r_id');
		$this->db->like('c_tags', $t_name);
		$this->db->where('c_status', 1);
		$this->db->where('c_pdate <=', unix_to_human(now(), TRUE, 'eu') );
		$this->db->order_by('c_id', 'DESC');

		if ($number_page and $per_page):
			$this->db->limit($per_page, ($number_page-1) * $per_page);
		elseif ($per_page):
			$this->db->limit($per_page);
		endif;		 

		$query = $this->db->get();
		return $query;
	}

	function get_tags()
	{
		$select = 'GROUP_CONCAT(DISTINCT c_tags
				   ORDER BY c_tags DESC SEPARATOR "") as tags';

		$this->db->select($select);
		$this->db->from('content');
		$this->db->where('c_status', 1);
		$this->db->where('c_pdate <=', unix_to_human(now(), TRUE, 'eu') );

		$query = $this->db->get();
		return $query;
	}

	function get_all_tags()
	{
		$select = 'GROUP_CONCAT(DISTINCT c_tags
				   ORDER BY c_tags ASC SEPARATOR "") as tags';

		$this->db->select($select);
		$this->db->from('content');

		$query = $this->db->get();
		return $query;
	}

	function get_contents_by_rubric($rubric)
	{
		$this->db->distinct()
				 ->select('c_id, c_title, c_content, c_tags, c_status, c_cdate, c_udate, c_pdate, c_url_rw, rubric.r_id, r_title, r_url_rw, user.u_id, u_login')
				 ->join('rubric', 'rubric.r_id = content.r_id')
				 ->join('user', 'user.u_id = content.u_id')
				 ->from('content')
				 ->where('r_url_rw', $rubric)
				 ->order_by('c_id', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	function get_contents_by_tag($tag)
	{
		$this->db->distinct()
				 ->select('c_id, c_title, c_content, c_tags, c_status, c_cdate, c_udate, c_pdate, c_url_rw, rubric.r_id, r_title, r_url_rw, user.u_id, u_login')
				 ->join('rubric', 'rubric.r_id = content.r_id')
				 ->join('user', 'user.u_id = content.u_id')
				 ->from('content')
				 ->like('c_tags', $tag)
				 ->order_by('c_id', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	function get_contents_same_tag($slug_rubric, $slug_content, $c_tags)
	{
		$this->db->select('c_title, c_url_rw, r_url_rw');
		$this->db->join('rubric', 'content.r_id = rubric.r_id');
		$this->db->from('content');
		$this->db->where_not_in('content.c_url_rw', $slug_content);
		$this->db->where('c_status', 1);
		foreach ($c_tags as $tag):
			$this->db->or_like('c_tags', $tag);
		endforeach;
		$this->db->where('c_pdate <=', unix_to_human(now(), TRUE, 'eu') );
		$this->db->order_by('c_id', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	function get_tag_admin($c_tags, $option, $user)
	{
		$this->db->select('c_id, c_title, c_content, c_tags, c_status, c_cdate, c_udate, c_pdate, c_url_rw, rubric.r_id, r_title, r_url_rw, user.u_id, u_login');
		$this->db->join('rubric', 'content.r_id = rubric.r_id');
		$this->db->from('content');
		$this->db->join('user', 'user.u_id = content.u_id');

		if ($user):
			$this->db->where('user.u_id', $user);
		endif;

		if (is_array($c_tags)):
			$count = count($c_tags);
			foreach ($c_tags as $tag):
				if ($count > 1):
					if ($option == 'or'):
						$this->db->or_like('c_tags', $tag);
					else:
						$this->db->like('c_tags', $tag);
					endif;
				else:
					$this->db->like('c_tags', $tag);
				endif;
			endforeach;
		else:
			$this->db->like('c_tags', $c_tags);
		endif;
		$this->db->order_by('c_id', 'DESC');

		$query = $this->db->get();
		return $query;
	}

}


/* End of file model_content.php */
/* Location: ./application/models/admin/model_content.php */