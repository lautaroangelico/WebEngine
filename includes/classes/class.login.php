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

class login {
	
	private $_config;
	
	function __construct() {
		global $_SESSION, $dB, $dB2;
		
		$this->common = new common($dB, $dB2);
		$this->me = (config('SQL_USE_2_DB',true) ? $dB2 : $dB);
		
		$loginConfigs = loadConfigurations('login');
		if(!is_array($loginConfigs)) throw new Exception('Login configurations missing.');
		$this->_config = $loginConfigs;
	}
	
	public function isLoggedIN() {
		if(!$_SESSION['valid']) return;
		if(!check_value($_SESSION['userid'])) return;
		if(!check_value($_SESSION['username'])) return;
		
		if(!$this->checkActiveSession($_SESSION['userid'], session_id())) {
			# session is inactive -> logout
			$this->logout();
			return;
		}
		
		# update session time
		$this->updateActiveSessionTime($_SESSION['userid']);
		
		# no session timeout
		if(!$this->_config['enable_session_timeout']) return true;
		
		# session timeout is enabled
		if(!$this->isSessionActive($_SESSION['timeout'])) {
			# session timed out -> logout
			$this->logout();
			return;
		}
		
		# update session data
		$_SESSION['timeout'] = time();
		
		return true;
	}
	
	public function validateLogin($username, $password) {
		
		if(!check_value($username)) throw new Exception(lang('error_4',true));
		if(!check_value($password)) throw new Exception(lang('error_4',true));
		if(!$this->canLogin($_SERVER['REMOTE_ADDR'])) throw new Exception(lang('error_3',true));
		if(!$this->common->userExists($username)) throw new Exception(lang('error_2',true));
		if($this->common->validateUser($username,$password)) {
			# login success
			$this->removeFailedLogins($_SERVER['REMOTE_ADDR']);
			session_regenerate_id();
			$_SESSION['valid'] = true;
			$_SESSION['timeout'] = time();
			$_SESSION['userid'] = $this->common->retrieveUserID($username);
			$_SESSION['username'] = $username;
			
			// ACTIVE SESSIONS
			$this->deleteActiveSession($_SESSION['userid']);
			$this->addActiveSession($_SESSION['userid'], $_SERVER['REMOTE_ADDR']);
			
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
		
		$result = $this->me->query_fetch_single("SELECT * FROM WEBENGINE_FLA WHERE ip_address = ? ORDER BY id DESC", array($ipaddress));
		if(!is_array($result)) return true;
		if(time() < $result['unlock_timestamp']) return;
		
		$this->removeFailedLogins($ipaddress);
		return true;
	}
	
	public function checkFailedLogins($ipaddress) {
		if(!Validator::Ip($ipaddress)) return;
		$result = $this->me->query_fetch_single("SELECT * FROM WEBENGINE_FLA WHERE ip_address = ? ORDER BY id DESC", array($ipaddress));
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
				$this->me->query("UPDATE WEBENGINE_FLA SET username = ?, ip_address = ?, failed_attempts = failed_attempts + 1, unlock_timestamp = ?, timestamp = ? WHERE ip_address = ?", array($username, $ipaddress, $timeout, time(), $ipaddress));
			} else {
				$this->me->query("UPDATE WEBENGINE_FLA SET username = ?, ip_address = ?, failed_attempts = failed_attempts + 1, timestamp = ? WHERE ip_address = ?", array($username, $ipaddress, time(), $ipaddress));
			}
		} else {
			# insert
			$data = array($username, $ipaddress, 0, 1, time());
			$this->me->query("INSERT INTO WEBENGINE_FLA (username, ip_address, unlock_timestamp, failed_attempts, timestamp) VALUES (?, ?, ?, ?, ?)", $data);
		}
	
	}
	
	public function removeFailedLogins($ipaddress) {
		if(Validator::Ip($ipaddress)) return;
		$this->me->query("DELETE FROM WEBENGINE_FLA WHERE ip_address = ?", array($ipaddress));
	}
	
	public function isSessionActive($session_timeout) {
		if(!check_value($session_timeout)) return;
		$offset = time() - $session_timeout;
		if($offset > $this->_config['session_timeout']) return;
		return true;
	}
	
	public function logout() {
		$_SESSION = array();
		session_destroy();
		redirect();
	}
	
	private function deleteActiveSession($userid) {
		$this->me->query("DELETE FROM WEBENGINE_ACTIVE_SESSIONS WHERE session_user_id = ?", array($userid));
	}
	
	private function addActiveSession($userid,$ipaddress) {
		$add = $this->me->query("INSERT INTO WEBENGINE_ACTIVE_SESSIONS (session_user_id,session_id,session_ip,session_time) VALUES (?,?,?,?) ", array($userid,session_id(),$ipaddress,time()));
		if(!$add) return;
		return true;
	}
	
	private function checkActiveSession($userid,$session_id) {
		$check = $this->me->query_fetch_single("SELECT * FROM WEBENGINE_ACTIVE_SESSIONS WHERE session_user_id = ? AND session_id = ?", array($userid,$session_id));
		if(!is_array($check)) return;
		return true;
	}
	
	private function updateActiveSessionTime($userid) {
		$update = $this->me->query("UPDATE WEBENGINE_ACTIVE_SESSIONS SET session_time = ? WHERE session_user_id = ?", array(time(),$userid));
		if(!$update) return;
		return true;
	}

}