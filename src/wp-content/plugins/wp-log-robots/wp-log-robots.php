<?php
/*
Plugin Name: wp-log-robots
Plugin URI: http://www.netroby.com/
Description: loged each robots.
Version: 0.1.1
Author: netroby
Author URI: http://www.netroby.com
License: New BSD License
 */


//Define time stamp
define("TIME_STAMP", time()+ (8* 3600));

//Define max records 
define("MAX_RECORDS", 1000);

//Log file
$log_file = ABSPATH. "robots_log.txt";

//Log file write lock
$log_file_lock = $log_file. ".lock";


//Get old records
function get_old()
{
	global $log_file;

	if(!file_exists($log_file))
	{
		touch($log_file);
		chmod($log_file,0755);
		clearstatcache();

		return NULL;

	}
	else
	{

		$old_log = file_get_contents($log_file);

		$fo = explode("\r\n", substr($old_log, 0, -2));

		$old = "";

		switch(isset($fo[MAX_RECORDS]))
		{
		case TRUE:
			for($i = 0; $i < MAX_RECORDS; $i++)
			{
				$old .= $fo[$i]. "\r\n";

			}
			
			break;

		case FALSE:
			$old = $old_log;

			break;

		}

		unset($fo);
		unset($old_log);

		return $old;

	}
}

//Log robots 
function wp_log_robots()
{
	global $log_file, $log_file_lock;

	if(isset($_SERVER['HTTP_USER_AGENT']) && isset($_SERVER['REQUEST_URI']))
	{
		$is_bot = FALSE;

		if(preg_match("/bot/i", $_SERVER['HTTP_USER_AGENT']) || preg_match("/spider/i", $_SERVER['HTTP_USER_AGENT']))
		{
			$is_bot = TRUE;
		}

		if($is_bot == TRUE)
		{
			//Ipv6 compatible
			$real_ip = preg_replace("/^::ffff:/i", "", $_SERVER['REMOTE_ADDR']);

			//Log format , you could defined it your self
			$str = date("Y-m-d H:i:s", TIME_STAMP). "\t". $real_ip. "\t". $_SERVER['REQUEST_URI']. "\t". $_SERVER['HTTP_USER_AGENT']. "\r\n";

			//Got old records
			$fo = get_old();

			//Check log file write lock if exists
			if(!file_exists($log_file_lock))
			{
				//Create file lock prevent write log twice each time 
				touch($log_file_lock);

				//Puts new & old records together
				file_put_contents($log_file, $str. $fo);



				//Do not forget remove the log file write lock
				unlink($log_file_lock);

				clearstatcache();

				//For debug purpose
				//echo '<!-- ', $_SERVER['HTTP_USER_AGENT'], $_SERVER['REQUEST_URI'], '-->';

			}
			
			unset($real_ip);
			unset($str);
			unset($fo);

		}

		unset($is_bot);

	}
}

//Add to hook while the wordpress init (run once)
add_action("init", 'wp_log_robots');


