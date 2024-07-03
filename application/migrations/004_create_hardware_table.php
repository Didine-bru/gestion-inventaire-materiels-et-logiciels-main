<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_hardware_table extends CI_Migration {
  private $table;

  public function __construct()
  {
    parent::__construct();
    $this->load->dbforge();
    $this->table = 'hardware';
  }

  public function up()
  {
    // Drop table 'users' if it exists
		$this->dbforge->drop_table($this->table, TRUE);

    $fields = array(
      'id' => array(
        'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
      ),
      'serial_number' => array(
        'type'        => 'VARCHAR',
        'constraint'  => '45',
        'unique' => TRUE
      ),
      'model' => [
				'type'       => 'VARCHAR',
				'constraint' => '50',
			],
      'color' => [
				'type'       => 'VARCHAR',
				'constraint' => '30',
			],
      'category' => [
				'type'       => 'VARCHAR',
				'constraint' => '60',
			],
      'manufacturer' => [
				'type'       => 'VARCHAR',
				'constraint' => '60',
			],
      'date_purchase' => [
        'type' => 'DATE'
      ],
      'date_warranty_expiration' => [
        'type' => 'DATE'
      ],
      'under_maintenance' => [
        'type' => 'TINYINT',
        'constraint' => '1',
        'default' => '0',
        'null'       => TRUE,
      ],
      'personnel_id' => [
        'type'       => 'MEDIUMINT',
				'constraint' => '8',
				'unsigned'   => TRUE,
        'null'       => TRUE,
      ],
      'date_movement' => [
        'type' => 'DATE',
        'null'       => TRUE,
      ],
    );

    $this->dbforge->add_field($fields);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->table, TRUE);     
  }

  public function down()
  {
    $this->dbforge->drop_table($this->table, TRUE);
  }
}