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
 * ExpressionEngine ExpressionEngine Info Accessory
 *
 * @package		ExpressionEngine
 * @subpackage	Control Panel
 * @category	Accessories
 * @author		ExpressionEngine Dev Team
 * @link		http://expressionengine.com
 */
class Expressionengine_info_acc {

	var $name			= 'ExpressionEngine Info';
	var $id				= 'expressionengine_info';
	var $version		= '1.0';
	var $description	= 'Links and Information about ExpressionEngine';
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
		$this->EE->lang->loadfile('expressionengine_info');
		
		// localize Accessory display name
		$this->name = $this->EE->lang->line('expressionengine_info');
		
		// set the sections
		$this->sections[$this->EE->lang->line('resources')] = $this->_fetch_resources();
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch Resources
	 *
	 * @access	public
	 * @return	string
	 */
	function _fetch_resources()
	{
		return '
		<ul>
			<li><a href="'.$this->EE->cp->masked_url('http://dereferer.ws/?http://expressionengine.com').'" title="ExpressionEngine.com">ExpressionEngine.com</a></li>
			<li><a href="'.$this->EE->cp->masked_url('http://dereferer.ws/?http://expressionengine.com/user_guide').'">'.$this->EE->lang->line('documentation').'</a></li>
			<li><a href="'.$this->EE->cp->masked_url('http://dereferer.ws/?http://expressionengine.com/forums').'">'.$this->EE->lang->line('support_forums').'</a></li>
			<li><a href="'.$this->EE->cp->masked_url('http://dereferer.ws/?https://secure.expressionengine.com/download.php').'">'.$this->EE->lang->line('downloads').'</a></li>
			<li><a href="'.$this->EE->cp->masked_url('http://dereferer.ws/?http://expressionengine.com/support').'">'.$this->EE->lang->line('support_resources').'</a></li>
		</ul>
		';
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch Version
	 *
	 * @access	public
	 * @return	string
	 */

}
// END CLASS

/* End of file acc.expressionengine_info.php */
/* Location: ./system/expressionengine/accessories/acc.expressionengine_info.php */