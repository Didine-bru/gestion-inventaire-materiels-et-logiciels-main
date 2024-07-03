<?php defined('BASEPATH') or exit('No direct script access allowed');

class License_Model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'license';
    $this->load->model('software_Model', 'software_manager');
  }

  public function items($where = array(), $limit = NULL, $offset = NULL, $orders = array(),  $likes = array())
  {
    $select = 'license.*';
    $select .= ', software.id as software_id, software.name as software_name, software.version as software_version, software.date_purchase as software_date_purchase, software.date_warranty_expiration as software_date_warranty_expiration';
    $join_options = array(
      array('software', 'license.id = software.license_id', 'LEFT')
    );
    $licenses = $this->read_with_join($select, $join_options, $where, $limit, $offset, $orders, $likes);
    $output = array();
    foreach ($licenses as $license) {
      if (!array_key_exists($license->id, $output)) {
        $license->softwares = [];
        if ($license->software_id) {
          $personnel_ids = $this->read_from_table('software_personnel', 'personnel_id', array('software_id' => $license->software_id));
          $personnel_ids = array_map(fn ($value): int => $value->personnel_id, $personnel_ids);
          $license->softwares[] = array('id' => $license->software_id, 'name' => $license->software_name, 'version' => $license->software_version, 'date_purchase' => $license->software_date_purchase, 'date_warranty_expiration' => $license->software_date_warranty_expiration, 'license_id' => $license->id, 'personnel_ids' => $personnel_ids);
        }
        unset($license->software_id);
        unset($license->software_name);
        unset($license->software_version);
        unset($license->software_date_purchase);
        unset($license->software_date_warranty_expiration);
        $output[$license->id] = $license;
      } else {
        if ($license->software_id) {
          $personnel_ids = $this->read_from_table('software_personnel', 'personnel_id', array('software_id' => $license->software_id));
          $personnel_ids = array_map(fn ($value): int => $value->personnel_id, $personnel_ids);
          $output[$license->id]->softwares[] = array('id' => $license->software_id, 'name' => $license->software_name, 'version' => $license->software_version, 'date_purchase' => $license->software_date_purchase, 'date_warranty_expiration' => $license->software_date_warranty_expiration, 'license_id' => $license->id, 'personnel_ids' => $personnel_ids);
        }
      }
    }
    return array_values($output);
  }

  public function delete($where)
  {
    $this->software_manager->update(array('license_id' => $where), array('license_id' => NULL));
    return parent::delete($where);
  }
}
