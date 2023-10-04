<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.5
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2023 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

function check_value($value=array()) {
	if(@empty($value) and !@isset($value)) return;
	if(is_array($value)) {
    	if(count($value)>0) return true;
    } else {
    	if(!@empty($value) and @isset($value)) return true;
    	if($value=='0') return true;
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
	if(!isset($_SESSION['valid'])) return;
	if(!isset($_SESSION['userid'])) return;
	if(!isset($_SESSION['username'])) return;
	if(!isset($_SESSION['timeout'])) return;
	
	$loginConfigs = loadConfigurations('login');
	if(is_array($loginConfigs)) {
		if($loginConfigs['enable_session_timeout']) {
			if(time()-$_SESSION['timeout'] > $loginConfigs['session_timeout']) {
				logOutUser();
			}
		}
	}
	
	$_SESSION['timeout'] = time();
	return true;
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
	if(!array_key_exists($phrase, $lang)) {
		$result = 'ERROR';
	} else {
		$result = $lang[$phrase];
	}
	
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
	if(empty($cache_file)) return;
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

# to be removed
function cs_CalculateTimeLeft() {
	return 0;
}

function updateCronLastRun($file) {
	$database = Connection::Database('Me_MuOnline');
	$update = $database->query("UPDATE ".WEBENGINE_CRON." SET cron_last_run = ? WHERE cron_file_run = ?", array(time(), $file));
	if(!$update) return;
	return true;
}

function returnGuildLogo($binaryData="", $size=40) {
	$imgSize = Validator::UnsignedNumber($size) ? $size : 40;
	return '<img src="'.__PATH_API__.'guildmark.php?data='.$binaryData.'&size='.urlencode($size).'" width="'.$imgSize.'" height="'.$imgSize.'">';
}

function getGensRank($contributionPoints) {
	global $custom;
	foreach($custom['gens_ranks'] as $points => $title) {
		if($contributionPoints >= $points) return $title;
	}
	return $title;
}

function getGensLeadershipRank($rankPosition) {
	global $custom;
	foreach($custom['gens_ranks_leadership'] as $title => $range) {
		if($rankPosition >= $range[0] && $rankPosition <= $range[1]) return $title;
	}
	return;
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

function getPlayerClassAvatar($code=0, $htmlImageTag=true, $tooltip=true, $cssClass=null) {
	global $custom;
	$imageFileName = array_key_exists($code, $custom['character_class']) ? $custom['character_class'][$code][2] : 'avatar.jpg';
	$imageFullPath = __PATH_TEMPLATE_IMG__ . config('character_avatars_dir', true) . '/' . $imageFileName;
	$className = array_key_exists($code, $custom['character_class']) ? $custom['character_class'][$code][0] : '';
	if(!$htmlImageTag) return $imageFullPath;
	$result = '<img';
	if(check_value($cssClass)) $result .= ' class="'.$cssClass.'"';
	if($tooltip) $result .= ' data-toggle="tooltip" data-placement="top" title="'.$className.'" alt="'.$className.'"';
	$result .= ' src="'.$imageFullPath.'" />';
	return $result;
}

function playerProfile($playerName) {
	if(!config('player_profiles',true)) return $playerName;
	return '<a href="'.__BASE_URL__.'profile/player/req/'.urlencode($playerName).'/" target="_blank">'.$playerName.'</a>';
}

function guildProfile($guildName) {
	if(!config('guild_profiles',true)) return $guildName;
	return '<a href="'.__BASE_URL__.'profile/guild/req/'.urlencode($guildName).'/" target="_blank">'.$guildName.'</a>';
}

function encodeCache($data, $pretty=false) {
	if($pretty) return json_encode($data, JSON_PRETTY_PRINT);
	return json_encode($data);
}

function decodeCache($data) {
	return json_decode($data, true);
}

function updateCacheFile($fileName, $data) {
	$file = __PATH_CACHE__ . $fileName;
	if(!file_exists($file)) return;
	if(!is_writable($file)) return;
	
	$fp = fopen($file, 'w');
	fwrite($fp, $data);
	fclose($fp);
	return true;
}

function loadCache($fileName) {
	$file = __PATH_CACHE__ . $fileName;
	if(!file_exists($file)) return;
	if(!is_readable($file)) return;
	
	$cacheDataRaw = file_get_contents($file);
	if(!check_value($cacheDataRaw)) return;
	
	$cacheData = decodeCache($cacheDataRaw);
	if(!is_array($cacheData)) return;
	
	return $cacheData;
}

function checkBlockedIp() {
	if(in_array(access, array('cron'))) return;
	if(!check_value($_SERVER['REMOTE_ADDR'])) return true;
	if(!Validator::Ip($_SERVER['REMOTE_ADDR'])) return true;
	$blockedIpCache = loadCache('blocked_ip.cache');
	if(!is_array($blockedIpCache)) return;
	if(in_array($_SERVER['REMOTE_ADDR'], $blockedIpCache)) return true;
}

function getCronList() {
	$db = Connection::Database('Me_MuOnline');
	$result = $db->query_fetch("SELECT * FROM ".WEBENGINE_CRON." ORDER BY cron_id ASC");
	if(!is_array($result)) return;
	return $result;
}

function addRankingMenuLink($phrase, $module, $filesExclusivity=null) {
	global $rankingMenuLinks;
	if(!check_value($phrase)) return;
	if(!check_value($module)) return;
	
	if(is_array($filesExclusivity)) {
		if(!in_array(strtolower(config('server_files',true)), array_map('strtolower', $filesExclusivity))) return;
	}
	
	if(lang($phrase) != 'ERROR') {
		$phrase = lang($phrase);
	}
	
	$rankingMenuLinks[] = array($phrase, $module);
}

function getRankingMenuLinks() {
	global $rankingMenuLinks;
	if(!is_array($rankingMenuLinks)) return;
	return $rankingMenuLinks;
}

function loadJsonFile($filePath) {
	if(!file_exists($filePath)) return;
	if(!is_readable($filePath)) return;
	$jsonData = file_get_contents($filePath);
	if($jsonData == false) return;
	$result = json_decode($jsonData, true);
	if(!is_array($result)) return;	
	return $result;
}

function getCountryCodeFromIp($ip) {
	$api = 'http://ip-api.com/json/'.$ip.'?fields=status,countryCode';
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, $api);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($handle);
    curl_close($handle);
    if(!check_value($json)) return;
    $result = json_decode($json, true);
	if(!is_array($result)) return;
	if($result['status'] == 'fail') return;
	if(!check_value($result['countryCode'])) return;
	return $result['countryCode'];
}

function getCountryFlag($countryCode='default') {
	if(!check_value($countryCode)) $countryCode = 'default';
	return __PATH_COUNTRY_FLAGS__ . strtolower($countryCode) . '.gif';
}

function returnMapName($id=0) {
	global $custom;
	if(!is_array($custom['map_list'])) return 'Lorencia Bar';
	if(!array_key_exists($id, $custom['map_list'])) {
		if(config('error_reporting',true)) return 'Map Number ('.$id.')';
		return 'Lorencia Bar';
	}
	return $custom['map_list'][$id];
}

function returnPkLevel($id) {
	global $custom;
	if(!is_array($custom['pk_level'])) return;
	if(!array_key_exists($id, $custom['pk_level'])) return;
	return $custom['pk_level'][$id];
}

function getDirectoryListFromPath($path) {
	if(!file_exists($path)) return;
	$files = scandir($path);
	foreach($files as $row) {
		if(in_array($row, array('.','..'))) continue;
		if(!is_dir($path.$row)) continue;
		$result[] = $row;
	}
	if(!is_array($result)) return;
	return $result;
}

function getInstalledLanguagesList() {
	$languageDir = getDirectoryListFromPath(__PATH_LANGUAGES__);
	if(!is_array($languageDir)) return;
	foreach($languageDir as $language) {
		if(!file_exists(__PATH_LANGUAGES__.$language.'/language.php')) continue;
		$result[] = $language;
	}
	if(!is_array($result)) return;
	return $result;
}

// https://www.php.net/manual/en/function.filesize.php#106569
function readableFileSize($bytes, $decimals = 2) {
	$sz = 'BKMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

function getPlayerClass($class=0) {
	global $custom;
	if(!array_key_exists($class, $custom['character_class'])) return 'Unknown';
	return $custom['character_class'][$class][0];
}

function custom($index) {
	global $custom;
	if(!is_array($custom)) return;
	if(!array_key_exists($index, $custom)) return;
	return $custom[$index];
}