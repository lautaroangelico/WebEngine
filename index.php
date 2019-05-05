<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2019 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

# Define CMS access
define('access', 'index');

try {
	
	# Load WebEngine
	if(!@include_once('includes/webengine.php')) throw new Exception('Could not load WebEngine CMS.');
	
} catch (Exception $ex) {
	ob_clean();
	$errorPage = file_get_contents('includes/error.html');
	echo str_replace("{ERROR_MESSAGE}", $ex->getMessage(), $errorPage);
	
}
