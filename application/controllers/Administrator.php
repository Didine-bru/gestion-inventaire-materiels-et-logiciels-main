<?php defined('BASEPATH') or exit('No direct script access allowed');

class Administrator extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library(['form_validation']);
		$this->load->helper(['language', 'other_component_helper', 'modal_helper']);

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');
		$this->load->model('Administrator_Model', 'admin_manager');
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in()) {
			// redirect them to the login page
			return redirect('auth/login', 'refresh');
		}
		$this->layout->set_titre('Administrateurs - BOA');
		$this->layout->add_js('request_utils');

		// print_r();
		$user = $this->ion_auth->user()->result()[0];
		$user->is_admin = $this->ion_auth->is_admin();
		$data = array(
			'user' => $user
		);
		return $this->layout->view('administrator', $data);
	}

	public function administrators($id = NULL)
	{
		if (!$this->ion_auth->logged_in()) {
			return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");
		}
		if (!empty($id)) {
			if (!$this->ion_auth->is_admin() & $this->input->get('id') != $this->ion_auth->get_user_id())
				return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");
			$user = $this->ion_auth->user((int)$this->input->get('id'))->result()[0];
			$group = $this->ion_auth->get_users_groups($user->id)->result();
			$data = $user;
			$data['groups'] = $group;
		} else if (!$this->ion_auth->is_admin()) {
			return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");
		} else {
			$users = $this->admin_manager->items();
			$data = [];
			foreach ($users as $user) {
				unset($user->password);
				$data[] = $user;
			}
		}
		return $this->ajax->output($data);
	}

	public function search()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");
		}
		$text = $this->input->get('text');
		$users = $this->admin_manager->items(
			array(),
			NULL,
			NULL,
			array(),
			array('first_name' => $text, 'last_name' => $text, 'email' => $text)
		);
		$data = [];
		foreach ($users as $user) {
			unset($user->password);
			$data[] = $user;
		}
		return $this->ajax->output($data);
	}

	public function create()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
			return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");

		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim');
		if ($identity_column !== 'email') {
			$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|valid_email');
		} else {
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|valid_email|is_unique[' . $tables['users'] . '.email]');
			$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.username]');
		}
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() === TRUE) {
			$email = strtolower($this->input->post('email'));
			// $identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
			if ($this->input->post('identity'))
				$identity = $this->input->post('identity');
			else
				$identity = $email;
			$password = $this->input->post('password');

			$additional_data = [
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
			];

			if ($this->input->post('is_admin'))
				$group_ids = [1, 2];
			else
				$group_ids = [2];

			if ($this->ion_auth->register($identity, $password, $email, $additional_data, $group_ids)) {
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				return $this->ajax->output(array('success' => TRUE), 201, $this->session->flashdata('message'));
			}
		}
		$message = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		return $this->ajax->output(array('success' => FALSE), 400, $message);
	}

	public function edit($id)
	{
		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id))) {
			return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");
		}

		$user = $this->ion_auth->user($id)->row();

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim');
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|valid_email');
		$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim');

		if (isset($_POST) && !empty($_POST)) {
			// update the password if it was posted
			if ($this->input->post('password')) {
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE) {
				$data = [
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'email' => $this->input->post('email'),
					'username' => $this->input->post('identity')
				];

				// update the password if it was posted
				if ($this->input->post('password')) {
					$data['password'] = $this->input->post('password');
				}

				// Only allow updating groups if user is admin
				if ($this->ion_auth->is_admin()) {
					// Update the groups user belongs to
					$this->ion_auth->remove_from_group('', $id);

					if ($this->input->post('is_admin'))
						$groupData = [1, 2];
					else
						$groupData = [2];

					if (isset($groupData) && !empty($groupData)) {
						foreach ($groupData as $grp) {
							$this->ion_auth->add_to_group($grp, $id);
						}
					}
				}

				// check to see if we are updating the user
				if ($this->ion_auth->update($user->id, $data)) {
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					return $this->ajax->output(array('success' => TRUE), 202, $this->session->flashdata('message'));
				} else {
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					return $this->ajax->output(array('success' => FALSE), 400, $this->session->flashdata('message'));
				}
			}
		}
		$message = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		return $this->ajax->output(array('success' => FALSE), 400, $message);
	}

	public function delete($id)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
			return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");

		if ($this->ion_auth->delete_user($id)) {
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			return $this->ajax->output(array('success' => TRUE), 202, $this->session->flashdata('message'));
		}
		$this->session->set_flashdata('message', $this->ion_auth->errors());
		return $this->ajax->output(array('success' => FALSE), 400, $this->session->flashdata('message'));
	}
}
