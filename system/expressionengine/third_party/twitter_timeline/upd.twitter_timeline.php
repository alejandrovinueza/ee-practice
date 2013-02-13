<?php
class Twitter_timeline_upd
{
    public $version = '1.0';

    public function __construct()
    {
        $this->EE      =& get_instance();
        $this->site_id = $this->EE->config->item('site_id');
    }

    public function install()
    {
        $this->EE->db->insert('modules', array(
                                              'module_name'        => 'Twitter_timeline',
                                              'module_version'     => $this->version,
                                              'has_cp_backend'     => 'n',
                                              'has_publish_fields' => 'n'
                                         ));

   

        return TRUE;
    }

    public function update($current = '')
    {
        if ($current == $this->version)
        {
            return FALSE;
        }
        return TRUE;
    }

    public function uninstall()
    {
        $this->EE->load->dbforge();

        $this->EE->db->query("DELETE FROM exp_modules WHERE module_name = 'Tgl_Twitter'");


        return TRUE;
    }
}

/* End of File: upd.twitter_timeline.php */