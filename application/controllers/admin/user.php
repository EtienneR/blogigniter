<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('model_content', 'model_user', 'model_comment'));
		$this->load->library(array('form_validation', 'session', 'admin/functions'));
		$this->load->helper(array('form', 'functions', 'text'));
		define('URL_LAYOUT'		 , 'admin/view_dashboard');
		define('URL_HOME_CONTENT', 'admin/content');
		define('URL_HOME_RUBRIC' , 'admin/rubric');
		define('URL_HOME_USERS'  , 'admin/user');
		session_start();
		if (isset($_GET["profiler"])):
			$this->output->enable_profiler(TRUE);
		endif;
	}

	// Display all users
	public function index()
	{
		if ($this->functions->get_loged()):
			$data['user_data'] = $this->functions->get_user_data();

			$data['page']  = 'users';
			$data['title'] = 'Utilisateurs';

			$users = $data['query'] = $this->functions->get_all_user();

			$data['nb_comments'] = $this->functions->get_comments();

			$this->load->view(URL_LAYOUT, $data);

		endif;
	}

	// Add or edit an user
	public function edit($u_id = '')
	{
		if ($this->functions->get_loged()):
			$data['user_data'] = $this->functions->get_user_data();

			$data['nb_comments'] = $this->functions->get_comments();

			// If user with "admin" level or 
			if ( $data['user_data']['level'] == 1 OR ($data['user_data']['id_user'] == $u_id) ):

				$this->form_validation->set_rules('u_level', 'Level', 'required');
				$this->form_validation->set_rules('u_login', 'Login', 'trim|required|callback_check_user');
				$this->form_validation->set_rules('u_email', 'Email', 'trim|required|valid_email|callback_check_email');
				$this->form_validation->set_rules('u_biography', 'Biography', 'trim');
				$this->form_validation->set_rules('u_twitter', 'Twitter', 'trim');
				$this->form_validation->set_rules('u_google', 'Google Plus', 'trim');

				$u_login 	 = $this->input->post('u_login');
				$u_level 	 = $this->input->post('u_level');
				$u_email 	 = $this->input->post('u_email');
				$u_status 	 = $this->input->post('u_status');
				$u_biography = $this->input->post('u_biography');
				$u_twitter	 = $this->input->post('u_twitter');
				$u_google	 = $this->input->post('u_google');

				// Add an user
				if ($this->uri->total_segments() == 3):
					$data['page']  = 'add_user';
					$data['title'] = 'Ajouter un utilisateur';

					$this->form_validation->set_rules('u_pass', 'Pass', 'trim|required');

					$u_pass	  = $this->input->post('u_pass');
					$u_status = 1;

					if ($this->form_validation->run() !== FALSE):
						$this->model_user->create_user($u_login, $u_pass, $u_status, $u_email, $u_level, $u_biography, $u_twitter, $u_google);
						$this->session->set_flashdata('success', 'Utilisateur ou utilisatrice "' . $u_login . '" ajouté(e)');
						redirect(base_url(URL_HOME_USERS));
					endif;

				// Edit an user
				else:

					$user = $this->model_user->get_user($u_id, '')->row();

					// If user exists
					if (!empty($user)):

						$data['page']		 = 'edit_user';
						$data['u_id']		 = $user->u_id;
						$data['u_login']	 = $user->u_login;
						$data['u_email']	 = $user->u_email;
						$data['u_level']	 = $user->u_level;
						$data['u_status']	 = $user->u_status;
						$data['u_biography'] = $user->u_biography;
						$data['u_twitter']	 = $user->u_twitter;
						$data['u_google']	 = $user->u_google;

						if ($data['user_data']['id_user'] == $data['u_id']):
							$data['title'] = "Modifier mon profil";
						else:
							$data['title'] = "Modifier l'utilisateur " . $data['u_login'];
						endif;

						if ($this->form_validation->run() !== FALSE):
							$this->model_user->update_user($u_login, $u_email, $u_level, $u_status, $u_biography, $u_twitter, $u_google, $u_id);
							$this->session->set_flashdata('success', 'Utilisateur ou utilisatrice "' . $u_login . '" modifié(e) (les paramètres prendront effet lors de la prochaine connexion).');
							redirect(base_url(URL_HOME_USERS));
						endif;

					else:
						$this->session->set_flashdata('warning', 'Cette utilisateur (#' . $u_id . ') n\'existe plus ou n\'a jamais existé');
						redirect(base_url(URL_HOME_CONTENT));
					endif;

				endif;

				$this->load->view(URL_LAYOUT, $data);

			else:
				$this->session->set_flashdata('alert', 'Vous ne disposez pas des droits nécessaires pour accèder à cette partie.');
				redirect(base_url(URL_HOME_CONTENT));
			endif;

		endif;
	}

	// Check if an user already exists
	public function check_user($u_login)
	{
		$u_id = $this->uri->segment(4);

		if ($this->model_user->check_user($u_id, $u_login)->num_rows() == 1):
			$this->form_validation->set_message('check_user', 'Impossible de rajouter ou de modifier l\'utilisateur "' . $u_login . '" car ce dernier existe déjà.');
			return FALSE;
		else:
			return TRUE;
		endif;
	}

	// Check if the user email already exists
	public function check_email($u_email)
	{
		$u_id = $this->uri->segment(4);

		if ($this->model_user->check_email($u_id, $u_email)->num_rows() == 1):
			$this->form_validation->set_message('check_email', 'Impossible de rajouter ou de modifier l\'utilisateur avec l\'email "' . $u_email . '" car cette adresse email existe déjà.');
			return FALSE;
		else:
			return TRUE;
		endif;	
	}

	// Delete an user
	public function delete($u_id)
	{
		if ($this->functions->get_loged()):

			// If the user with "admin" level
			if ($this->functions->get_user_level() == 1):

				// If user exists
				if ($this->model_user->get_user($u_id, '')->num_rows() == 1):

					// If no content attached to this user
					if ($this->model_content->get_content_by_user($u_id, '', '')->num_rows() == 0):
						$this->model_user->delete_user($u_id);
						$this->session->set_flashdata('success', 'Utilisateur ou utilisatrice supprimé(e).');
					else:
						$url = base_url('admin/user/' . $u_id);
						$this->session->set_flashdata('alert', 'Impossible de supprimer cette utilisateur / utilisatrice car il / elle a un ou plusieurs article(s) rattaché(s). <a href="' . $url . '">Afficher</a>');
					endif;

				else:
					$this->session->set_flashdata('warning', 'Cette utilisateur (#' . $u_id . ') n\'existe plus ou n\'a jamais existé');
				endif;

				redirect(base_url(URL_HOME_USERS));

			else:
				$this->session->set_flashdata('alert', 'Vous ne disposez pas des droits nécessaires pour accèder à cette partie.');
				redirect(base_url(URL_HOME_CONTENT));
			endif;

		endif;
	}

	// Changing his own password
	public function change_password()
	{
		if ($this->functions->get_loged()):
			$data['user_data'] = $this->functions->get_user_data();
			$u_id = $data['user_data']['id_user'];

			$data['page']  = 'change_password';
			$data['title'] = 'Changer mon mot de passe';

			$u_old_pass   = $this->input->post('u_old_pass');
			$u_new_pass   = $this->input->post('u_pass');
			$u_new_pass_2 = $this->input->post('u_pass_2');

			$params = "{$u_old_pass},{$u_new_pass},{$u_new_pass_2}";

			$this->form_validation->set_rules('u_old_pass', 'Ancien password', 'trim|required|callback_check_password');
			$this->form_validation->set_rules('u_pass', 'Nouveau password', 'trim|required');
			$this->form_validation->set_rules("u_pass_2", "Nouveau password (confirmation)", "trim|required|callback_check_confirm[{$params}]");

			if ($this->form_validation->run() !== FALSE):
				$this->model_user->update_password_user($u_new_pass, $u_id);
				$this->session->set_flashdata('success', 'Votre mot de passe a bien été validé et marchera à la première reconnexion.');
				redirect(base_url(URL_HOME_USERS));
			endif;

			$this->load->view(URL_LAYOUT, $data);

		endif;
	}

	// Check the user password
	public function check_password($u_old_pass)
	{
		$id_user = $this->functions->get_user_id();

		if ($this->model_user->check_user_password($id_user, $u_old_pass)->num_rows() == 0):
			$this->form_validation->set_message('check_password', 'L\'ancien mot de passe n\'est pas bon.');
			return FALSE;
		else:
			return TRUE;
		endif;
	}

	// Check if : the new password is different from the old ; and if : both new passwords are same
	public function check_confirm($null, $params = '')
	{
		list($u_old_pass, $u_new_pass, $u_new_pass_2) = explode(',', $params);

		if ($u_old_pass == $u_new_pass):
			$this->form_validation->set_message('check_confirm', 'Le nouveau mot de passe est identique à l\'ancien.');
			return false;
		elseif ($u_new_pass !== $u_new_pass_2):
			$this->form_validation->set_message('check_confirm', 'Les 2 nouveaux mots de passe ne correspondent pas');
			return false;
		else:
			return true;
		endif;
	}

}


/* End of file user.php */
/* Location: ./application/controllers/admin/user.php */