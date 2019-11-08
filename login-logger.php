<?php

/**
 * Plugin Name: My Plugin
 * Plugin URI: http://www.mywebsite.com/
 * Description: Login logging plugin.
 * Version: 1.0
 * Author: Your Name
 * Author URI: http://github.com/rolfen
 */

add_action('wp_login', 'log_details');


function get_user_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}
 
function log_details() {
	error_log(get_user_ip());
    //do stuff
}

/**
 Sources:
 - https://www.wpbeginner.com/wp-tutorials/how-to-display-a-users-ip-address-in-wordpress/
 - https://wordpress.stackexchange.com/questions/28522/is-there-a-hook-that-runs-after-a-user-logs-in
*/

?>