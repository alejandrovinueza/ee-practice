<?php if (! defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
class Twitter_timeline
{
    var $return_data = '';
    var $api_version = '1';
    var $base_url = 'https://api.twitter.com/1/statuses/user_timeline.json';
    var $cache_name = 'twitter_timeline_cache';
    var $cache_expired = FALSE;
    var $rate_limit_hit = FALSE;
    var $refresh = 45; // Period between cache refreshes, in minutes (purposely high default to prevent hitting twitter's rate limit on shared IPs - be careful)
    var $limit = 20;
    var $parameters = array();
    var $months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    var $entities = array('user_mentions' => FALSE, 'urls' => FALSE, 'hashtags' => FALSE, 'media' => FALSE);
    var $use_stale;
    var $screen_name = '';
    var $timeout = 10;
    var $useragent = 'Twitter Timeline';

    function __construct()
    {
        $this->EE =& get_instance();
    }
    function feed()
    {
	    $this->EE->load->library('typography');
	    $this->EE->typography->initialize();
	    $this->EE->typography->parse_images = TRUE;
	    $this->EE->typography->allow_headings = FALSE;

	    $connection = curl_init();
	    $this->screen_name=$this->EE->TMPL->fetch_param('screen_name');
        $url = $this->base_url.'?include_entities=true&include_rts=false&exclude_replies=true&screen_name='.$this->screen_name.'&count='.$this->limit;

    	curl_setopt($connection, CURLOPT_URL, $url);
        curl_setopt($connection, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($connection, CURLOPT_HTTPGET, 1);
        curl_setopt($connection, CURLOPT_HEADER, false);
        curl_setopt($connection, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($connection);

        $http_code = curl_getinfo($connection, CURLINFO_HTTP_CODE);

        curl_close($connection);

    	$tweets = json_decode($response, true);

    	foreach ($tweets as $tweet) {
    		$variables[] = array(
			   	'id' => $tweet['id'],
			    'text' => $tweet['text'],
			    'screen_name' => $tweet['user']['screen_name'],
			    'profile_image_url' => $tweet['user']['profile_image_url'],
			    'created_at' => $tweet['created_at']
			);
    	}

        $output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $variables);

    	return $output;
    }
}

/* End of File: mod.twitter_timeline.php */