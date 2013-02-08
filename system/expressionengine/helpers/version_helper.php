<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
  *  Get version info
  */
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
	
	// --------------------------------------------------------------------

	/**
	 * Check EE Version Cache.
	 *
	 * @access	private
	 * @return	bool|string
	 */
	function _check_version_cache()
	{
		$EE =& get_instance();
		$EE->load->helper('file');
		
		// check cache first
		$cache_expire = 60 * 60 * 24;	// only do this once per day
		$contents = read_file(APPPATH.'cache/ee_version/current_version');

		if ($contents !== FALSE)
		{
			$details = unserialize($contents);

			if (($details['timestamp'] + $cache_expire) > $EE->localize->now)
			{
				return $details['data'];
			}
			else
			{
				return FALSE;
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Write EE Version Cache
	 *
	 * @param array - details of version needed to be cached.
	 * @return void
	 */
	function _write_version_cache($details)
	{
		$EE =& get_instance();
		$EE->load->helper('file');
		
		if ( ! is_dir(APPPATH.'cache/ee_version'))
		{
			mkdir(APPPATH.'cache/ee_version', DIR_WRITE_MODE);
			@chmod(APPPATH.'cache/ee_version', DIR_WRITE_MODE);	
		}
		
		$data = array(
				'timestamp'	=> $EE->localize->now,
				'data' 		=> $details
			);

		if (write_file(APPPATH.'cache/ee_version/current_version', serialize($data)))
		{
			@chmod(APPPATH.'cache/ee_version/current_version', FILE_WRITE_MODE);			
		}		
	}


/* End of file version_helper.php */
/* Location: ./system/expressionengine/helpers/version_helper.php */