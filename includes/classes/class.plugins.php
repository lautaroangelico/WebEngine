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

class Plugins {
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
								message('success','Plugin successtully imported!');
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
				}
				if($error) {
					return false;
				} else {
					return true;
				}
			} else {
				$file = $this->pluginPath($plugin_name).$array['file'];
				if(file_exists($file)) {
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
		global $dB,$_SESSION;
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
		$query = $dB->query("INSERT INTO WEBENGINE_PLUGINS (name, author, version, compatibility, folder, files, status, install_date, installed_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}
	
	public function retrieveInstalledPlugins() {
		global $dB;
		$plugins = $dB->query_fetch("SELECT * FROM WEBENGINE_PLUGINS ORDER BY id ASC");
		return $plugins;
	}
	
	public function updatePluginStatus($plugin_id,$new_status) {
		global $dB;
		$update = $dB->query("UPDATE WEBENGINE_PLUGINS SET status = ? WHERE id = ?", array($new_status, $plugin_id));
		$update_cache = $this->rebuildPluginsCache();
		if(!$update_cache) {
			message('error','Could not update plugins cache data, make sure the file exists and it\'s writable!');
		}
	}
	
	public function uninstallPlugin($plugin_id) {
		global $dB;
		$uninstall = $dB->query("DELETE FROM WEBENGINE_PLUGINS WHERE id = ?", array($plugin_id));
		if($uninstall) {
			return true;
		} else {
			return false;
		}
	}
	
	public function rebuildPluginsCache() {
		global $dB;
		$plugins = $dB->query_fetch("SELECT * FROM WEBENGINE_PLUGINS WHERE status = 1 ORDER BY id ASC");
		if(is_array($plugins)) {
			
			$cacheDATA = array();
			$i = 0;
			foreach($plugins as $thisPlugin) {
				$cacheDATA[$i][] = $thisPlugin['folder'];
				$cacheDATA[$i][] = $thisPlugin['files'];
				$i++;
			}
			
			$buildCacheDATA = BuildCacheData($cacheDATA);
			$update = UpdateCache('plugins.cache',$buildCacheDATA);
			if($update) {
				return true;
			} else {
				return false;
			}
		} else {
			$update = UpdateCache('plugins.cache','');
			if($update) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	public function gotEnabledPlugins() {
		global $dB;
		$plugins = $dB->query_fetch("SELECT * FROM WEBENGINE_PLUGINS WHERE status = 1");
		if($plugins) {
			return true;
		} else {
			return false;
		}
	}
	
	public function loadPlugins() {
		$cache = LoadCacheData('plugins.cache');
		$i = 0;
		foreach($cache as $thisPlugin) {
			if($i >= 1) {
				$pPath = $this->pluginPath($thisPlugin[0]);
				$pFiles = explode("|",$thisPlugin[1]);
				foreach($pFiles as $pFile) {
					if(!@include_once($pPath.$pFile)) die('[WEBENGINE::ERROR][!#] Could not load plugin file ('.$pPath.$pFile.')');
				}
			}
			$i++;
		}
	}
}