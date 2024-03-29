<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2010, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * ExpressionEngine CP Home Page Class
 *
 * @package		ExpressionEngine
 * @subpackage	Control Panel
 * @category	Control Panel
 * @author		ExpressionEngine Dev Team
 * @link		http://expressionengine.com
 */
class Homepage extends CI_Controller {

	/**
	 * Index function
	 *
	 * @access	public
	 * @return	void
	 */	
	function index($message = '')
	{	
		$this->cp->get_installed_modules();
		$this->cp->set_variable('cp_page_title', $this->lang->line('main_menu'));

		$version			= FALSE;
		$show_notice		= $this->_checksum_bootstrap_files();
		$allowed_templates	= $this->session->userdata('assigned_template_groups');

		// Notices only show for super admins
		
		$vars = array(
			'version'			=> $version,
			'message'			=> $message,
			'instructions'		=> $this->lang->line('select_channel_to_post_in'),
			'show_page_option'	=> (isset($this->cp->installed_modules['pages'])) ? TRUE : FALSE,
			'info_message_open'	=> ($this->input->cookie('home_msg_state') != 'closed' && $show_notice) ? TRUE : FALSE,
			'no_templates'		=> sprintf($this->lang->line('no_templates_available'), BASE.AMP.'C=design'.AMP.'M=new_template_group'),
			
			'can_access_modify'		=> TRUE,
			'can_access_content'	=> TRUE,
			'can_access_templates'	=> (count($allowed_templates) > 0 && $this->cp->allowed_group('can_access_design')) ? TRUE : FALSE
		);
		
		
		// Pages module is installed, need to check perms
		// to see if the member group can access it.
		// Super admin sees all.
		
		if ($vars['show_page_option'] && $this->session->userdata('group_id') != 1)
		{
			$this->load->model('member_model');
			$vars['show_page_option'] = $this->member_model->can_access_module('pages');
		}
		
		$vars['recent_entries'] = $this->_recent_entries();

		// A few more permission checks
		
		if ( ! $this->cp->allowed_group('can_access_publish'))
		{
			$vars['show_page_option'] = FALSE;
			
			if ( ! $this->cp->allowed_group('can_access_edit') && ! $this->cp->allowed_group('can_admin_templates'))
			{
				$vars['can_access_modify'] = FALSE;
				
				if ( ! $this->cp->allowed_group('can_admin_channels')  && ! $this->cp->allowed_group('can_admin_sites'))
				{
					$vars['can_access_content'] = FALSE;
				}
			}
		}
		
		//  Comment blocks
		$vars['comments_installed']			= $this->db->table_exists('comments');
		$vars['can_moderate_comments']		= $this->cp->allowed_group('can_moderate_comments') ? TRUE : FALSE;
		$vars['comment_validation_count']	= ($vars['comments_installed']) ? $this->_total_validating_comments() : FALSE;	

		// Most recent comment and most recent entry
		$this->load->model('channel_model');

		$vars['cp_recent_ids'] = array(
			'entry'		=> $this->channel_model->get_most_recent_id('entry')
		);

		// Prep js
		
		$this->javascript->set_global('lang.close', $this->lang->line('close'));
		
		if ($show_notice)
		{
			$this->javascript->set_global('importantMessage.state', $vars['info_message_open']);
		}

		$this->cp->add_js_script('file', 'cp/homepage');
		$this->javascript->compile();
		
		$this->load->view('homepage', $vars);
	}


	// --------------------------------------------------------------------
	
	/**
	 *  Get Comments Awaiting Validation
	 *
	 * Gets total number of comments with 'pending' status
	 *
	 * @access	private
	 * @return	integer
	 */
	function _total_validating_comments()
	{  
		$this->db->where('status', 'p');
		$this->db->where('site_id', $this->config->item('site_id'));
		$this->db->from('comments');

		return $this->db->count_all_results();
  	}
  	/* END */


	// --------------------------------------------------------------------
	
	/**
	 *  Get Recent Entries
	 *
	 * Gets total number of comments with 'pending' status
	 *
	 * @access	private
	 * @return	array
	 */
	function _recent_entries()
	{
		$this->load->model('channel_entries_model');
		$entries = array();

		$query = $this->channel_entries_model->get_recent_entries(10);
		
		if ($query && $query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $row)
			{
				$link = '';
				
				if (($row->author_id == $this->session->userdata('member_id')) OR $this->cp->allowed_group('can_edit_other_entries'))
				{
					$link = BASE.AMP.'C=content_publish'.AMP.'M=entry_form'.AMP.'channel_id='.$row->channel_id.AMP.'entry_id='.$row->entry_id;
				}
				

				$link = ($link == '') ? $row->title: '<a href="'.$link.'">'.$row->title.'</a>';
				
				$entries[] = $link;
			}
		}
		
		return $entries;
	}



	// --------------------------------------------------------------------

	/**
	 * Accept Bootstrap Checksum Changes
	 * 
	 * Updates the bootstrap file checksums with the new versions.
	 *
	 * @access	public
	 */
	function accept_checksums()
	{
		if ($this->session->userdata('group_id') != 1)
		{
			show_error($this->lang->line('unauthorized_access'));
		}
		
		$this->load->library('file_integrity');
		$changed = $this->file_integrity->check_bootstrap_files(TRUE);

		if ($changed)
		{
			foreach($changed as $site_id => $paths)
			{
				foreach($paths as $path)
				{
					$this->file_integrity->create_bootstrap_checksum($path, $site_id);
				}
			}
		}
		
		$this->functions->redirect(BASE.AMP.'C=homepage');
	}

	// --------------------------------------------------------------------

	/**
	 * Bootstrap Checksum Validation
	 * 
	 * Creates a checksum for our bootstrap files and checks their
	 * validity with the database
	 *
	 * @access	private
	 */
	function _checksum_bootstrap_files()
	{
		$this->load->library('file_integrity');
		$changed = $this->file_integrity->check_bootstrap_files();

		if ($changed)
		{
			// Email the webmaster - if he isn't already looking at the message
			
			if ($this->session->userdata('email') != $this->config->item('webmaster_email'))
			{
				$this->file_integrity->send_site_admin_warning($changed);
			}
			
			if ($this->session->userdata('group_id') == 1)
			{
				$this->load->vars(array('new_checksums' => $changed));
				return TRUE;
			}
		}
		
		return FALSE;
	}
	// --------------------------------------------------------------------

	/**
	 * EE Version Check function
	 * 
	 * Requests a file from ExpressionEngine.com that informs us what the current available version
	 * of ExpressionEngine.
	 *
	 * @access	private
	 * @return	bool|string
	 */	

}

/* End of file homepage.php */
/* Location: ./system/expressionengine/controllers/cp/homepage.php */