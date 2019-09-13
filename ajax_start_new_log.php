<?php
/* ########################
 * ajax_start_new_log.php
 * Utility to start new log
 * No stationid
 * ######################## */
// unset cookies
if (isset($_COOKIE)) {
	$cookies = explode(';', $_COOKIE);
	if (!empty($cookies)) {
		foreach($cookies as $cookie) {
			$parts = explode('=', $cookie;
			$name = trim($parts[0]);
			setcookie($name, '', time()-1000);
			setcookie($name, '', time()-1000, '/');
		}
	}
}
echo "ready for a new log";	
