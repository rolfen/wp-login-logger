<?php

/**
 * Plugin Name: Basic Login logger
 * Plugin URI: https://github.com/rolfen/wp-login-logger
 * Description: Login logging plugin.
 * Version: 1.0
 * Author: Rolf
 * Author URI: http://github.com/rolfen
 */

add_action('wp_login', 'log_details', 10, 2);

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
 

function log_details($user_login, $user) {
	append_to_log(
		array(
			get_user_ip(),
			$user_login,
			implode(' ', $user->roles)
		)
	);
}

function append_to_log($message) {
	// $message is an array
	$file = fopen("./login.log","a"); 
	fwrite($file, date('Y-m-d h:i:s') . ", " . implode(', ', $message) . "\n"); 
	fclose($file); 
}

?>