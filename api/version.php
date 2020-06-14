<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.1
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

define('access', 'api');
header('Content-Type: application/json');

try {
	
	// Load WebEngine
	if(!@include_once(rtrim(str_replace('\\','/', dirname(__DIR__)), '/') . '/includes/webengine.php')) throw new Exception('Could not load WebEngine.');
	
	// Apache Version
	if(!function_exists('apache_get_version')) {
		function apache_get_version() {
			if(!isset($_SERVER['SERVER_SOFTWARE']) || strlen($_SERVER['SERVER_SOFTWARE']) == 0) {
				return '';
			}
			return $_SERVER['SERVER_SOFTWARE'];
		}
	}
	
	// Listener
	$handler = new Handler();
	$handler->versionApiListener();
	
	// Response
	http_response_code(200);
	echo json_encode(array('code' => 200, 'apache' => apache_get_version(), 'php' => phpversion(), 'webengine' => __WEBENGINE_VERSION__), JSON_PRETTY_PRINT);
	
} catch(Exception $ex) {
	http_response_code(500);
	echo json_encode(array('code' => 500, 'error' => $ex->getMessage()), JSON_PRETTY_PRINT);
}