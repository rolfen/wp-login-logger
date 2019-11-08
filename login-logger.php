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

/**
 * Get IP of logged in user
 * @returns {string} IP
 */
function get_user_ip() {
	if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}
 
/**
 * Callback for the wp_login action
 * @param {string} $user_login - The WordPress username of the logged in user
 * @param {object} $user - The WP_User object of the logged in user
 */
function log_details($user_login, $user) {
	append_to_log(
		array(
			get_user_ip(),
			$user_login,
			implode(' ', $user->roles)
		)
	);
}

/**
 * Appends a single-line record to the login log. Fields will be comma-separated
 * The log file will be in the root folder of the plugin.
 * @param {array} $record - The log record as an array of fields
 */
function append_to_log($record) {
	$log_path = plugin_dir_path(__FILE__) . "login.log";
	$file = fopen($log_path, 'a'); 
	fwrite($file, date('Y-m-d h:i:s') . ", " . implode(', ', $record) . "\n"); 
	fclose($file); 
}

?>