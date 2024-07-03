<?php defined('BASEPATH') or exit('No direct script access allowed');

class Hardware_Model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->table = 'hardware';
  }

  public function items($where = array(), $limit = NULL, $offset = NULL, $orders = array(),  $likes = array())
  {
    $select = 'hardware.*';
    $select .= ', personnel.id as personnel_id, personnel.serial_number as personnel_serial_number, personnel.name as personnel_name, personnel.departement as personnel_departement, personnel.post as personnel_post';
    $join_options = array(
      array('personnel', 'hardware.personnel_id = personnel.id', 'LEFT')
    );
    $hardwares = $this->read_with_join($select, $join_options, $where, $limit, $offset, $orders, $likes);
    $output = array();
    foreach ($hardwares as $hardware) {
      if ($hardware->personnel_id) {
        $hardware->personnel = array('id' => $hardware->personnel_id, 'serial_number' => $hardware->personnel_serial_number, 'name' => $hardware->personnel_name, 'departement' => $hardware->personnel_departement, 'post' => $hardware->personnel_post);
      } else {
        $hardware->personnel = NULL;
      }
      unset($hardware->personnel_id);
      unset($hardware->personnel_serial_number);
      unset($hardware->personnel_name);
      unset($hardware->personnel_departement);
      unset($hardware->personnel_post);
      $output[] = $hardware;
    }
    return $output;
  }
}
