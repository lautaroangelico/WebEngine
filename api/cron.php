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

// access
define('access', 'api');

try {
	
	// WebEngine CMS
	if(!@include_once(rtrim(str_replace('\\','/', dirname(__DIR__)), '/') . '/includes/webengine.php')) throw new Exception('Could not load WebEngine CMS.');
	
	// Check Status
	if(config('cron_api',true) == false) throw new Exception('Cron api disabled.');
	if(!check_value(config('cron_api_key',true))) throw new Exception('Configured cron api key is not valid.');
	
	// Check Key
	if(!check_value($_REQUEST['key'])) throw new Exception('Key is not valid.');
	if($_REQUEST['key'] != config('cron_api_key',true)) throw new Exception('Key is not valid.');
	
	// Cron List
	$cronList = getCronList();
	if(!is_array($cronList)) throw new Exception('There are no crons.');
	
	// Encapsulation
	function loadCronFile($path) {
		include($path);
	}

	// Execute Crons
	foreach($cronList as $cron) {
		if($cron['cron_status'] != 1) continue;
		if(!check_value($cron['cron_last_run'])) {
			$lastRun = $cron['cron_run_time'];
		} else {
			$lastRun = $cron['cron_last_run']+$cron['cron_run_time'];
		}
		if(time() > $lastRun) {
			$filePath = __PATH_CRON__.$cron['cron_file_run'];
			if(file_exists($filePath)) {
				loadCronFile($filePath);
				$executedCrons[] = $cron['cron_file_run'];
			}
		}
	}
	
	http_response_code(200);
	header('Content-Type: application/json');
	echo json_encode(array('code' => 200, 'message' => 'Crons successfully executed.', 'executed' => $executedCrons));
	
} catch(Exception $ex) {
	http_response_code(500);
	header('Content-Type: application/json');
	echo json_encode(array('code' => 500, 'error' => $ex->getMessage()));
}