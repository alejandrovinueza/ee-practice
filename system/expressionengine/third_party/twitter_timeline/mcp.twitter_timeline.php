<?php if (! defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
class Twitter_timeline_mcp
{
    private $data = array();

    public function __construct()
    {
        $this->EE       =& get_instance();
        $this->site_id  = $this->EE->config->item('site_id');
        $this->base_url = BASE . AMP . 'C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=twitter_timeline';

        // load table lib for control panel
        $this->EE->load->library('table');
        $this->EE->load->helper('form');

        // Set page title
        $this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('twitter_timeline_module_name'));
    }