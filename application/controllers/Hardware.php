<?php defined('BASEPATH') or exit('No direct script access allowed');

class Hardware extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library(['form_validation']);
    $this->load->helper(['other_component_helper', 'modal_helper']);
    $this->load->model('Hardware_Model', 'hardware_manager');
    $this->load->model('Personnel_Model', 'personnel_manager');
  }

  public function index()
  {
    if (!$this->ion_auth->logged_in()) {
      // redirect them to the login page
      return redirect('auth/login', 'refresh');
    }
    $this->layout->set_titre('Matériels - BOA');
    $this->layout->add_css('flatpickr.min');
    $this->layout->add_js('flatpickr');
    $this->layout->add_js('flatpickr_locale_fr');
    $this->layout->add_js('request_utils');

    $user = $this->ion_auth->user()->result()[0];
    $user->is_admin = $this->ion_auth->is_admin();
    $personnels = $this->personnel_manager->read('id, name');
    $data = array(
      'user' => $user,
      'personnels' => $personnels
    );
    return $this->layout->view('hardware', $data);
  }

  public function hardwares($id = NULL)
  {
    if (!$this->ion_auth->logged_in()) {
      return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");
    }

    if (!empty($id)) {
      $data = $this->hardware_manager->items(array('id' => $id))[0];
    } else {
      $data = $this->hardware_manager->items();
    }
    return $this->ajax->output($data);
  }

  public function search()
	{
		if (!$this->ion_auth->logged_in()) {
			return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");
		}
		$text = $this->input->get('text');
		$data = $this->hardware_manager->items(
			array(),
			NULL,
			NULL,
			array(),
			array('hardware.serial_number' => $text, 'model' => $text, 'manufacturer' => $text)
		);
		return $this->ajax->output($data);
	}

  public function create()
  {
    if (!$this->ion_auth->logged_in())
      return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");

    // validate form input
    $this->form_validation->set_rules('serial_number', 'Numéro de série', 'trim|required|is_unique[' . $this->hardware_manager->table . '.serial_number]');
    $this->form_validation->set_rules('model', 'Modèle', 'trim|required');
    $this->form_validation->set_rules('color', 'Couleur', 'trim|required');
    $this->form_validation->set_rules('category', 'Categorie', 'trim|required');
    $this->form_validation->set_rules('manufacturer', 'Fabricant', 'trim|required');
    $this->form_validation->set_rules('date_purchase', "Date d'achat", 'trim|required');
    $this->form_validation->set_rules('date_warranty_expiration', "Date d'expiration de la garantie", 'trim|required');

    if ($this->form_validation->run() === TRUE) {
      $data = array(
        'serial_number' => $this->input->post('serial_number'),
        'model'  => $this->input->post('model'),
        'color' => $this->input->post('color'),
        'category' => $this->input->post('category'),
        'manufacturer' => $this->input->post('manufacturer'),
        'date_purchase' => $this->input->post('date_purchase'),
        'date_warranty_expiration' => $this->input->post('date_warranty_expiration'),
        'under_maintenance' => $this->input->post('under_maintenance'),
      );
      $not_escaped_options = array();
      if (!$this->input->post('under_maintenance') && !empty($this->input->post('personnel_id'))) {
        $data['personnel_id'] = $this->input->post('personnel_id');
      }
      if ($this->input->post('under_maintenance') || !empty($this->input->post('personnel_id'))) {
        $not_escaped_options['date_movement'] = 'NOW()';
      }

      if ($this->hardware_manager->create($data, $not_escaped_options)) {
        $this->session->set_flashdata('message', "L'ajout du matériel est avec succès");
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

    $hardware = $this->hardware_manager->read('*', array('id' => $id))[0];

    // validate form input
    $this->form_validation->set_rules('serial_number', 'Numéro de série', 'trim|required');
    $this->form_validation->set_rules('model', 'Modèle', 'trim|required');
    $this->form_validation->set_rules('color', 'Couleur', 'trim|required');
    $this->form_validation->set_rules('category', 'Categorie', 'trim|required');
    $this->form_validation->set_rules('manufacturer', 'Fabricant', 'trim|required');
    $this->form_validation->set_rules('date_purchase', "Date d'achat", 'trim|required');
    $this->form_validation->set_rules('date_warranty_expiration', "Date d'expiration de la garantie", 'trim|required');


    if (isset($_POST) && !empty($_POST)) {
      if ($this->form_validation->run() === TRUE) {
        $data = array(
          'serial_number' => $this->input->post('serial_number'),
          'model'  => $this->input->post('model'),
          'color' => $this->input->post('color'),
          'category' => $this->input->post('category'),
          'manufacturer' => $this->input->post('manufacturer'),
          'date_purchase' => $this->input->post('date_purchase'),
          'date_warranty_expiration' => $this->input->post('date_warranty_expiration'),
          'under_maintenance' => $this->input->post('under_maintenance'),
        );
        // print_r($data);
        $not_escaped_options = array();
        if ($data['under_maintenance']) {
          $data['personnel_id'] = NULL;
        } else
          $data['personnel_id'] = $this->input->post('personnel_id');

        if (($data['under_maintenance'] && !$hardware->under_maintenance) || (!empty($this->input->post('personnel_id')) && $this->input->post('personnel_id') != $hardware->personnel_id)) {
          $not_escaped_options['date_movement'] = 'NOW()';
        }
        if (!$data['under_maintenance'] && empty($this->input->post('personnel_id'))) {
          $data['date_movement'] = NULL;
          $not_escaped_options = array();
        }

        if ($this->hardware_manager->update(array('id' => $hardware->id), $data, $not_escaped_options)) {
          return $this->ajax->output(array('success' => TRUE), 202, 'La modification portée sur le matériel est avec succès');
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

    if ($this->hardware_manager->delete($id)) {
      return $this->ajax->output(array('success' => TRUE), 202, "Le matériel est supprimé");
    }
    return $this->ajax->output(array('success' => FALSE), 400, 'Erreur');
  }
}
