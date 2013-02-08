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
 * ExpressionEngine Learning Accessory
 *
 * @package		ExpressionEngine
 * @subpackage	Control Panel
 * @category	Accessories
 * @author		ExpressionEngine Dev Team
 * @link		http://expressionengine.com
 */
class Learning_acc {
	
	var $name			= 'Learning EE';
	var $id				= 'learningEE';
	var $version		= '1.0';
	var $description	= 'Educational Resources for ExpressionEngine';
	var $sections		= array();
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->EE =& get_instance();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set Sections
	 *
	 * Set content for the accessory
	 *
	 * @access	public
	 * @return	void
	 */
	function set_sections()
	{
		$this->sections = array(
			
			$this->EE->lang->line('community_tutorials')	 => 	'<ul>
						<li>'.$this->EE->lang->line('train_ee').'</li>
						<li>'.$this->EE->lang->line('ee_screencasts').'</li>
						<li>'.$this->EE->lang->line('ee_seach_bookmarklet').'</li>
					</ul>'
						,
						
			$this->EE->lang->line('community_resources') => '<ul>
						<li>'.$this->EE->lang->line('ee_insider').'</li>
						<li>'.$this->EE->lang->line('devot_ee').'</li>
						<li>'.$this->EE->lang->line('ee_podcast').'</li>
					</ul>
			',
			$this->EE->lang->line('support') => '<ul>
						<li>'.$this->EE->lang->line('documentation').'</li>
						<li>'.$this->EE->lang->line('support_forums').'</li>
						<li>'.$this->EE->lang->line('wiki').'</li>
					</ul>'			
		);
	}

	// --------------------------------------------------------------------

}
// END CLASS

/* End of file acc.learning.php */
/* Location: ./system/expressionengine/accessories/acc.learning.php */