<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_user extends CI_Model {

	function login($login, $password)
	{
		$this->db->select('u_id, u_login, u_pass, u_email, u_level')
				 ->from('user')
				 ->where('u_login', $login)
				 ->where('u_pass', MD5($password))
				 ->where('u_status', 1)
				 ->limit(1);

		$query = $this->db->get();
		return $query;
	}

	function get_users()
	{
		$this->db->select('u_id, u_login, u_pass, u_email, u_level, u_status, u_biography, u_twitter, u_google')
				 ->from('user')
				 ->order_by('u_id', 'ASC');

		$query = $this->db->get();
		return $query;
	}

	function get_user($u_id, $u_login)
	{
		$this->db->select('u_id, u_login, u_email, u_pass, u_level, u_status, u_biography, u_twitter, u_google');
		$this->db->from('user');
		if (empty($u_login)):
		$this->db->where('u_id', $u_id);
		else:
		$this->db->where('u_login', $u_login);
		endif;
		$this->db->limit(1);

		$query = $this->db->get();
		return $query;
	}

	function check_user($u_id, $u_login)
	{
		$this->db->select('u_login')
				 ->from('user')
				 ->where('u_id <>', $u_id)
				 ->where('u_login', $u_login)
				 ->limit(1);

		$query = $this->db->get();
		return $query;
	}

	function check_email($u_id, $u_email)
	{
		$this->db->select('u_email')
				 ->from('user')
				 ->where('u_email', $u_email)
				 ->where('u_id <>', $u_id)
				 ->limit(1);

		$query = $this->db->get();
		return $query;
	}

	function check_user_password($u_id, $u_old_pass)
	{
		$this->db->select('u_pass')
				 ->from('user')
				 ->where('u_id', $u_id)
				 ->where('u_pass', md5($u_old_pass))
				 ->limit(1);

		$query = $this->db->get();
		return $query;
	}

	function reset_password($u_email, $new_pass)
	{
		$data = array(
			'u_pass' => md5($new_pass)
		);

		$this->db->where('u_email', $u_email);
		$this->db->update('user', $data);
	}

	function create_user($u_login, $u_pass, $u_email, $u_level, $u_status, $u_biography, $u_twitter, $u_google)
	{
		$data = array(
			'u_login'	  => $u_login,
			'u_pass'	  => md5($u_pass),
			'u_email'	  => $u_email,
			'u_level'	  => $u_level,
			'u_status'	  => $u_status,
			'u_biography' => $u_biography,
			'u_twitter'	  => $u_twitter,
			'u_google'	  => $u_google
		);

		$this->db->insert('user', $data);
	}

	function update_user($u_login, $u_email, $u_level, $u_status, $u_biography, $u_twitter, $u_google, $u_id)
	{
		$data = array(
			'u_login'	  => $u_login,
			'u_email'	  => $u_email,
			'u_level'	  => $u_level,
			'u_status'	  => $u_status,
			'u_biography' => $u_biography,
			'u_twitter'	  => $u_twitter,
			'u_google'	  => $u_google
		);

		$this->db->where('u_id', $u_id);
		$this->db->update('user', $data);
	}

	function update_password_user($u_new_pass, $u_id)
	{
		$data = array(
			'u_pass' => md5($u_new_pass)
		);

		$this->db->where('u_id', $u_id);
		$this->db->update('user', $data);
	}


	function delete_user($u_id)
	{
		$this->db->where('u_id', $u_id)
				 ->delete('user');
	}


}


/* End of file model_user.php */
/* Location: ./application/models/admin/model_user.php */