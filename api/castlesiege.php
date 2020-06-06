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

try {
	
	if(!@include_once(rtrim(str_replace('\\','/', dirname(__DIR__)), '/') . '/includes/webengine.php')) throw new Exception('Could not load WebEngine.');
	
	$castleSiege = new CastleSiege();
	$siegeData = $castleSiege->siegeData();
	
	http_response_code(200);
	echo json_encode(
		array(
			'TimeLeft' => $siegeData['warfare_stage_timeleft']
		)
	);

} catch(Exception $ex) {
	http_response_code(500);
	echo json_encode(array('code' => 500, 'error' => $ex->getMessage()));
}