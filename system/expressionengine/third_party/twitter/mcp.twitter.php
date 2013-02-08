<?php
class Twitter_mcp {
	function __construct()
	{
	    $this->EE =& get_instance();

	    $this->EE->cp->set_right_nav(array(
	            'add_twitter'  => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'
	                .AMP.'module=twitter'.AMP.'method=file_browse'
	            ));
	}
	function index()
	{
	    $this->EE->load->library('javascript');
	    $this->EE->load->library('table');
	    $this->EE->load->helper('form');

	    $this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('twitter_module_name'));

	    $vars['action_url'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=twitter'.AMP.'method=edit_twitters';
	    $vars['form_hidden'] = NULL;
	    $vars['files'] = array();

	    $vars['options'] = array(
	        'edit'  => lang('edit_selected'),
	        'delete'    => lang('delete_selected')
	    );
	    if ( ! $rownum = $this->EE->input->get_post('rownum'))
		{
		    $rownum = 0;
		}

		$query = $this->EE->db->get('twitter_usernames', $this->perpage, $rownum);
		// get all member groups for the dropdown list
		$member_groups = $this->EE->member_model->get_member_groups();

		foreach($member_groups->result() as $group)
		{
		    $member_group[$group->group_id] = $group->group_title;
		}

		foreach($query->result_array() as $row)
		{
		    $vars['twitter'][$row['username_id']]['twitter_id'] = $row['twitter_id'];
		    $vars['twitter'][$row['username_id']]['user_id'] = $row['user_id'];
		    
		    // Toggle checkbox
		    $vars['twitter'][$row['username_id']]['toggle'] = array(
		        'name'      => 'toggle[]',
		        'id'        => 'edit_box_'.$row['username_id'],
		        'value'     => $row['twitter_id'],
		        'class'     =>'toggle'
		    );
		}
	}
}