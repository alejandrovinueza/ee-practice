<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Twitter_upd {

    var $version = '1.0';

    function __construct()
    {
        // Make a local reference to the ExpressionEngine super object
        $this->EE =& get_instance();
    }
    function install()
	{
	    $this->EE->load->dbforge();

	    $data = array(
	        'module_name' => 'Twitter' ,
	        'module_version' => $this->version,
	        'has_cp_backend' => 'n',
	        'has_publish_fields' => 'y'
	    );

	    $this->EE->db->insert('modules', $data);

	    $data = array(
    		'class'     => 'Twitter' ,
    		'method'    => 'feed'
		);

		$this->EE->db->insert('actions', $data);

		$action_id  = $this->EE->functions->fetch_action_id('Twitter', 'feed');

		$fields = array(
			'username_id'   => array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE, 'auto_increment' => TRUE),
		    'twitter_id'   => array('type' => 'int', 'constraint' => '20'),
		    'user_id' => array('type' => 'int', 'constraint' => '10')
		);

		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('username_id', TRUE);

		$this->EE->dbforge->create_table('twitter_usernames');

		$this->EE->load->library('layout');
	    $this->EE->layout->add_layout_tabs($this->tabs(), 'twitter');

	    return TRUE;
	}
	function uninstall()
	{
	    $this->EE->load->dbforge();

	    $this->EE->db->select('module_id');
	    $query = $this->EE->db->get_where('modules', array('module_name' => 'Twitter'));

	    $this->EE->db->where('module_id', $query->row('module_id'));
	    $this->EE->db->delete('module_member_groups');

	    $this->EE->db->where('module_name', 'Twitter');
	    $this->EE->db->delete('modules');

	    $this->EE->db->where('class', 'Twitter');
	    $this->EE->db->delete('actions');

	    $this->EE->dbforge->drop_table('twitter_usernames');

	    // Required if your module includes fields on the publish page
	    $this->EE->load->library('layout');
	    $this->EE->layout->delete_layout_tabs($this->tabs(), 'twitter');

	    return TRUE;
	}
	function update($current = '')
	{
	    return FALSE;
	}
	function tabs()
	{
	    $tabs['twitter'] = array(
	        'twitter_field_ids'    => array(
	                    'visible'   => 'true',
	                    'collapse'  => 'false',
	                    'htmlbuttons'   => 'false',
	                    'width'     => '100%'
	                    )
	        );

	    return $tabs;
	}
}