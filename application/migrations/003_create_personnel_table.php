<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_personnel_table extends CI_Migration {
  private $table;

  public function __construct()
  {
    parent::__construct();
    $this->load->dbforge();
    $this->table = 'personnel';
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
      'name' => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
			],
      'departement' => [
				'type'       => 'VARCHAR',
				'constraint' => '40',
			],
      'post' => [
				'type'       => 'VARCHAR',
				'constraint' => '60',
        'null' => TRUE
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