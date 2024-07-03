<?php defined('BASEPATH') or exit('No direct script access allowed');

class Administrator_Model extends Ion_auth_model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function items($where = array(), $limit = NULL, $offset = 1, $orders = array(),  $likes = array(), $groups = ['admin', 'members'])
	{
		$this->select($this->tables['users'] . ".id, first_name, last_name, email, username as identity, " . $this->tables['groups'] . '.name as group_name' );
		if (!empty($where)) {
			$this->where($where);
		}
		if (!empty($$offset)) {
			$this->offset($$offset);
		}
		if (!empty($limit)) {
			$this->limit($limit);
		}
		if (!empty($orders)) {
			$this->order_by($orders);
		}
		if (!empty($likes)) {
			foreach($likes as $key => $value) {
				// $this->like($key, $value);
				$this->db->or_like($key, $value, 'both');
			}
		}

		$users = $this->users($groups)->result();
		$output = array();
		foreach($users as $user) {
			if (!array_key_exists($user->id, $output)) {
				$output[$user->id] = $user;
				$output[$user->id]->groups = array($user->group_name);
				unset($output[$user->id]->group_name);
			} else {
				$output[$user->id]->groups[] = $user->group_name;
			}
		}
		$output_array = array();
    foreach($output as $key => $value) {
      $output_array[] = $value;
    }
    return $output_array;
	}
}
