<?php defined('BASEPATH') or exit('No direct script access allowed');

class Software_Model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'software';
    $this->load->model('License_Model', 'license_manager');
    $this->load->model('Personnel_Model', 'personnel_manager');
  }

  public function items($where = array(), $limit = NULL, $offset = NULL, $orders = array(),  $likes = array())
  {
    $select = 'software.*';
    $select .= ', license.id as license_id, license.name as license_name, license.license_number as license_license_number, license.number_of_licenses as license_number_of_licenses, license.date_expiration as license_date_expiration';
    $select .= ', personnel.id as personnel_id, personnel.serial_number as personnel_serial_number, personnel.name as personnel_name, personnel.departement as personnel_departement, personnel.post as personnel_post';
    $join_options = array(
      array('license', 'software.license_id = license.id', 'LEFT'),
      array('software_personnel', 'software.id = software_personnel.software_id', 'LEFT'),
      array('personnel', 'software_personnel.personnel_id = personnel.id', 'LEFT')
    );
    $softwares = $this->read_with_join($select, $join_options, $where, $limit, $offset, $orders, $likes);
    $output = array();
    foreach ($softwares as $software) {
      if (!array_key_exists($software->id, $output)) {
        $software->personnels = [];
        if ($software->personnel_id) {
          $software->personnels[] = array('id' => $software->personnel_id, 'serial_number' => $software->personnel_serial_number, 'name' => $software->personnel_name, 'departement' => $software->personnel_departement, 'post' => $software->personnel_post);
        }
        unset($software->personnel_id);
        unset($software->personnel_serial_number);
        unset($software->personnel_name);
        unset($software->personnel_departement);
        unset($software->personnel_post);

        if ($software->license_id) {
          $software->license = array('id' => $software->license_id, 'name' => $software->license_name, 'license_number' => $software->license_license_number, 'number_of_licenses' => $software->license_number_of_licenses, 'date_expiration' => $software->license_date_expiration);
        } else {
          $software->license = NULL;
        }
        unset($software->license_id);
        unset($software->license_name);
        unset($software->license_license_number);
        unset($software->license_number_of_licenses);
        unset($software->license_date_expiration);

        $output[$software->id] = $software;
      } else {
        if ($software->personnel_id) {
          $output[$software->id]->personnels[] = array('id' => $software->personnel_id, 'serial_number' => $software->personnel_serial_number, 'name' => $software->personnel_name, 'departement' => $software->personnel_departement, 'post' => $software->personnel_post);
        }
      }
    }
    return array_values($output);
  }

  public function delete($where)
  {
    $this->delete_into_table('software_personnel', array('software_id' => $where));
    return parent::delete($where);
  }
}
