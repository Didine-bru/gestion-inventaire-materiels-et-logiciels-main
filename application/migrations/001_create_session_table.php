<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_session_table extends CI_Migration {
  private $table;

  public function __construct()
  {
    parent::__construct();
    $this->load->dbforge();
    $this->load->config('config', TRUE);
    $this->table = $this->config->item('sess_save_path');
  }

  public function up()
  {
    // Drop table 'users' if it exists
		$this->dbforge->drop_table($this->table, TRUE);

    $fields = array(
      'id' => array(
        'type'       => 'VARCHAR',
        'constraint' => 40,
      ),
      'ip_address' => array(
        'type'        => 'VARCHAR',
        'constraint'  => 45
      ),
      'timestamp' => array(
        'type'        => 'INT',
        'constraint'  => 10,
        'unsigned'    => TRUE,
        'default'     => '0'
      ),
      'data' => array(
        'type' => 'BLOB'
      )
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