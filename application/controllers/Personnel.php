<?php defined('BASEPATH') or exit('No direct script access allowed');

class Personnel extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library(['form_validation']);
    $this->load->helper(['other_component_helper', 'modal_helper']);
    $this->load->model('Personnel_Model', 'personnel_manager');
    $this->load->model('License_Model', 'license_manager');
  }

  public function index()
  {
    if (!$this->ion_auth->logged_in()) {
      // redirect them to the login page
      return redirect('auth/login', 'refresh');
    }
    $this->layout->set_titre('Personnels - BOA');
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
    return $this->layout->view('personnel', $data);
  }

  public function personnels($id = NULL)
  {
    if (!$this->ion_auth->logged_in()) {
      return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");
    }
    if (!empty($id)) {
      $data = $this->personnel_manager->items(array('id' => $id))[0];
    } else {
      $data = $this->personnel_manager->items();
    }
    return $this->ajax->output($data);
  }

  public function search()
  {
    if (!$this->ion_auth->logged_in()) {
      return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");
    }
    $text = $this->input->get('text');
    $data = $this->personnel_manager->items(
      array(),
      NULL,
      NULL,
      array(),
      array('personnel.serial_number' => $text, 'personnel.name' => $text)
    );
    return $this->ajax->output($data);
  }

  public function create()
  {
    if (!$this->ion_auth->logged_in())
      return $this->ajax->output(NULL, 401, "Vous n'êtes pas authorisé");

    // validate form input
    $this->form_validation->set_rules('serial_number', 'Numéro Matricule', 'trim|required|is_unique[' . $this->personnel_manager->table . '.serial_number]');
    $this->form_validation->set_rules('name', 'Nom et prénom(s)', 'trim|required');
    $this->form_validation->set_rules('departement', 'Departement', 'trim|required');
    $this->form_validation->set_rules('post', 'Poste', 'trim|required');

    if ($this->form_validation->run() === TRUE) {
      $data = array(
        'serial_number' => $this->input->post('serial_number'),
        'name'  => $this->input->post('name'),
        'departement' => $this->input->post('departement'),
        'post' => $this->input->post('post')
      );

      if ($this->personnel_manager->create($data)) {
        $this->session->set_flashdata('message', "L'ajout du personnel est avec succès");
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

    $personnel = $this->personnel_manager->read('*', array('id' => $id))[0];

    // validate form input
    $this->form_validation->set_rules('serial_number', 'Numéro Matricule', 'trim|required');
    $this->form_validation->set_rules('name', 'Nom et prénom(s)', 'trim|required');
    $this->form_validation->set_rules('departement', 'Departement', 'trim|required');
    $this->form_validation->set_rules('post', 'Poste', 'trim|required');


    if (isset($_POST) && !empty($_POST)) {
      if ($this->form_validation->run() === TRUE) {
        $data = array(
          'serial_number' => $this->input->post('serial_number'),
          'name'  => $this->input->post('name'),
          'departement' => $this->input->post('departement'),
          'post' => $this->input->post('post')
        );

        if ($this->personnel_manager->update(array('id' => $personnel->id), $data)) {
          return $this->ajax->output(array('success' => TRUE), 202, 'La modification portée sur le personnel est avec succès');
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

    if ($this->personnel_manager->delete($id)) {
      return $this->ajax->output(array('success' => TRUE), 202, "Le personnel est supprimé");
    }
    return $this->ajax->output(array('success' => FALSE), 400, 'Erreur');
  }
}
