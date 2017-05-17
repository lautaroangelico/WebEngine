<?php
/**
 * WebEngine
 * http://muengine.net/
 * 
 * @version 1.0.9
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2017 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

// File Name
$file_name = basename(__FILE__);

// Build Directory Path
$dir_path = str_replace('\\','/',dirname(dirname(__FILE__))).'/';

// Load WebEngine
include($dir_path . 'webengine.php');

// Load Rankings Class
$Rankings = new Rankings();

// Load Ranking Configs
loadModuleConfigs('rankings');

if(mconfig('active')) {
	if(mconfig('rankings_enable_gens')) {
		$Rankings->UpdateRankingCache('gens');
	}
}

// UPDATE CRON
updateCronLastRun($file_name);