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

class Handler {
	
	private $_disableWebEngineFooterVersion = false;
	private $_disableWebEngineFooterCredits = false;
	
	private $_dB;
	private $_dB2;
	
	function __construct(dB $dB, dB $dB2 = null) {
		$this->_dB = $dB;
		$this->_dB2 = (check_value($dB2) ? $dB2 : null);
	}
	
	public function loadPage() {
		global $config,$lang,$custom,$common,$tSettings;
		
		# object instances
		$handler = $this;
		$dB = $this->_dB;
		$dB2 = $this->_dB2;
		
		# load language
		$loadLanguage = (check_value($_SESSION['language_display']) ? $_SESSION['language_display'] : $config['language_default']);
		$loadLanguage = (config('language_switch_active',true) ? $loadLanguage : $config['language_default']);
		if(!$this->languageExists($loadLanguage)) throw new Exception('The chosen language cannot be loaded ('.$loadLanguage.').');
		include(__PATH_LANGUAGES__ . $loadLanguage . '/language.php');
		
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
		global $config,$lang,$custom,$common,$mconfig,$tSettings;
		
		$handler = $this;
		$dB = $this->_dB;
		$dB2 = $this->_dB2;
		
		$page = $this->cleanRequest($page);
		$subpage = $this->cleanRequest($subpage);
		
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
		
		echo '<div class="webengine-powered" style="text-transform:uppercase;">';
			echo '<a href="https://webenginecms.org/" target="_blank" style="color:#ff0000;">';
				echo 'Powered by WebEngine';
				if(!$this->_disableWebEngineFooterVersion) echo ' ' . __WEBENGINE_VERSION__;
			echo '</a>';
		echo '</div>';
	}
	
	public function loadAdminCPModule($module='home') {
		global $config,$lang,$custom,$common,$handler,$mconfig,$gconfig;
		
		$dB = $this->_dB;
		$dB2 = $this->_dB2;
		
		$module = (check_value($module) ? $module : 'home');
		
		if($this->admincpmoduleExists($module)) {
			
			// admin access level
			$adminAccessLevel = config('admins',true);
			$accessLevel = $adminAccessLevel[$_SESSION['username']];
			
			// module access level
			$modulesAccessLevel = config('admincp_modules_access',true);
			if(is_array($modulesAccessLevel)) {
				if(array_key_exists($module, $modulesAccessLevel)) {
					if($accessLevel >= $modulesAccessLevel[$module]) {
						include(__PATH_ADMINCP_MODULES__.$module.'.php');
					} else {
						message('error','You do not have access to this module.');
					}
				} else {
					include(__PATH_ADMINCP_MODULES__.$module.'.php');
				}
			}
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
}