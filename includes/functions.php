<?php
/**
 * WebEngine
 * http://muengine.net/
 * 
 * @version 1.0.9.4
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2017 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

function check_value($value) {
	if((@count($value)>0 and !@empty($value) and @isset($value)) || $value=='0') {
		return true;
	}
}

function redirect($type = 1, $location = null, $delay = 0) {
	if(!check_value($location)) {
		$to = __BASE_URL__;
	} else {
		$to = __BASE_URL__ . $location;
		
		if($location == 'login') {
			$_SESSION['login_last_location'] = $_REQUEST['page'].'/';
			if(check_value($_REQUEST['subpage'])) {
				$_SESSION['login_last_location'] .= $_REQUEST['subpage'].'/';
			}
		}
	}

	switch($type) {
		default:
			header('Location: '.$to.'');
			die();
		break;
		case 1:
			header('Location: '.$to.'');
			die();
		break;
		case 2:
			echo '<meta http-equiv="REFRESH" content="'.$delay.';url='.$to.'">';
		break;
		case 3:
			header('Location: '.$location.'');
			die();
		break;
	}
}

function isLoggedIn() {
	$login = new login();
	if($login->isLoggedIN()) return true;
	return;
}

function logOutUser() {
	$login = new login();
	$login->logout();
}

function message($type='info', $message="", $title="") {
	switch($type) {
		case 'error':
			$class = ' alert-danger';
		break;
		case 'success':
			$class = ' alert-success';
		break;
		case 'warning':
			$class = ' alert-warning';
		break;
		default:
			$class = ' alert-info';
		break;
	}
	
	if(check_value($title)) {
		echo '<div class="alert'.$class.'" role="alert"><strong>'.$title.'</strong><br />'.$message.'</div>';
	} else {
		echo '<div class="alert'.$class.'" role="alert">'.$message.'</div>';
	}
}

function lang($phrase, $return=true) {
	global $lang;
	$result = $lang[$phrase];
	if(!$result) $result = 'ERROR';
	
	if(config('language_debug',true)) {
		if($return) {
			return '<span title="'.$phrase.'" alt="'.$phrase.'">'.$result.'</span>';
		} else {
			echo '<span title="'.$phrase.'" alt="'.$phrase.'">'.$result.'</span>';
		}
	} else {
		if($return) {
			return $result;
		} else {
			echo $result;
		}
	}
}

function langf($phrase, $args=array(), $print=false) {
	global $lang;
	$result = @vsprintf($lang[$phrase], $args);
	if(!$result) $result = 'ERROR';
	
	if(config('language_debug',true)) {
		if($print) {
			echo '<span title="'.$phrase.'" alt="'.$phrase.'">'.$result.'</span>';
		} else {
			return '<span title="'.$phrase.'" alt="'.$phrase.'">'.$result.'</span>';
		}
	} else {
		if($print) {
			echo $result;
		} else {
			return $result;
		}
	}
}

# to be removed
function Encode($txt) {
	return $txt;
}

# to be removed
function Decode($txt) {
	return $txt;
}

function debug($value) {
	echo '<pre>';
		print_r($value);
	echo '</pre>';
}

function canAccessAdminCP($username) {
	if(!check_value($username)) return;
	if(array_key_exists($username, config('admins',true))) return true;
	return false;
}

function webengine_id($var, $action='encode') {
	$base_chars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	
	for ($n = 0; $n<strlen($base_chars); $n++) {
		$i[] = substr( $base_chars,$n ,1);
    }
 
    $passhash = hash('sha256',config('encryption_hash',true));
    $passhash = (strlen($passhash) < strlen($base_chars)) ? hash('sha512',config('encryption_hash',true)) : $passhash;
 
    for ($n=0; $n < strlen($base_chars); $n++) {
		$p[] =  substr($passhash, $n ,1);
    }
 
    array_multisort($p, SORT_DESC, $i);
    $base_chars = implode($i);
	
	switch($action) {
		case 'encode':
			$string = '';
			$len = strlen($base_chars);
			while($var >= $len) {
				$mod = bcmod($var, $len);
				$var = bcdiv($var, $len);
				$string = $base_chars[$mod].$string;
			}
			return $base_chars[$var] . $string;
		break;
		case 'decode':
			$integer = 0;
			$var = strrev($var );
			$baselen = strlen( $base_chars );
			$inputlen = strlen( $var );
			for ($i = 0; $i < $inputlen; $i++) {
				$index = strpos($base_chars, $var[$i] );
				$integer = bcadd($integer, bcmul($index, bcpow($baselen, $i)));
			}
			return $integer;
		break;
	}
}

function Encode_id($id) {
	return webengine_id($id,'encode');
}

function Decode_id($id) {
	return webengine_id($id,'decode');
}

function BuildCacheData($data_array) {
	$result = null;
	if(is_array($data_array)) {
		foreach($data_array as $row) {
			$count = count($row);
			$i = 1;
			foreach($row as $data) {
				$result .= $data;
				if($i < $count) {
					$result .= '¦';
				}
				$i++;
			}
			$result .= "\n";
		}
		return $result;
	} else {
		return null;
	}
}

function UpdateCache($file_name, $data) {
	$file = __PATH_CACHE__.$file_name;
	if(!file_exists($file)) return;
	if(!is_writable($file)) return;
	
	$fp = fopen($file, 'w');
	fwrite($fp, time()."\n");
	fwrite($fp, $data);
	fclose($fp);
	return true;
}

function LoadCacheData($file_name) {
	$file = __PATH_CACHE__.$file_name;
	if(!file_exists($file)) return;
	if(!is_readable($file)) return;
	
	$cache_file = file_get_contents($file);
	$file_lanes = explode("\n",$cache_file);
	$nlines = count($file_lanes);
	for($i=0; $i<$nlines; $i++) {
		if(check_value($file_lanes[$i])) {
			$line_data[$i] = explode("¦",$file_lanes[$i]);
		}
	}
	return $line_data;
}

function sec_to_hms($input_seconds=0) {
	$result = sec_to_dhms($input_seconds);
	if(!is_array($result)) return array(0,0,0);
	return array((($result[0]*24)+$result[1]), $result[2], $result[3]);
}

function sec_to_dhms($input_seconds=0) {
	if($input_seconds < 1) return array(0,0,0,0);
	$days_module = $input_seconds % 86400;
	$days = ($input_seconds-$days_module)/86400;
	$hours_module = $days_module % 3600;
	$hours = ($days_module-$hours_module)/3600;
	$minutes_module = $hours_module % 60;
	$minutes = ($hours_module-$minutes_module)/60;
	$seconds = $minutes_module;
	return array($days,$hours,$minutes,$seconds);
}

/*
 * Calculates the exact time left before the next Castle Siege battle.
 * Configs:
 * 		- cs_battle_day: Values: 1(Monday) to 7(Sunday)
 * 		- cs_battle_time: Value: h:m:s (in 24 hour format!)
 * 		- cs_battle_duration: Value: numeric (time in minutes!)
 * 
*/
function cs_CalculateTimeLeft() {
	loadModuleConfigs('castlesiege');
	$weekDays = array("", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
	$battleDay = $weekDays[mconfig('cs_battle_day')];
	$today = date("l");
	$battleTime = mconfig('cs_battle_time');
	$battleDate = strtotime("next $battleDay $battleTime");
	$timeOffset = $battleDate - time();
	if($today == $battleDay) {
		$currentTime = strtotime(date("H:i:s"));
		$battleTimeToday = strtotime($battleTime);
		$timeOffsetToday = $battleTimeToday - time();
		if($battleTimeToday > $currentTime) {
			// CS BATTLE IS TODAY
			return $timeOffsetToday;
		} else {
			$timeOffsetToday = $timeOffsetToday*(-1);
			if((mconfig('cs_battle_duration')*60) > $timeOffsetToday) {
				// CS BATTLE IN PROGRESS
				return;
			} else {
				// CS BATTLE IS ON NEXT DATE
				return $timeOffset;
			}
		}
	} else {
		// CS BATTLE IS ON NEXT DATE
		return $timeOffset;
	}
}

function listCronFiles($selected="") {
	$dir = opendir(__PATH_CRON__);
	while(($file = readdir($dir)) !== false) {
		if(filetype(__PATH_CRON__ . $file) == "file" && $file != ".htaccess" && $file != "cron.php") {
			
			if(check_value($selected) && $selected == $file) {
				$return[] = "<option value=\"$file\" selected=\"selected\">$file</option>";
			} else {
				$return[] = "<option value=\"$file\">$file</option>";
			}
		}
	}
	closedir($dir);
	return join('', $return);
}

function cronFileAlreadyExists($cron_file) {
	global $dB;
	$check = $dB->query_fetch_single("SELECT * FROM WEBENGINE_CRON WHERE cron_file_run = '$cron_file'");
	if(!is_array($check)) {
		return true;
	}
}

function addCron($cron_times) {
	global $dB;
	if(check_value($_POST['cron_name']) && check_value($_POST['cron_file']) && check_value($_POST['cron_time'])) {
		
		$filePath = __PATH_CRON__.$_POST['cron_file'];

		// Check Cron File Exists
		if(!file_exists($filePath)) {
			message('error','The selected file doesn\'t exist.');
			return;
		}
		// Check Cron File Databse
		if(!cronFileAlreadyExists($_POST['cron_file'])) {
			message('error','A cron job with the same file already exists.');
			return;
		}
		// Check Cron Time
		if(!array_key_exists($_POST['cron_time'], $cron_times)) {
			message('error','The selected cron time doesn\'t exist.');
			return;
		}
		
		$sql_data = array(
			$_POST['cron_name'],
			$_POST['cron_description'],
			$_POST['cron_file'],
			$cron_times[$_POST['cron_time']],
			1,
			md5_file($filePath)
		);
		
		$query = $dB->query("INSERT INTO WEBENGINE_CRON (cron_name, cron_description, cron_file_run, cron_run_time, cron_status, cron_file_md5) VALUES (?, ?, ?, ?, ?, ?)", $sql_data);
		if($query) {
		
			// UPDATE CACHE
			updateCronCache();
			
			message('success','Cron job successfully added!');
		} else {
			message('error','Could not add cron job.');
		}
		
	} else {
		message('error','Please complete all the required fields.');
	}
}

function updateCronLastRun($file) {
	global $dB;
	$update = $dB->query("UPDATE WEBENGINE_CRON SET cron_last_run = '".time()."' WHERE cron_file_run = '".$file."'");
	if($update) {
		// UPDATE CACHE
		updateCronCache();
	}
}

function updateCronCache() {
	global $dB;
	$cacheDATA = BuildCacheData($dB->query_fetch("SELECT * FROM WEBENGINE_CRON"));
	UpdateCache('cron.cache',$cacheDATA);
}

function getCronJobDATA($id) {
	global $dB;
	$result = $dB->query_fetch_single("SELECT * FROM WEBENGINE_CRON WHERE cron_id = '$id'");
	if(is_array($result)) {
		return $result;
	}
}

function deleteCronJob($id) {
	global $dB;
	$cronDATA = getCronJobDATA($id);
	if(is_array($cronDATA)) {
		if($cronDATA['cron_protected']) {
			message('error','This cron job is protected therefore cannot be deleted.');
			return;
		}
		$delete = $dB->query("DELETE FROM WEBENGINE_CRON WHERE cron_id = '$id'");
		if($delete) {
			message('success','Cron job "<strong>'.$cronDATA['cron_name'].'</strong>" successfully deteled!');
			updateCronCache();
		} else {
			message('error','Could not delete cron job.');
		}
	} else {
		message('error','Could not find cron job.');
	}
}

function togglestatusCronJob($id) {
	global $dB;
	$cronDATA = getCronJobDATA($id);
	if(is_array($cronDATA)) {
		if($cronDATA['cron_status'] == 1) {
			$status = 0;
		} else {
			$status = 1;
		}
		$toggle = $dB->query("UPDATE WEBENGINE_CRON SET cron_status = $status WHERE cron_id = '$id'");
		if($toggle) {
			message('success','Cron job "<strong>'.$cronDATA['cron_name'].'</strong>" status successfully changed!');
			updateCronCache();
		} else {
			message('error','Could not update cron job.');
		}
	} else {
		message('error','Could not find cron job.');
	}
}

function editCronJob($id,$name,$desc,$file,$time,$cron_times,$current_file) {
	global $dB;
	if(check_value($name) && check_value($file) && check_value($time)) {
		$filePath = __PATH_CRON__.$file;

		// Check Cron File Exists
		if(!file_exists($filePath)) {
			message('error','The selected file doesn\'t exist.');
			return;
		}
		// Check Cron File Databse
		if($file != $current_file) {
			if(!cronFileAlreadyExists($file)) {
				message('error','A cron job with the same file already exists.');
				return;
			}
		}
		// Check Cron Time
		if(!array_key_exists($time, $cron_times)) {
			message('error','The selected cron time doesn\'t exist.');
			return;
		}

		$query = $dB->query("UPDATE WEBENGINE_CRON SET cron_name = '".$name."', cron_description = '".$desc."', cron_file_run = '".$file."', cron_run_time = '".$cron_times[$time]."' WHERE cron_id = $id");
		if($query) {
		
			// UPDATE CACHE
			updateCronCache();
			
			message('success','Cron job successfully updated!');
		} else {
			message('error','Could not edit cron job.');
		}
	} else {
		message('error','You must fill all the required fields.');
	}
}

function returnGuildLogo($binaryData="", $size=40) {
	$imgSize = (Validator::UnsignedNumber($size) ? $size : 40);
	$imgData = (config('gmark_bin2hex_enable', true) ? bin2hex($binaryData) : $binaryData);
	return '<img src="'.__BASE_URL__.'helper.php?req='.$imgData.'&s='.urlencode($size).'" width="'.$imgSize.'" height="'.$imgSize.'">';
}

function getGensRank($id=0) {
	global $custom;
	if(!is_array($custom['gens_ranks'])) return 'None';
	if(!array_key_exists($id, $custom['gens_ranks'])) return 'None';
	return $custom['gens_ranks'][$id];
}

function webengineConfigs() {
	if(!file_exists(__PATH_CONFIGS__ . 'webengine.json')) throw new Exception('WebEngine\'s configuration file doesn\'t exist, please reupload the website files.');
	
	$webengineConfigs = file_get_contents(__PATH_CONFIGS__ . 'webengine.json');
	if(!check_value($webengineConfigs)) throw new Exception('WebEngine\'s configuration file is empty, please run the installation script.');
	
	return json_decode($webengineConfigs, true);
}

function config($config_name, $return = false) {
	$config = webengineConfigs();
	if($return) {
		return $config[$config_name];
	} else {
		echo $config[$config_name];
	}
}

function convertXML($object) {
	return json_decode(json_encode($object), true);
}

function loadModuleConfigs($module) {
	global $mconfig;
	if(moduleConfigExists($module)) {
		$xml = simplexml_load_file(__PATH_MODULE_CONFIGS__.$module.'.xml');
		$mconfig = array();
		if($xml) {
			$moduleCONFIGS = convertXML($xml->children());
			$mconfig = $moduleCONFIGS;
		}
	}
}

function moduleConfigExists($module) {
	if(file_exists(__PATH_MODULE_CONFIGS__.$module.'.xml')) {
		return true;
	}
}

function globalConfigExists($config_file) {
	if(file_exists(__PATH_CONFIGS__.$config_file.'.xml')) {
		return true;
	}
}

function mconfig($configuration) {
	global $mconfig;
	if(@array_key_exists($configuration, $mconfig)) {
		return $mconfig[$configuration];
	} else {
		return null;
	}
}

function gconfig($config_file,$return=true) {
	global $gconfig;
	if(globalConfigExists($config_file)) {
		$xml = simplexml_load_file(__PATH_CONFIGS__.$config_file.'.xml');
		$gconfig = array();
		if($xml) {
			$globalCONFIGS = convertXML($xml->children());
			if($return) {
				return $globalCONFIGS;
			} else {
				$gconfig = $globalCONFIGS;
			}
		}
	}
}

function loadConfigurations($file) {
	if(!check_value($file)) return;
	if(!moduleConfigExists($file)) return;
	$xml = simplexml_load_file(__PATH_MODULE_CONFIGS__ . $file . '.xml');
	if($xml) return convertXML($xml->children());
	return;
}

function loadConfig($name="webengine") {
	if(!check_value($name)) return;
	if(!file_exists(__PATH_CONFIGS__ . $name . '.json')) return;
	$cfg = file_get_contents(__PATH_CONFIGS__ . $name . '.json');
	if(!check_value($cfg)) return;
	return json_decode($cfg, true);
}