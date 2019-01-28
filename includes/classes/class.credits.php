<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.1.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2019 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

class CreditSystem {
	
	private $_configId;
	private $_identifier;
	
	private $_configTitle;
	private $_configDatabase;
	private $_configTable;
	private $_configCreditsCol;
	private $_configUserCol;
	private $_configUserColId;
	private $_configCheckOnline = true;
	private $_configDisplay = false;
	
	private $_allowedUserColId = array(
		'userid',
		'username',
		'email',
		'character'
	);
	
	function __construct(common $common, Character $character, dB $muonline, dB $me_muonline = null) {
		$this->common = $common;
		$this->character = $character;
		$this->muonline = $muonline;
		if($me_muonline) {
			$this->memuonline = $me_muonline;
		}
		
		$this->db = (config('SQL_USE_2_DB',true) ? $this->memuonline : $this->muonline);
	}
	
	public function setIdentifier($input) {
		if(!$this->_configId) throw new Exception("You have not set a configuration id.");
		$config = $this->showConfigs(true);
		
		switch($config['config_user_col_id']) {
			case 'userid':
				$this->_setUserid($input);
				break;
			case 'username':
				$this->_setUsername($input);
				break;
			case 'email':
				$this->_setEmail($input);
				break;
			case 'character':
				$this->_setCharacter($input);
				break;
			default:
				throw new Exception("invalid identifier.");
		}
	}
	
	/**
	 * _setUserId
	 * sets the userid identifier
	 * @param int $input
	 * @throws Exception
	 */
	private function _setUserid($input) {
		if(!Validator::UnsignedNumber($input)) throw new Exception("The userid entered is not valid.");
		$this->_identifier = $input;
	}
	
	/**
	 * _setUsername
	 * sets the username identifier
	 * @param string $input
	 * @throws Exception
	 */
	private function _setUsername($input) {
		if(!Validator::AlphaNumeric($input)) throw new Exception("The username entered contains non-allowed characters.");
		if(!Validator::UsernameLength($input)) throw new Exception("The username entered is not valid.");
		$this->_identifier = $input;
	}
	
	/**
	 * _setEmail
	 * sets the email identifier
	 * @param string $input
	 * @throws Exception
	 */
	private function _setEmail($input) {
		if(!Validator::Email($input)) throw new Exception("The email entered is not valid.");
		$this->_identifier = $input;
	}
	
	/**
	 * _setCharacter
	 * sets the character name identifier
	 * @param string $input
	 * @throws Exception
	 */
	private function _setCharacter($input) {
		if(!Validator::AlphaNumeric($input)) throw new Exception("The character name entered is not valid.");
		$this->_identifier = $input;
	}
	
	/**
	 * addCredits
	 * adds credits to an account or character depending on the cofiguration set
	 * @param int $input
	 * @throws Exception
	 */
	public function addCredits($input) {
		if(!Validator::UnsignedNumber($input)) throw new Exception("The amount of credits to add must be an unsigned number.");
		if(!$this->_configId) throw new Exception("You have not set a configuration id.");
		if(!$this->_identifier) throw new Exception("You have not set the user identifier.");
		
		// get configs
		$config = $this->showConfigs(true);
		
		// check online
		if($config['config_checkonline']) {
			if($this->_isOnline($config['config_user_col_id'])) throw new Exception("Your account is online, please disconnect.");
		}
		
		// choose database
		$database = ($config['config_database'] == "MuOnline" ? $this->muonline : $this->memuonline);
		
		// build query
		$data = array(
			'credits' => $input,
			'identifier' => $this->_identifier
		);
		$variables = array('{TABLE}','{COLUMN}','{USER_COLUMN}');
		$values = array($config['config_table'], $config['config_credits_col'], $config['config_user_col']);
		$query = str_replace($variables, $values, "UPDATE {TABLE} SET {COLUMN} = {COLUMN} + :credits WHERE {USER_COLUMN} = :identifier");
		
		// add credits
		$addCredits = $database->query($query, $data);
		if(!$addCredits) throw new Exception("There was an error adding the credits");
		
		$this->_addLog($config['config_title'], $input, "add");
	}
	
	/**
	 * subtractCredits
	 * subtracts credits from an account or character depending on the configuration set
	 * @param type $input
	 * @throws Exception
	 */
	public function subtractCredits($input) {
		if(!Validator::UnsignedNumber($input)) throw new Exception("The amount of credits to subtract must be an unsigned number.");
		if(!$this->_configId) throw new Exception("You have not set a configuration id.");
		if(!$this->_identifier) throw new Exception("You have not set the user identifier.");
		
		// get configs
		$config = $this->showConfigs(true);
		
		// check online
		if($config['config_checkonline']) {
			if($this->_isOnline($config['config_user_col_id'])) throw new Exception("Your account is online, please disconnect.");
		}
		
		// check current credits
		if($this->getCredits() < $input) throw new Exception(lang('error_40',true));
		
		// choose database
		$database = ($config['config_database'] == "MuOnline" ? $this->muonline : $this->memuonline);
		
		// build query
		$data = array(
			'credits' => $input,
			'identifier' => $this->_identifier
		);
		$variables = array('{TABLE}','{COLUMN}','{USER_COLUMN}');
		$values = array($config['config_table'], $config['config_credits_col'], $config['config_user_col']);
		$query = str_replace($variables, $values, "UPDATE {TABLE} SET {COLUMN} = {COLUMN} - :credits WHERE {USER_COLUMN} = :identifier");
		
		// add credits
		$addCredits = $database->query($query, $data);
		if(!$addCredits) throw new Exception("There was an error subtracting the credits");
		
		$this->_addLog($config['config_title'], $input, "subtract");
	}
	
	/**
	 * setConfigId
	 * sets the configuration id (from the database)
	 * @param int $input
	 * @throws Exception
	 */
	public function setConfigId($input) {
		if(!Validator::UnsignedNumber($input)) throw new Exception("Invalid configuration id.");
		if(!$this->_configurationExists($input)) throw new Exception("Invalid configuration id.");
		$this->_configId = $input;
	}
	
	/**
	 * setConfigtitle
	 * sets the title for the new configuration
	 * @param string $input
	 * @throws Exception
	 */
	public function setConfigTitle($input) {
		if(!Validator::Chars($input, array('a-z', 'A-Z', '0-9', ' '))) throw new Exception("The title can only contain alphanumeric characters and spaces.");
		$this->_configTitle = $input;
	}
	
	/**
	 * setConfigDatabase
	 * sets the database for the new configuration
	 * @param string $input
	 * @throws Exception
	 */
	public function setConfigDatabase($input) {
		if(!Validator::Chars($input, array('a-z', 'A-Z', '0-9', '_'))) throw new Exception("The database entered contains non-allowed characters.");
		$this->_configDatabase = $input;
	}
	
	/**
	 * setConfigtable
	 * sets the table for the new configuration
	 * @param string $input
	 * @throws Exception
	 */
	public function setConfigTable($input) {
		if(!Validator::Chars($input, array('a-z', 'A-Z', '0-9', '_'))) throw new Exception("The table entered contains non-allowed characters.");
		$this->_configTable = $input;
	}
	
	/**
	 * setConfigCreditsColumn
	 * sets the credits column for the new configuration
	 * @param string $input
	 * @throws Exception
	 */
	public function setConfigCreditsColumn($input) {
		if(!Validator::Chars($input, array('a-z', 'A-Z', '0-9', '_'))) throw new Exception("The credits column entered contains non-allowed characters.");
		$this->_configCreditsCol = $input;
	}
	
	/**
	 * setConfigUserColumn
	 * sets the user column for the new configuration
	 * @param string $input
	 * @throws Exception
	 */
	public function setConfigUserColumn($input) {
		if(!Validator::Chars($input, array('a-z', 'A-Z', '0-9', '_'))) throw new Exception("The user column entered contains non-allowed characters.");
		$this->_configUserCol = $input;
	}
	
	/**
	 * setConfigUserColumnId
	 * sets the user column identifier for the new configuration
	 * @param string $input
	 * @throws Exception
	 */
	public function setConfigUserColumnId($input) {
		if(!Validator::AlphaNumeric($input)) throw new Exception("The user column identifier is not valid, please select one of the following: userid, username, email or character.");
		if(!in_array($input, $this->_allowedUserColId)) throw new Exception("The user column identifier is not valid, please select one of the following: userid, username, email or character.");
		$this->_configUserColId = $input;
	}
	
	/**
	 * setConfigCheckOnline
	 * sets the online check for the new configuration
	 * @param boolean $input
	 */
	public function setConfigCheckOnline($input) {
		$this->_configCheckOnline = ($input ? 1 : 0);
	}
	
	/**
	 * setConfigDisplay
	 * sets the config display in myaccoutn module for the new configuration
	 * @param boolean $input
	 */
	public function setConfigDisplay($input) {
		$this->_configDisplay = ($input ? 1 : 0);
	}
	
	/**
	 * _configurationExists
	 * checks if the configuration exists in the database
	 * @param int $input
	 * @return boolean
	 */
	private function _configurationExists($input) {
		$check = $this->db->query_fetch_single("SELECT * FROM WEBENGINE_CREDITS_CONFIG WHERE config_id = ?", array($input));
		if($check) return true;
		return false;
	}
	
	/**
	 * saveConfig
	 * inserts the new configuration to the database
	 * @throws Exception
	 */
	public function saveConfig() {
		if(!$this->_configTitle) throw new Exception("You need to set a title to the configuration.");
		if(!$this->_configDatabase) throw new Exception("You need to set a database to the configuration.");
		if(!$this->_configTable) throw new Exception("You need to set a table to the configuration.");
		if(!$this->_configCreditsCol) throw new Exception("You need to set a credits column to the configuration.");
		if(!$this->_configUserCol) throw new Exception("You need to set a user column to the configuration.");
		if(!$this->_configUserColId) throw new Exception("You need to set a user column identifier to the configuration.");
		
		$data = array(
			'title' => $this->_configTitle,
			'database' => $this->_configDatabase,
			'table' => $this->_configTable,
			'creditscol' => $this->_configCreditsCol,
			'usercol' => $this->_configUserCol,
			'usercolid' => $this->_configUserColId,
			'checkonline' => $this->_configCheckOnline,
			'display' => $this->_configDisplay
		);
		
		$query = "INSERT INTO WEBENGINE_CREDITS_CONFIG "
			. "(config_title, config_database, config_table, config_credits_col, config_user_col, config_user_col_id, config_checkonline, config_display) "
			. "VALUES "
			. "(:title, :database, :table, :creditscol, :usercol, :usercolid, :checkonline, :display)";
		
		$saveConfig = $this->db->query($query, $data);
		if(!$saveConfig) throw new Exception("There has been an error adding the configuration to the database, check for database errors.");
	}
	
	/**
	 * editConfig
	 * edits a configuration from the database
	 * @throws Exception
	 */
	public function editConfig() {
		if(!$this->_configId) throw new Exception("You have not set a configuration id.");
		if(!$this->_configTitle) throw new Exception("You need to set a title to the configuration.");
		if(!$this->_configDatabase) throw new Exception("You need to set a database to the configuration.");
		if(!$this->_configTable) throw new Exception("You need to set a table to the configuration.");
		if(!$this->_configCreditsCol) throw new Exception("You need to set a credits column to the configuration.");
		if(!$this->_configUserCol) throw new Exception("You need to set a user column to the configuration.");
		if(!$this->_configUserColId) throw new Exception("You need to set a user column identifier to the configuration.");
		
		$data = array(
			'id' => $this->_configId,
			'title' => $this->_configTitle,
			'database' => $this->_configDatabase,
			'table' => $this->_configTable,
			'creditscol' => $this->_configCreditsCol,
			'usercol' => $this->_configUserCol,
			'usercolid' => $this->_configUserColId,
			'checkonline' => $this->_configCheckOnline,
			'display' => $this->_configDisplay
		);
		
		$query = "UPDATE WEBENGINE_CREDITS_CONFIG SET "
			. "config_title = :title, "
			. "config_database = :database, "
			. "config_table = :table, "
			. "config_credits_col = :creditscol, "
			. "config_user_col= :usercol, "
			. "config_user_col_id = :usercolid,"
			. "config_checkonline = :checkonline, "
			. "config_display = :display "
			. "WHERE config_id = :id";
		
		$editConfig = $this->db->query($query, $data);
		if(!$editConfig) throw new Exception("There has been an error editing the configuration, check for database errors.");
	}
	
	/**
	 * deleteConfig
	 * deletes a configuration from the database
	 * @throws Exception
	 */
	public function deleteConfig() {
		if(!$this->_configId) throw new Exception("You have not set a configuration id.");
		if(!$this->db->query("DELETE FROM WEBENGINE_CREDITS_CONFIG WHERE config_id = ?", array($this->_configId))) {
			throw new Exception("There has been an error deleting the configuration, check for database errors.");
		}
	}
	
	/**
	 * showConigs
	 * returns all or a single configuration from the database
	 * @param boolean $singleConfig
	 * @return array
	 * @throws Exception
	 */
	public function showConfigs($singleConfig = false) {
		if($singleConfig) {
			if(!$this->_configId) throw new Exception("You have not set a configuration id.");
			return $this->db->query_fetch_single("SELECT * FROM WEBENGINE_CREDITS_CONFIG WHERE config_id = ?", array($this->_configId));
		} else {
			$result = $this->db->query_fetch("SELECT * FROM WEBENGINE_CREDITS_CONFIG ORDER BY config_id ASC");
			if($result) return $result;
			return false;
		}
	}
	
	/**
	 * buildSelectInput
	 * builds a select input with all the configurations
	 * @param string $name
	 * @param int $default
	 * @param string $class
	 * @return string
	 */
	public function buildSelectInput($name="creditsconfig", $default=1, $class="") {
		$selectName = (Validator::Chars($name, array('a-z', 'A-Z', '0-9', '_')) ? $name : "creditsconfig");
		$selectedOption = (Validator::UnsignedNumber($default) ? $default : 1);
		$configs = $this->showConfigs();
		$return = ($class ? '<select name="'.$selectName.'" class="'.$class.'">' : '<select name="'.$selectName.'">');
		if(is_array($configs)) {
			if($default == 0) {
				$return .= '<option value="0" selected>none</option>';
			} else {
				$return .= '<option value="0">none</option>';
			}
			foreach($configs as $config) {
				if($selectedOption == $config['config_id']) {
					$return .= '<option value="'.$config['config_id'].'" selected>'.$config['config_title'].'</option>';
				} else {
					$return .= '<option value="'.$config['config_id'].'">'.$config['config_title'].'</option>';
				}
			}
		} else {
			$return .= '<option value="0" selected>none</option>';
		}
		$return .= '</select>';
		return $return;
	}
	
	/**
	 * _isOnline
	 * checks if the account is online
	 * @param string $input
	 * @return boolean
	 * @throws Exception
	 */
	private function _isOnline($input) {
		if(!$this->_identifier) throw new Exception("Identifier not set, cannot check online status.");
		switch($input) {
			case 'userid':
				// get account information using the id
				$accountInfo = $this->common->accountInformation($this->_identifier);
				if(!$accountInfo) throw new Exception("Could not retrieve account information.");
				
				// check online status
				return $this->common->accountOnline($accountInfo[_CLMN_USERNM_]);
				break;
			case 'username':
				// check online status
				return $this->common->accountOnline($this->_identifier);
				break;
			case 'email':
				// get the account id using the email
				$userId = $this->common->retrieveUserIDbyEmail($this->_identifier);
				if(!$userId) throw new Exception("Could not retrieve account information (email).");
				
				// get account information using the id
				$accountInfo = $this->common->accountInformation($userId);
				if(!$accountInfo) throw new Exception("Could not retrieve account information.");
				
				// check online status
				return $this->common->accountOnline($accountInfo[_CLMN_USERNM_]);
				break;
			case 'character':
				// get account username from character data
				$characterData = $this->character->CharacterData($this->_identifier);
				if(!$characterData) throw new Exception("Could not retrieve account information (character).");
				
				// check online status
				return $this->common->accountOnline($characterData[_CLMN_CHR_ACCID_]);
				break;
			default:
				throw new Exception("Invalid identifier set, cannot check online status.");
		}
	}
	
	/**
	 * _addLog
	 * saves a log of credits transactions
	 * @param string $configTitle
	 * @param int $credits
	 * @param string $transaction
	 */
	private function _addLog($configTitle="unknown", $credits=0, $transaction="unknown") {
		$inadmincp = access == 'admincp' ? 1 : 0;
		if($inadmincp == 1) {
			$module = $_GET['module'];
		} else {
			$module = $_GET['page'] . '/' . $_GET['subpage'];
		}
		$ip = (check_value($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0');
		
		$data = array(
			'config' => $configTitle,
			'identifier' => $this->_identifier,
			'credits' => $credits,
			'transaction' => $transaction,
			'timestamp' => time(),
			'inadmincp' => $inadmincp,
			'module' => $module,
			'ip' => $ip
		);
		
		$query = "INSERT INTO WEBENGINE_CREDITS_LOGS "
			. "(log_config, log_identifier, log_credits, log_transaction, log_date, log_inadmincp, log_module, log_ip) "
			. "VALUES "
			. "(:config, :identifier, :credits, :transaction, :timestamp, :inadmincp, :module, :ip)";
		
		$saveLog = $this->db->query($query, $data);
	}
	
	/**
	 * getLogs
	 * returns an array of logs from the database
	 * @param int $limit
	 * @return array
	 */
	public function getLogs($limit=50) {
		$result = $this->db->query_fetch("SELECT TOP 50 * FROM WEBENGINE_CREDITS_LOGS ORDER BY log_id DESC");
		if(is_array($result)) return $result;
	}
	
	/**
	 * getCredits
	 * returns the available credits of the user
	 * @return int
	 */
	public function getCredits() {
		if(!$this->_configId) throw new Exception("You have not set a configuration id.");
		if(!$this->_identifier) throw new Exception("You have not set the user identifier.");
		
		// get configs
		$config = $this->showConfigs(true);
		
		// choose database
		$database = ($config['config_database'] == "MuOnline" ? $this->muonline : $this->memuonline);
		
		// build query
		$data = array(
			'identifier' => $this->_identifier
		);
		$variables = array('{TABLE}','{COLUMN}','{USER_COLUMN}');
		$values = array($config['config_table'], $config['config_credits_col'], $config['config_user_col']);
		$query = str_replace($variables, $values, "SELECT {COLUMN} FROM {TABLE} WHERE {USER_COLUMN} = :identifier");
		
		// add credits
		$getCredits = $database->query_fetch_single($query, $data);
		if(!$getCredits) throw new Exception("There was an error getting the credits value.");
		
		return $getCredits[$config['config_credits_col']];
	}
}