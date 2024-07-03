<?php defined('BASEPATH') or exit('No direct script access allowed');

class License extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library(['form_validation']);
    $this->load->helper(['other_component_helper', 'modal_helper']);
    $this->load->model('License_Model', 'license_manager');
    $this->load->model('Software_Model', 'software_manager');
		$this->load->model('Personnel_Model', 'personnel_manager');
  }

  public function index()
  {
    if (!$this->ion_auth->logged_in()) {
      // redirect them to the login page
      return redirect('auth/login', 'refresh');
    }
    $this->layout->set_titre('Licences - BOA');
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
    return $this->layout->view('license', $data);
  }

  public function licenses($id = NULL)
  {
    if (!$this->ion_auth->logged_in()) {
      return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");
    }
  
    if (!empty($id)) {
      $data = $this->license_manager->items(array('id' => $id))[0];
    } else {
      $data = $this->license_manager->items();
    }
    return $this->ajax->output($data);
  }

  public function search()
	{
		if (!$this->ion_auth->logged_in()) {
			return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");
		}
		$text = $this->input->get('text');
		$data = $this->license_manager->items(
			array(),
			NULL,
			NULL,
			array(),
			array('license.name' => $text, 'license.license_number' => $text)
		);
		return $this->ajax->output($data);
	}

  public function create()
  {
    if (!$this->ion_auth->logged_in())
      return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");

    // validate form input
    $this->form_validation->set_rules('license_number', 'Numéro de licence', 'trim|required|is_unique[' . $this->license_manager->table . '.license_number]');
    $this->form_validation->set_rules('name', 'Nom de la license', 'trim|required');
    $this->form_validation->set_rules('number_of_licenses', 'Nombre des licenses', 'trim|required');
    $this->form_validation->set_rules('date_expiration', "Date d'expiration", 'trim');

    if ($this->form_validation->run() === TRUE) {
      $data = array(
        'license_number' => $this->input->post('license_number'),
        'name'  => $this->input->post('name'),
        'number_of_licenses' => $this->input->post('number_of_licenses'),
        'date_expiration' => $this->input->post('date_expiration')
      );

      if ($this->license_manager->create($data)) {
        $this->session->set_flashdata('message', "L'ajout de la licence est avec succès");
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

    $license = $this->license_manager->read('*', array('id' => $id))[0];

    // validate form input
    $this->form_validation->set_rules('license_number', 'Numéro de licence', 'trim|required');
    $this->form_validation->set_rules('name', 'Nom de la license', 'trim|required');
    $this->form_validation->set_rules('number_of_licenses', 'Nombre des licenses', 'trim');
    $this->form_validation->set_rules('date_expiration', "Date d'expiration", 'trim');


    if (isset($_POST) && !empty($_POST)) {
      if ($this->form_validation->run() === TRUE) {
        $data = array(
          'license_number' => $this->input->post('license_number'),
          'name'  => $this->input->post('name'),
          'number_of_licenses' => $this->input->post('number_of_licenses'),
          'date_expiration' => $this->input->post('date_expiration')
        );

        if ($this->license_manager->update(array('id' => $license->id), $data)) {
          return $this->ajax->output(array('success' => TRUE), 202, 'La modification portée sur la licence est avec succès');
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

    if ($this->license_manager->delete($id)) {
      return $this->ajax->output(array('success' => TRUE), 202, "La licence est supprimée");
    }
    return $this->ajax->output(array('success' => FALSE), 400, 'Erreur');
  }
}
