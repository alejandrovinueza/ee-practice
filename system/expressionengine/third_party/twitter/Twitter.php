<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Twitter {

    var $return_data    = '';

    function __construct()
    {
        // Make a local reference to the ExpressionEngine super object
        $this->EE =& get_instance();
    }
    function feed()
	{
		$api='https://api.twitter.com/1/statuses/user_timeline.json';
      	$number=20;
      	$connection = curl_init();
      	$url = $api.'?include_entities=true&include_rts=false&exclude_replies=true&screen_name='.$this->EE->TMPL->fetch_param('username').'&count='.$number;
      
     	curl_setopt($connection, CURLOPT_URL, $url);
      	curl_setopt($connection, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      	curl_setopt($connection, CURLOPT_HTTPGET, 1);
      	curl_setopt($connection, CURLOPT_HEADER, false);
      	curl_setopt($connection, CURLOPT_HTTPHEADER, array('Accept: application/json'));
      	curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);

      	$response = curl_exec($connection);
      	curl_close($connection);


      	$variables[] = array(
            'json' => json_decode($response, true);
           );

    	$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $variables);

    	return $output;

	}
}
