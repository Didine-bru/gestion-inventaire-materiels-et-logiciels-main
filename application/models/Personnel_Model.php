<?php defined('BASEPATH') or exit('No direct script access allowed');

class Personnel_Model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table = 'personnel';
		$this->load->model('Hardware_Model', 'hardware_manager');
	}

	public function items($where = array(), $limit = NULL, $offset = NULL, $orders = array(),  $likes = array())
	{
		$select = 'personnel.*';
		$select .= ', hardware.id as hardware_id, hardware.serial_number as hardware_serial_number, hardware.model as hardware_model, hardware.color as hardware_color, hardware.category as hardware_category, hardware.manufacturer as hardware_manufacturer, hardware.date_purchase as hardware_date_purchase, hardware.date_warranty_expiration as hardware_date_warranty_expiration, hardware.under_maintenance as hardware_under_maintenance, hardware.date_movement as hardware_date_movement';
		$select .= ', software.id as software_id, software.name as software_name, software.version as software_version, software.date_purchase as software_date_purchase, software.date_warranty_expiration as software_date_warranty_expiration';
		$join_options = array(
			array('hardware', 'personnel.id = hardware.personnel_id', 'LEFT'),
			array('software_personnel', 'personnel.id = software_personnel.personnel_id', 'LEFT'),
			array('software', 'software_personnel.software_id = software.id', 'LEFT')
		);
		$personnels = $this->read_with_join($select, $join_options, $where, $limit, $offset, $orders, $likes);
		$output = array();
		foreach ($personnels as $personnel) {
			if (!array_key_exists($personnel->id, $output)) {
				$personnel->hardwares = [];
				if ($personnel->hardware_id)
					$personnel->hardwares[] = array('id' => $personnel->hardware_id, 'serial_number' => $personnel->hardware_serial_number, 'model' => $personnel->hardware_model, 'color' => $personnel->hardware_color, 'category' => $personnel->hardware_category, 'manufacturer' => $personnel->hardware_manufacturer, 'date_purchase' => $personnel->hardware_date_purchase, 'date_warranty_expiration' => $personnel->hardware_date_warranty_expiration, 'under_maintenance' => $personnel->hardware_under_maintenance, 'personnel_id' => $personnel->id, 'date_movement' => $personnel->hardware_date_movement);
				unset($personnel->hardware_id);
				unset($personnel->hardware_serial_number);
				unset($personnel->hardware_model);
				unset($personnel->hardware_color);
				unset($personnel->hardware_category);
				unset($personnel->hardware_manufacturer);
				unset($personnel->hardware_date_purchase);
				unset($personnel->hardware_date_warranty_expiration);
				unset($personnel->hardware_under_maintenance);
				unset($personnel->hardware_date_movement);

				$personnel->softwares = [];
				if ($personnel->software_id) {
					$personnel_ids = $this->read_from_table('software_personnel', 'personnel_id', array('software_id' => $personnel->software_id));
					$personnel_ids = array_map(fn ($value): int => $value->personnel_id, $personnel_ids);
					$personnel->softwares[] = array('id' => $personnel->software_id, 'name' => $personnel->software_name, 'version' => $personnel->software_version, 'date_purchase' => $personnel->software_date_purchase, 'date_warranty_expiration' => $personnel->software_date_warranty_expiration, 'license_id' => $personnel->id, 'personnel_ids' => $personnel_ids);
				}
				unset($personnel->software_id);
				unset($personnel->software_name);
				unset($personnel->software_version);
				unset($personnel->software_date_purchase);
				unset($personnel->software_date_warranty_expiration);
				$output[$personnel->id] = $personnel;
			} else {
				if ($personnel->hardware_id)
					$output[$personnel->id]->hardwares[] = array('id' => $personnel->hardware_id, 'serial_number' => $personnel->hardware_serial_number, 'model' => $personnel->hardware_model, 'color' => $personnel->hardware_color, 'category' => $personnel->hardware_category, 'manufacturer' => $personnel->hardware_manufacturer, 'date_purchase' => $personnel->hardware_date_purchase, 'date_warranty_expiration' => $personnel->hardware_date_warranty_expiration, 'under_maintenance' => $personnel->hardware_under_maintenance, 'personnel_id' => $personnel->id, 'date_movement' => $personnel->hardware_date_movement);
				if ($personnel->software_id) {
					$personnel_ids = $this->read_from_table('software_personnel', 'personnel_id', array('software_id' => $personnel->software_id));
					$personnel_ids = array_map(fn ($value): int => $value->personnel_id, $personnel_ids);
					$output[$personnel->id]->softwares[] = array('id' => $personnel->software_id, 'name' => $personnel->software_name, 'version' => $personnel->software_version, 'date_purchase' => $personnel->software_date_purchase, 'date_warranty_expiration' => $personnel->software_date_warranty_expiration, 'license_id' => $personnel->id, 'personnel_ids' => $personnel_ids);
				}
			}
		}
		$output_array = array();
		foreach ($output as $key => $value) {
			$value->hardwares = array_unique($value->hardwares, SORT_REGULAR);
			$value->softwares = array_unique($value->softwares, SORT_REGULAR);
			$output_array[] = $value;
		}
		return $output_array;
	}

	public function delete($where)
	{
		$this->hardware_manager->update(array('personnal_id' => $where), array('personnal_id' => NULL));
		$this->delete_into_table('software_personnel', array('personnel_id' => $where));
		return parent::delete($where);
	}
}
