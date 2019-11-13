<?php

/**
 * Plugin Name: Basic Login logger
 * Plugin URI: https://github.com/rolfen/wp-login-logger
 * Description: Logs successful login and logout events.
 * Version: 1.0
 * Author: Rolf
 * Author URI: http://github.com/rolfen
 */

add_action('wp_login', 'login_hook', 10, 2);
add_action('clear_auth_cookie', 'logout_hook');

/**
 * Get IP of client
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
 * Gets called when user logs in (wp_login action)
 * @param {string} $user_login - The WordPress username of the logged in user
 * @param {object} $user - The WP_User object of the logged in user
 */
function login_hook($user_login, $user) {
	append_to_log(
		array(
			'login',
			get_user_ip(),
			$user_login,
			implode(' ', $user->roles)
		)
	);
}

/**
 * Gets called just before user logs out
 */
function logout_hook() {
	if($user = wp_get_current_user()) {
		append_to_log(
			array(
				'logout',
				get_user_ip(),
				$user->user_login,
				implode(' ', $user->roles)
			)
		);
	}
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