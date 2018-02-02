<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.0.9.9
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2013-2018 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * https://opensource.org/licenses/MIT
 */

// access
define('access', 'cron');

// Load WebEngine
if(!@include_once(str_replace('\\','/',dirname(dirname(__FILE__))).'/' . 'webengine.php')) die('Failed to load WebEngine CMS.');

// Load Cache Data
$cacheDATA = LoadCacheData('cron.cache');

foreach($cacheDATA as $key => $thisCRON) {
	if($key != 0) {
		$cron = array(
			'id' => $thisCRON[0],
			'file' => $thisCRON[3],
			'run_time' => $thisCRON[4],
			'last_run' => $thisCRON[5],
			'status' => $thisCRON[6]
		);
		
		if($cron['status'] == 1) {
			if(!check_value($cron['last_run'])) {
				$lrtime = $cron['run_time'];
			} else {
				$lrtime = $cron['last_run']+$cron['run_time'];
			}
			if(time() > $lrtime) {
				$filePath = __PATH_CRON__.$cron['file'];
				if(file_exists($filePath)) {
					debug('[Run] ' . $thisCRON[1]);
					include($filePath);
					debug('<-- Done');
				}
			}
		}
	}
}
