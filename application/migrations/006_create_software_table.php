<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_software_table extends CI_Migration {
  private $table;
  private $software_personnel_table;

  public function __construct()
  {
    parent::__construct();
    $this->load->dbforge();
    $this->table = 'software';
    $this->software_personnel_table = 'software_personnel';
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
      'name' => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
			],
      'version' => array(
        'type'        => 'VARCHAR',
        'constraint'  => '40',
      ),
      'date_purchase' => [
        'type' => 'DATE'
      ],
      'date_warranty_expiration' => [
        'type' => 'DATE',
        'null' => TRUE
      ],
      'license_id' => array(
        'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
        'null'          => TRUE
      ),
    );

    $this->dbforge->add_field($fields);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($this->table, TRUE);  
    
    // Drop table 'users_groups' if it exists
		$this->dbforge->drop_table($this->software_personnel_table, TRUE);

		// Table structure for table 'users_groups'
		$this->dbforge->add_field([
			'id' => [
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'software_id' => [
				'type'       => 'MEDIUMINT',
				'constraint' => '8',
				'unsigned'   => TRUE
			],
			'personnel_id' => [
				'type'       => 'MEDIUMINT',
				'constraint' => '8',
				'unsigned'   => TRUE
			]
		]);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table($this->software_personnel_table);
  }

  public function down()
  {
    $this->dbforge->drop_table($this->software_personnel_table, TRUE);
    $this->dbforge->drop_table($this->table, TRUE);
  }
}