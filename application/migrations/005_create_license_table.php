<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_license_table extends CI_Migration {
  private $table;

  public function __construct()
  {
    parent::__construct();
    $this->load->dbforge();
    $this->table = 'license';
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
      'license_number' => array(
        'type'        => 'VARCHAR',
        'constraint'  => '45',
        'unique' => TRUE,
      ),
      'number_of_licenses' => array(
        'type'        => 'INT',
        'constraint'  => '8',
        'default' => '-1'
      ),
      'date_expiration' => [
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