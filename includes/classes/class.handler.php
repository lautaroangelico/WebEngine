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

class Handler {
	
	private $_disableWebEngineFooterVersion = false;
	private $_disableWebEngineFooterCredits = false;
	
	public function loadPage() {
		global $config,$lang,$custom,$tSettings;
		
		# object instances
		$handler = $this;
		
		# load language
		if(strtolower($config['language_default']) != 'en') {
			$this->_loadLanguagePhrases('en');
		}
		$this->_loadLanguagePhrases($config['language_default']);
		if($config['language_switch_active']) {
			if(isset($_SESSION['language_display'])) {
				if($_SESSION['language_display'] != $config['language_default']) {
					$this->_loadLanguagePhrases($_SESSION['language_display']);
				}
			}
		}
		
		# access
		if(!defined('access')) throw new Exception('Access forbidden.');
		switch(access) {
			case 'index':
				# check if template exists
				if(!$this->templateExists($config['website_template'])) throw new Exception('The chosen template cannot be loaded ('.$config['website_template'].').');
				
				# load template
				include(__PATH_TEMPLATES__ . $config['website_template'] . '/index.php');
				
				# show admincp button
				if(isLoggedIn() && canAccessAdminCP($_SESSION['username'])) {
					echo '<a href="'.__PATH_ADMINCP_HOME__.'" class="btn btn-primary admincp-button">AdminCP</a>';
				}
				break;
			case 'api':
				
				break;
			case 'cron':
				
				break;
			case 'admincp':
				
				break;
			case 'install':
				
				break;
			default:
				throw new Exception('Access forbidden.');
		}
	}

	public function loadModule($page = 'news',$subpage = 'home') {
		global $config,$lang,$custom,$mconfig,$tSettings;
		try {
			$handler = $this;
			$page = $this->cleanRequest($page);
			$subpage = $this->cleanRequest($subpage);
			
			if(isset($_GET['request'])) {
				$request = explode("/", $_GET['request']);
				if(is_array($request)) {
					for($i = 0; $i < count($request); $i++) {
						if(check_value($request[$i])) {
							if(check_value($request[$i+1])) {
								$_GET[$request[$i]] = filter_var($request[$i+1], FILTER_SANITIZE_STRING);
							} else {
								$_GET[$request[$i]] = NULL;
							}
						}
						$i++;
					}
				}
			}
			
			if(!check_value($page)) { $page = 'news'; }
			
			if(!check_value($subpage)) {
				if($this->moduleExists($page)) {
					@loadModuleConfigs($page);
					include(__PATH_MODULES__ . $page . '.php');
				} else {
					$this->module404();
				}
			} else {
				// HANDLE PAGE AS DIRECTORY (PATH)
				switch($page) {
					case 'news':
						if($this->moduleExists($page)) {
							@loadModuleConfigs($page);
							include(__PATH_MODULES__ . $page . '.php');
						} else {
							$this->module404();
						}
					break;
					default:
						$path = $page.'/'.$subpage;
						if($this->moduleExists($path)) {
							$cnf = $page.'.'.$subpage;
							@loadModuleConfigs($cnf);
							include(__PATH_MODULES__ . $path . '.php');
						} else {
							$this->module404();
						}
					break;
				}
			}
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	private function moduleExists($page) {
		if(file_exists(__PATH_MODULES__ . $page . '.php')) return true;
		return false;
	}
	
	private function usercpmoduleExists($page) {
		if(file_exists(__PATH_MODULES_USERCP__ . $page . '.php')) return true;
		return false;
	}
	
	private function templateExists($template) {
		if(file_exists(__PATH_TEMPLATES__ . $template . '/index.php')) return true;
		return false;
	}
	
	private function languageExists($language) {
		if(file_exists(__PATH_LANGUAGES__ . $language . '/language.php')) return true;
		return false;
	}
	
	private function admincpmoduleExists($page) {
		if(file_exists(__PATH_ADMINCP_MODULES__ . $page . '.php')) return true;
		return false;
	}
	
	public function webenginePowered() {
		if($this->_disableWebEngineFooterCredits) return;
		
		echo '<a href="https://webenginecms.org/" target="_blank" class="webengine-powered">';
			echo 'Powered by WebEngine';
			if(!$this->_disableWebEngineFooterVersion) echo ' ' . __WEBENGINE_VERSION__;
		echo '</a>';
	}
	
	public function loadAdminCPModule($module='home') {
		global $config,$lang,$custom,$handler,$mconfig,$gconfig,$webengine;
		
		$dB = Connection::Database('MuOnline');
		$dB2 = Connection::Database('Me_MuOnline');
		$common = new common();
		
		$module = (check_value($module) ? $module : 'home');
		if($this->admincpmoduleExists($module)) {
			include(__PATH_ADMINCP_MODULES__.$module.'.php');
		} else {
			message('error','INVALID MODULE');
		}
	}
	
	public function websiteTitle() {
		$websiteTitle = (check_value(lang('website_title',true)) && lang('website_title',true) != 'ERROR' ? lang('website_title',true) : config('website_title',true));
		echo $websiteTitle;
	}
	
	private function cleanRequest($string) {
		return preg_replace("/[^a-zA-Z0-9\s\/]/", "", $string);
	}
	
	private function module404() {
		redirect();
	}
	
	public function switchLanguage($language) {
		if(!check_value($language)) return;
		if(!$this->languageExists($language)) return;
		
		# set session variable
		$_SESSION['language_display'] = $language;
		
		return true;
	}
	
	private function _loadLanguagePhrases($language='en') {
		global $lang;
		if(!@include_once(__PATH_LANGUAGES__ . $language . '/language.php')) throw new Exception('Language phrases could not be loaded ('.$language.').');
	}
	
	public function checkWebEngineBlacklist() {
		$url = 'http://version.webenginecms.org/1.0/blacklist.php';
		$fields = array(
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
		if(!array_key_exists('blacklisted', $resultArray)) return;
		if($resultArray['blacklisted'] === 1) {
			$webengineConfigurations = webengineConfigs();
			$webengineConfigurations['blacklisted'] = true;
			$newWebEngineConfig = json_encode($webengineConfigurations, JSON_PRETTY_PRINT);
			$cfgFile = fopen(__PATH_CONFIGS__.'webengine.json', 'w');
			if(!$cfgFile) return;
			if(!fwrite($cfgFile, $newWebEngineConfig)) return;
			fclose($cfgFile);
		}
		return;
	}
	
	public function versionApiListener() {
		if(!array_key_exists('HTTP_USER_AGENT', $_SERVER)) return;
		if($_SERVER['HTTP_USER_AGENT'] != "WebEngine") return;
		$this->checkWebEngineBlacklist();
	}
}