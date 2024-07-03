<?php defined('BASEPATH') or exit('No direct script access allowed');

class Software extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library(['form_validation']);
		$this->load->helper(['other_component_helper', 'modal_helper']);
		$this->load->model('Software_Model', 'software_manager');
		$this->load->model('Personnel_Model', 'personnel_manager');
		$this->load->model('License_Model', 'license_manager');
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in()) {
			// redirect them to the login page
			return redirect('auth/login', 'refresh');
		}
		$this->layout->set_titre('Logiciels - BOA');
		$this->layout->add_css('flatpickr.min');
		$this->layout->add_js('flatpickr');
		$this->layout->add_js('flatpickr_locale_fr');
		$this->layout->add_js('request_utils');

		// print_r();
		$user = $this->ion_auth->user()->result()[0];
		$user->is_admin = $this->ion_auth->is_admin();
		$personnels = $this->personnel_manager->read('id, name');
		$licenses = $this->license_manager->read('id, name');
		$data = array(
			'user' => $user,
			'personnels' => $personnels,
			'licenses' => $licenses
		);
		return $this->layout->view('software', $data);
	}

	public function softwares($id = NULL)
	{
		if (!$this->ion_auth->logged_in()) {
			return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");
		}
		if (!empty($id)) {
			$data = $this->software_manager->items(array('id' => $id))[0];
		} else {
			$data = $this->software_manager->items();
		}
		return $this->ajax->output($data);
	}

	public function search()
	{
		if (!$this->ion_auth->logged_in()) {
			return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");
		}
		$text = $this->input->get('text');
		$data = $this->software_manager->items(
			array(),
			NULL,
			NULL,
			array(),
			array('software.name' => $text, 'software.version' => $text, 'departement' => $text)
		);
		return $this->ajax->output($data);
	}

	public function create()
	{
		if (!$this->ion_auth->logged_in())
			return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");

		// validate form input
		$this->form_validation->set_rules('name', 'Nom du logiciel', 'trim|required');
		$this->form_validation->set_rules('version', 'Version', 'trim|required');
		$this->form_validation->set_rules('date_purchase', "Date d'achat", 'trim|required');
		$this->form_validation->set_rules('date_warranty_expiration', "Date d'expiration de la garantie", 'trim');

		if ($this->form_validation->run() === TRUE) {
			$data = array(
				'name' => $this->input->post('name'),
				'version'  => $this->input->post('version'),
				'date_purchase' => $this->input->post('date_purchase'),
				'date_warranty_expiration' => $this->input->post('date_warranty_expiration'),
			);
			if ($this->input->post('license_id'))
				$data['license_id'] = $this->input->post('license_id');

			if ($this->software_manager->create($data)) {
				$software_id = $this->software_manager->db->insert_id();

				if ($this->input->post('personnel_ids')) {
					$personnel_ids = explode(',', $this->input->post('personnel_ids'));
					foreach ($personnel_ids as $personnel_id) {
						$this->software_manager->create_into_table(
							'software_personnel',
							array('personnel_id' => $personnel_id, 'software_id' => $software_id)
						);
					}
				}
				$this->session->set_flashdata('message', "L'ajout du logiciel est avec succès");
				return $this->ajax->output(array('success' => TRUE), 201, $this->session->flashdata('message'));
			}
		}
		$message = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
		return $this->ajax->output(array('success' => FALSE), 400, $message);
	}

	public function edit($id)
	{
		if (!$this->ion_auth->logged_in()) {
			return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");
		}

		$software = $this->software_manager->read('*', array('id' => $id))[0];

		// validate form input
		$this->form_validation->set_rules('name', 'Nom du logiciel', 'trim|required');
		$this->form_validation->set_rules('version', 'Version', 'trim|required');
		$this->form_validation->set_rules('date_purchase', "Date d'achat", 'trim|required');
		$this->form_validation->set_rules('date_warranty_expiration', "Date d'expiration de la garantie", 'trim');

		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$data = array(
					'name' => $this->input->post('name'),
					'version'  => $this->input->post('version'),
					'date_purchase' => $this->input->post('date_purchase'),
					'date_warranty_expiration' => $this->input->post('date_warranty_expiration'),
				);
				if ($this->input->post('license_id'))
					$data['license_id'] = $this->input->post('license_id');
				else
					$data['license_id'] = NULL;
				
				$this->software_manager->delete_into_table('software_personnel', array('software_id' => $software->id));
				if ($this->input->post('personnel_ids')) {
					$personnel_ids = explode(',', $this->input->post('personnel_ids'));
					foreach ($personnel_ids as $personnel_id) {
						$this->software_manager->create_into_table(
							'software_personnel',
							array('personnel_id' => $personnel_id, 'software_id' => $software->id)
						);
					}
				}				

				if ($this->software_manager->update(array('id' => $software->id), $data)) {
					return $this->ajax->output(array('success' => TRUE), 202, 'La modification portée sur le logiciel est avec succès');
				} else {
					return $this->ajax->output(array('success' => FALSE), 400, 'Erreur sur la modification');
				}
			}
		}
		$message = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
		return $this->ajax->output(array('success' => FALSE), 400, $message);
	}

	public function delete($id)
	{
		if (!$this->ion_auth->logged_in())
			return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");

		if ($this->software_manager->delete($id)) {
			return $this->ajax->output(array('success' => TRUE), 202, "Le logiciel est supprimé");
		}
		return $this->ajax->output(array('success' => FALSE), 400, 'Erreur');
	}
}
