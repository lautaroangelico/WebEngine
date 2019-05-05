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

class login {
	
	private $_config;
	
	function __construct() {
		global $_SESSION;
		
		$this->common = new common();
		$this->me = Connection::Database('Me_MuOnline');
		
		$loginConfigs = loadConfigurations('login');
		if(!is_array($loginConfigs)) throw new Exception(lang('error_98'));
		$this->_config = $loginConfigs;
	}
	
	public function validateLogin($username, $password) {
		
		if(!check_value($username)) throw new Exception(lang('error_4',true));
		if(!check_value($password)) throw new Exception(lang('error_4',true));
		if(!$this->canLogin($_SERVER['REMOTE_ADDR'])) throw new Exception(lang('error_3',true));
		if(!$this->common->userExists($username)) throw new Exception(lang('error_2',true));
		if($this->common->validateUser($username,$password)) {
			
			$userId = $this->common->retrieveUserID($username);
			if(!check_value($userId)) throw new Exception(lang('error_12',true));
			
			$accountData = $this->common->accountInformation($userId);
			if(!is_array($accountData)) throw new Exception(lang('error_12',true));
			
			# login success
			$this->removeFailedLogins($_SERVER['REMOTE_ADDR']);
			session_regenerate_id();
			$_SESSION['valid'] = true;
			$_SESSION['timeout'] = time();
			$_SESSION['userid'] = $userId;
			$_SESSION['username'] = $accountData[_CLMN_USERNM_];
			
			# redirect to usercp
			redirect(1,'usercp/');
			
		} else {
			# failed login
			$this->addFailedLogin($username,$_SERVER['REMOTE_ADDR']);
			message('error', lang('error_1',true));
			message('warning', langf('login_txt_5', array($this->checkFailedLogins($_SERVER['REMOTE_ADDR']), mconfig('max_login_attempts'), mconfig('max_login_attempts'))));
		}
	}
	
	public function canLogin($ipaddress) {
		if(!Validator::Ip($ipaddress)) return;
		$failedLogins = $this->checkFailedLogins($ipaddress);
		if($failedLogins < $this->_config['max_login_attempts']) return true;
		
		$result = $this->me->query_fetch_single("SELECT * FROM ".WEBENGINE_FLA." WHERE ip_address = ? ORDER BY id DESC", array($ipaddress));
		if(!is_array($result)) return true;
		if(time() < $result['unlock_timestamp']) return;
		
		$this->removeFailedLogins($ipaddress);
		return true;
	}
	
	public function checkFailedLogins($ipaddress) {
		if(!Validator::Ip($ipaddress)) return;
		$result = $this->me->query_fetch_single("SELECT * FROM ".WEBENGINE_FLA." WHERE ip_address = ? ORDER BY id DESC", array($ipaddress));
		if(!is_array($result)) return;
		return $result['failed_attempts'];
	}
	
	public function addFailedLogin($username, $ipaddress) {
		if(!Validator::UsernameLength($username)) return;
		if(!Validator::AlphaNumeric($username)) return;
		if(!Validator::Ip($ipaddress)) return;
		if(!$this->common->userExists($username)) return;
		
		$failedLogins = $this->checkFailedLogins($ipaddress);
		$timeout = time()+$this->_config['failed_login_timeout']*60;
		
		if($failedLogins >= 1) {
			# update
			if(($failedLogins+1) >= $this->_config['max_login_attempts']) {
				# max failed attemps -> block
				$this->me->query("UPDATE ".WEBENGINE_FLA." SET username = ?, ip_address = ?, failed_attempts = failed_attempts + 1, unlock_timestamp = ?, timestamp = ? WHERE ip_address = ?", array($username, $ipaddress, $timeout, time(), $ipaddress));
			} else {
				$this->me->query("UPDATE ".WEBENGINE_FLA." SET username = ?, ip_address = ?, failed_attempts = failed_attempts + 1, timestamp = ? WHERE ip_address = ?", array($username, $ipaddress, time(), $ipaddress));
			}
		} else {
			# insert
			$data = array($username, $ipaddress, 0, 1, time());
			$this->me->query("INSERT INTO ".WEBENGINE_FLA." (username, ip_address, unlock_timestamp, failed_attempts, timestamp) VALUES (?, ?, ?, ?, ?)", $data);
		}
	
	}
	
	public function removeFailedLogins($ipaddress) {
		if(!Validator::Ip($ipaddress)) return;
		$this->me->query("DELETE FROM ".WEBENGINE_FLA." WHERE ip_address = ?", array($ipaddress));
	}
	
	public function logout() {
		$_SESSION = array();
		session_destroy();
		redirect();
	}

}