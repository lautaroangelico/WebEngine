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

class Plugins {
	
	function __construct() {
		
		// load database
		$this->db = Connection::Database('Me_MuOnline');
		
	}
	
	public function importPlugin($_FILE) {
		if($_FILE["file"]["type"] == "text/xml") {
			$xml = simplexml_load_file($_FILE["file"]["tmp_name"]);
			$pluginDATA = convertXML($xml->children());
			if($this->checkXML($pluginDATA)) {
				if($this->checkCompatibility($pluginDATA['compatibility'])) {
					if($this->checkPluginDirectory($pluginDATA['folder'])) {
						if($this->checkFiles($pluginDATA['files'],$pluginDATA['folder'])) {
							// Install Plugin
							$install = $this->installPlugin($pluginDATA);
							if($install) {
								message('success','Plugin successfully imported!');
							} else {
								message('error','Could not import plugin.');
							}
							
							$update_cache = $this->rebuildPluginsCache();
							if(!$update_cache) {
								message('error','Could not update plugins cache data, make sure the file exists and it\'s writable!');
							}
						} else { message('error','Plugin file(s) missing.'); }
					} else { message('error','Plugin folder not found, please make sure you upload it to the correct path.'); }
				} else { message('error','The plugin is not compatible with your current version.'); }
			} else { message('error','Invalid file or missing data.'); }
		} else { message('error','Invalid file type (only XML).'); }
	}
	
	private function checkXML($array) {
		if(array_key_exists('name',$array)
		&& array_key_exists('author',$array)
		&& array_key_exists('version',$array)
		&& array_key_exists('compatibility',$array)
		&& array_key_exists('folder',$array)
		&& array_key_exists('files',$array)) {
			if(check_value($array['name'])
			&& check_value($array['author'])
			&& check_value($array['version'])
			&& check_value($array['folder'])) {
				if(is_array($array['compatibility']) && is_array($array['files'])) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	private function checkCompatibility($array) {
		if(array_key_exists('webengine',$array)) {
			if(is_array($array['webengine'])) {
				if(in_array(__WEBENGINE_VERSION__,$array['webengine'])) {
					return true;
				} else {
					return false;
				}
			} else {
				if(__WEBENGINE_VERSION__ == $array['webengine']) {
					return true;
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}
	
	private function checkPluginDirectory($name) {
		if(file_exists($this->pluginPath($name)) && is_dir($this->pluginPath($name))) {
			return true;
		} else {
			return false;
		}
	}
	
	private function checkFiles($array,$plugin_name) {
		if(array_key_exists('file',$array)) {
			if(is_array($array['file'])) {
				$error = false;
				foreach($array['file'] as $thisFile) {
					$file = $this->pluginPath($plugin_name).$thisFile;
					if(!file_exists($file)) {
						$error = true;
					}
					if($thisFile == 'loader.php') {
						@$build = $this->_getBuidHash($file);
						if(check_value($build)) {
							$validateBuildHash = @$this->_validateBuildHash($build);
							if(!is_array($validateBuildHash)) return;
							if($validateBuildHash['status'] != true) return;
						}
					}
				}
				if($error) {
					return false;
				} else {
					return true;
				}
			} else {
				$file = $this->pluginPath($plugin_name).$array['file'];
				if(file_exists($file)) {
					@$build = $this->_getBuidHash($file);
					if(check_value($build)) {
						$validateBuildHash = @$this->_validateBuildHash($build);
						if(!is_array($validateBuildHash)) return;
						if($validateBuildHash['status'] != true) return;
					}
					return true;
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}
	
	public function pluginPath($name) {
		return __PATH_PLUGINS__.$name.'/';
	}
	
	private function installPlugin($pluginDATA) {
		$compatibility = $pluginDATA['compatibility']['webengine'];
		$files = $pluginDATA['files']['file'];
		if(is_array($pluginDATA['compatibility']['webengine'])) {
			$compatibility = implode("|",$pluginDATA['compatibility']['webengine']);
		}
		if(is_array($pluginDATA['files']['file'])) {
			$files = implode("|",$pluginDATA['files']['file']);
		}
		$data = array(
			$pluginDATA['name'],
			$pluginDATA['author'],
			$pluginDATA['version'],
			$compatibility,
			$pluginDATA['folder'],
			$files,
			1,
			time(),
			$_SESSION['username']
		);
		$query = $this->db->query("INSERT INTO ".WEBENGINE_PLUGINS." (name, author, version, compatibility, folder, files, status, install_date, installed_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", $data);
		if($query) {
			@$this->_getPluginLatestVersion($pluginDATA['folder'], $pluginDATA['version']);
			return true;
		} else {
			return false;
		}
	}
	
	public function retrieveInstalledPlugins() {
		$plugins = $this->db->query_fetch("SELECT * FROM ".WEBENGINE_PLUGINS." ORDER BY id ASC");
		return $plugins;
	}
	
	public function updatePluginStatus($plugin_id,$new_status) {
		$update = $this->db->query("UPDATE ".WEBENGINE_PLUGINS." SET status = ? WHERE id = ?", array($new_status, $plugin_id));
		$update_cache = $this->rebuildPluginsCache();
		if(!$update_cache) {
			message('error','Could not update plugins cache data, make sure the file exists and it\'s writable!');
		}
	}
	
	public function uninstallPlugin($plugin_id) {
		$uninstall = $this->db->query("DELETE FROM ".WEBENGINE_PLUGINS." WHERE id = ?", array($plugin_id));
		if($uninstall) {
			return true;
		} else {
			return false;
		}
	}
	
	public function rebuildPluginsCache() {
		$plugins = $this->db->query_fetch("SELECT * FROM ".WEBENGINE_PLUGINS." WHERE status = 1 ORDER BY id ASC");
		if(!is_array($plugins)) {
			$update = updateCacheFile('plugins.cache', "");
			if(!$update) return;
			return true;
		}
		
		foreach($plugins as $key => $row) {
			$compatibility = explode('|', $row['compatibility']);
			if(!is_array($compatibility)) continue;
			if(!in_array(__WEBENGINE_VERSION__, $compatibility)) continue;
			
			$files = explode('|', $row['files']);
			if(!is_array($files)) continue;
			
			$plugins[$key]['compatibility'] = $compatibility;
			$plugins[$key]['files'] = $files;
		}
		
		$cacheData = encodeCache($plugins);
		$update = updateCacheFile('plugins.cache', $cacheData);
		if(!$update) return;
		return true;
	}
	
	private function _getPluginLatestVersion($plugin, $version='1.0.0') {
		if(!check_value($plugin)) return;
		if(!check_value($version)) return;
		
		$url = 'https://version.webenginecms.org/1.0/plugin.php';
		
		$fields = array(
			'version' => urlencode($version),
			'baseurl' => urlencode(__BASE_URL__),
			'plugin' => urlencode($plugin),
		);
		
		foreach($fields as $key => $value) {
			$fieldsArray[] = $key . '=' . $value;
		}
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&", $fieldsArray));
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'WebEngine');
		curl_setopt($ch, CURLOPT_HEADER, false);

		$result = curl_exec($ch);
		curl_close($ch);
		
		if(!$result) return;
		$resultArray = json_decode($result, true);
		if(!is_array($resultArray)) return;
		return $resultArray;
	}
	
	private function _getBuidHash($filePath) {
		$fileContents = file_get_contents($filePath);
		$srch = preg_match("/@build/", $fileContents, $matches, PREG_OFFSET_CAPTURE);
		if(is_array($matches) && count($matches) > 0) {
			$build = substr($fileContents, $matches[0][1]+7, 32);
			if(!check_value($build)) return;
			if(strlen($build) !=32) return;
			return $build;
		}
		return;
	}
	
	private function _validateBuildHash($hash) {
		if(!check_value($hash)) return;
		
		$url = 'https://version.webenginecms.org/1.0/hash.php';
		
		$fields = array(
			'build' => urlencode($hash),
			'baseurl' => urlencode(__BASE_URL__),
		);
		
		foreach($fields as $key => $value) {
			$fieldsArray[] = $key . '=' . $value;
		}
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&", $fieldsArray));
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'WebEngine');
		curl_setopt($ch, CURLOPT_HEADER, false);

		$result = curl_exec($ch);
		curl_close($ch);
		
		if(!$result) return;
		$resultArray = json_decode($result, true);
		if(!is_array($resultArray)) return;
		return $resultArray;
	}
}