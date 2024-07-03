<?php defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Hardware_Model', 'hardware_manager');
    $this->load->model('Software_Model', 'software_manager');
		$this->load->model('License_Model', 'license_manager');
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in()) {
			// redirect them to the login page
			return redirect('auth/login', 'refresh');
		}
		$this->layout->set_titre('Acceuil - BOA');
		$user = $this->ion_auth->user()->result()[0];
    $user->is_admin = $this->ion_auth->is_admin();
		$data = array(
			'user' => $user
		);
		return $this->layout->view('home', $data);
	}

	public function statistics() {
		if (!$this->ion_auth->logged_in()) {
			// redirect them to the login page
			return redirect('auth/login', 'refresh');
		}
		$hardware = array(
			'used' => $this->hardware_manager->count(array('personnel_id !=' => NULL)),
			'maintenance' => $this->hardware_manager->count(array('under_maintenance' => TRUE)),
			'total' => $this->hardware_manager->count()
		);
		$software = array(
			'expired' => $this->software_manager->count(array('date_warranty_expiration <' => date('Y-m-d'))),
			'total' => $this->software_manager->count()
		);
		$license = array(
			'expired' => $this->license_manager->count(array('date_expiration <' => date('Y-m-d'))),
			'total' => $this->license_manager->count()
		);
		$data = array(
			'hardware' => $hardware,
			'software' => $software,
			'license' => $license
		);
		return $this->ajax->output($data);
	}
}
