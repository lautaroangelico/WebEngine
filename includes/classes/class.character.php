<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.6
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2025 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

class Character {
	
	protected $_classData;
	
	protected $_userid;
	protected $_username;
	protected $_character;
	
	protected $_unstickMap = 0;
	protected $_unstickCoordX = 125;
	protected $_unstickCoordY = 125;
	
	protected $_clearPkLevel = 3;
	
	protected $_skilEnhanceTreeLevel = 800;
	
	protected $_strength = 0;
	protected $_agility = 0;
	protected $_vitality = 0;
	protected $_energy = 0;
	protected $_command = 0;
	
	protected $muonline;
	protected $common;
	
	function __construct() {
		
		// load databases
		$this->muonline = Connection::Database('MuOnline');
		
		// common
		$this->common = new common();
		
		// class data
		$classData = custom('character_class');
		if(!is_array($classData)) throw new Exception(lang('error_108'));
		$this->_classData = $classData;
		
	}
	
	public function setUserid($userid) {
		if(!Validator::UnsignedNumber($userid)) throw new Exception(lang('error_111'));
		$this->_userid = $userid;
	}
	
	public function setUsername($username) {
		if(!Validator::UsernameLength($username)) throw new Exception(lang('error_112'));
		$this->_username = $username;
	}
	
	public function setCharacter($character) {
		$this->_character = $character;
	}
	
	public function setStrength($value) {
		if(!Validator::UnsignedNumber($value)) throw new Exception(lang('error_122'));
		$this->_strength = $value;
	}
	
	public function setAgility($value) {
		if(!Validator::UnsignedNumber($value)) throw new Exception(lang('error_122'));
		$this->_agility = $value;
	}
	
	public function setVitality($value) {
		if(!Validator::UnsignedNumber($value)) throw new Exception(lang('error_122'));
		$this->_vitality = $value;
	}
	
	public function setEnergy($value) {
		if(!Validator::UnsignedNumber($value)) throw new Exception(lang('error_122'));
		$this->_energy = $value;
	}
	
	public function setCommand($value) {
		if(!Validator::UnsignedNumber($value)) throw new Exception(lang('error_122'));
		$this->_command = $value;
	}
	
	public function CharacterReset() {
		// filters
		if(!check_value($this->_username)) throw new Exception(lang('error_21'));
		if(!check_value($this->_character)) throw new Exception(lang('error_21'));
		if(!check_value($this->_userid)) throw new Exception(lang('error_21'));
		if(!$this->CharacterExists($this->_character)) throw new Exception(lang('error_32'));
		if(!$this->CharacterBelongsToAccount($this->_character, $this->_username)) throw new Exception(lang('error_32'));
		
		// check online status
		$Account = new Account();
		if($Account->accountOnline($this->_username)) throw new Exception(lang('error_14'));
		
		// character data
		$characterData = $this->CharacterData($this->_character);
		
		// next reset
		$resetNumber = $characterData["ResetCount"]+1;
		
		// level requirement
		if(mconfig('required_level') >= 1) {
			if($characterData["cLevel"] < mconfig('required_level')) throw new Exception(lang('error_33'));
		}
		
		// maximum resets
		$maxResets = mconfig('maximum_resets');
		if($maxResets > 0) {
			if($resetNumber > $maxResets) throw new Exception(lang('error_127'));
		}
		
		// stats
		$clearStats = mconfig('keep_stats') == 1 ? false : true;
		
		// points
		$newLevelUpPoints = mconfig('points_reward') >= 1 ? mconfig('points_reward') : 0;
		if(mconfig('multiply_points_by_resets') == 1) {
			$newLevelUpPoints = $newLevelUpPoints*$resetNumber;
		}
		
		// existing lvl up points (only when keeping stats)
		if(!$clearStats) {
			$newLevelUpPoints += $characterData["LevelUpPoint"];
		}
		
		// class
		$revertClass = mconfig('revert_class_evolution') == 1 ? true : false;
		if($revertClass) {
			if(!array_key_exists('class_group', $this->_classData[$characterData["Class"]])) throw new Exception(lang('error_128'));
			$classGroup = $this->_classData[$characterData["Class"]]['class_group'];
		}
		
		// zen requirement
		$zenRequirement = mconfig('zen_cost');
		if($zenRequirement > 0) if($characterData["Money"] < $zenRequirement) throw new Exception(lang('error_34'));
		$newZen = $characterData["Money"]-$zenRequirement;
		
		// credit requirement
		$creditConfig = mconfig('credit_config');
		$creditCost = mconfig('credit_cost');
		if($creditCost > 0 && $creditConfig != 0) {
			$creditSystem = new CreditSystem();
			$creditSystem->setConfigId($creditConfig);
			$configSettings = $creditSystem->showConfigs(true);
			switch($configSettings['config_user_col_id']) {
				case 'userid':
					$creditSystem->setIdentifier($this->_userid);
					break;
				case 'username':
					$creditSystem->setIdentifier($this->_username);
					break;
				case 'character':
					$creditSystem->setIdentifier($this->_character);
					break;
				default:
					throw new Exception("Invalid identifier (credit system).");
			}
			if($creditSystem->getCredits() < $creditCost) throw new Exception(langf('error_126', array($configSettings['config_title'])));
		}
		
		// base stats
		$base_stats = $this->_getClassBaseStats($characterData["Class"]);
		
		// inventory
		$clearInventory = mconfig('clear_inventory') == 1 ? true : false;
		
		// query data
		if($revertClass) $data['class'] = $classGroup;
		if($clearStats) $data['str'] = $base_stats['str'];
		if($clearStats) $data['agi'] = $base_stats['agi'];
		if($clearStats) $data['vit'] = $base_stats['vit'];
		if($clearStats) $data['ene'] = $base_stats['ene'];
		if($clearStats) $data['cmd'] = $base_stats['cmd'];
		$data['points'] = $newLevelUpPoints;
		if($zenRequirement > 0) $data['zen'] = $newZen;
		$data['name'] = $characterData["Name"];
		
		// query
		$query = "UPDATE Character SET ";
		$query .= "cLevel = 1, ";
		if($revertClass) $query .= "Class = :class, ";
		if($revertClass) $query .= "Quest = NULL, ";
		if($clearStats) $query .= "Strength = :str, ";
		if($clearStats) $query .= "Dexterity = :agi, ";
		if($clearStats) $query .= "Vitality = :vit, ";
		if($clearStats) $query .= "Energy = :ene, ";
		if($clearStats) $query .= "Leadership = :cmd, ";
		if($zenRequirement > 0) $query .= "Money = :zen, ";
		if($clearInventory) $query .= "Inventory = NULL, ";
		$query .= "LevelUpPoint = :points, ";
		$query .= "ResetCount = ResetCount+1 ";
		$query .= "WHERE Name = :name";
		
		// reset
		$result = $this->muonline->query($query, $data);
		if(!$result) throw new Exception(lang('error_23'));
		
		// subtract credits
		if($creditCost > 0 && $creditConfig != 0) $creditSystem->subtractCredits($creditCost);
		
		// reward credits
		$creditRewardConfig = mconfig('credit_reward_config');
		$creditReward = mconfig('credit_reward');
		if($creditReward > 0 && $creditRewardConfig != 0) {
			$creditSystem = new CreditSystem();
			$creditSystem->setConfigId($creditRewardConfig);
			$configSettings = $creditSystem->showConfigs(true);
			switch($configSettings['config_user_col_id']) {
				case 'userid':
					$creditSystem->setIdentifier($this->_userid);
					break;
				case 'username':
					$creditSystem->setIdentifier($this->_username);
					break;
				case 'character':
					$creditSystem->setIdentifier($this->_character);
					break;
				default:
					throw new Exception("Invalid identifier (credit system).");
			}
			$creditSystem->addCredits($creditReward);
		}
		
		// success
		message('success', lang('success_8'));
	}
	
	public function CharacterResetStats() {
		// filters
		if(!check_value($this->_username)) throw new Exception(lang('error_21'));
		if(!check_value($this->_character)) throw new Exception(lang('error_21'));
		if(!check_value($this->_userid)) throw new Exception(lang('error_21'));
		if(!$this->CharacterExists($this->_character)) throw new Exception(lang('error_35'));
		if(!$this->CharacterBelongsToAccount($this->_character, $this->_username)) throw new Exception(lang('error_35'));
		
		// check online status
		$Account = new Account();
		if($Account->accountOnline($this->_username)) throw new Exception(lang('error_14'));
		
		// character data
		$characterData = $this->CharacterData($this->_character);
		
		// zen requirement
		$zenRequirement = mconfig('zen_cost');
		
		// credit requirement
		$creditConfig = mconfig('credit_config');
		$creditCost = mconfig('credit_cost');
		if($creditCost > 0 && $creditConfig != 0) {
			$creditSystem = new CreditSystem();
			$creditSystem->setConfigId($creditConfig);
			$configSettings = $creditSystem->showConfigs(true);
			switch($configSettings['config_user_col_id']) {
				case 'userid':
					$creditSystem->setIdentifier($this->_userid);
					break;
				case 'username':
					$creditSystem->setIdentifier($this->_username);
					break;
				case 'character':
					$creditSystem->setIdentifier($this->_character);
					break;
				default:
					throw new Exception("Invalid identifier (credit system).");
			}
			if($creditSystem->getCredits() < $creditCost) throw new Exception(langf('error_113', array($configSettings['config_title'])));
		}
		
		// check zen
		if($zenRequirement > 0) if($characterData["Money"] < $zenRequirement) throw new Exception(lang('error_34'));
		
		// base stats
		$base_stats = $this->_getClassBaseStats($characterData["Class"]);
		$base_stats_points = array_sum($base_stats);
		
		// calculate new level up points
		$levelUpPoints = $characterData["Strength"]+$characterData["Dexterity"]+$characterData["Vitality"]+$characterData["Energy"];
		if(array_key_exists("Leadership", $characterData)) {
			$levelUpPoints += $characterData['Leadership'];
		}
		if($base_stats_points > 0) {
			$levelUpPoints -= $base_stats_points;
		}
		
		// query data
		$data = array_merge(
			array(
				'player' => $characterData["Name"],
				'points' => $levelUpPoints,
				'zen' => $zenRequirement,
			),
			$base_stats
		);
		
		// query
		$query = "UPDATE Character SET Strength = :str, Dexterity = :agi, Vitality = :vit, Energy = :ene";
		if(array_key_exists("Leadership", $characterData)) $query .= ", Leadership = :cmd";
		$query .= ", Money = Money - :zen";
		$query .= ", LevelUpPoint = LevelUpPoint + :points WHERE Name = :player";
		
		// reset stats
		$result = $this->muonline->query($query, $data);
		if(!$result) throw new Exception(lang('error_21'));
		
		// subtract credits
		if($creditCost > 0 && $creditConfig != 0) $creditSystem->subtractCredits($creditCost);
		
		// success
		message('success', lang('success_9'));
	}
	
	public function CharacterClearPK() {
		// filters
		if(!check_value($this->_username)) throw new Exception(lang('error_21'));
		if(!check_value($this->_character)) throw new Exception(lang('error_21'));
		if(!check_value($this->_userid)) throw new Exception(lang('error_21'));
		if(!$this->CharacterExists($this->_character)) throw new Exception(lang('error_36'));
		if(!$this->CharacterBelongsToAccount($this->_character, $this->_username)) throw new Exception(lang('error_36'));
		
		// check online status
		$Account = new Account();
		if($Account->accountOnline($this->_username)) throw new Exception(lang('error_14'));
		
		// character data
		$characterData = $this->CharacterData($this->_character);
		
		// check pk status
		if($characterData["PkLevel"] == $this->_clearPkLevel) throw new Exception(lang('error_117'));
		
		// zen requirement
		$zenRequirement = mconfig('zen_cost');
		
		// credit requirement
		$creditConfig = mconfig('credit_config');
		$creditCost = mconfig('credit_cost');
		if($creditCost > 0 && $creditConfig != 0) {
			$creditSystem = new CreditSystem();
			$creditSystem->setConfigId($creditConfig);
			$configSettings = $creditSystem->showConfigs(true);
			switch($configSettings['config_user_col_id']) {
				case 'userid':
					$creditSystem->setIdentifier($this->_userid);
					break;
				case 'username':
					$creditSystem->setIdentifier($this->_username);
					break;
				case 'character':
					$creditSystem->setIdentifier($this->_character);
					break;
				default:
					throw new Exception("Invalid identifier (credit system).");
			}
			if($creditSystem->getCredits() < $creditCost) throw new Exception(langf('error_116', array($configSettings['config_title'])));
		}
		
		// check zen
		if($zenRequirement > 0) if($characterData["Money"] < $zenRequirement) throw new Exception(lang('error_34'));
		
		// query data
		$data = array(
			'player' => $characterData["Name"],
			'pklevel' => $this->_clearPkLevel,
			'zen' => $zenRequirement,
		);
		
		// query
		$query = "UPDATE Character SET PkLevel = :pklevel, PkTime = 0, Money = Money - :zen WHERE Name = :player";
		
		// clear pk
		$result = $this->muonline->query($query, $data);
		if(!$result) throw new Exception(lang('error_21'));
		
		// subtract credits
		if($creditCost > 0 && $creditConfig != 0) $creditSystem->subtractCredits($creditCost);
		
		// success
		message('success', lang('success_10'));
	}
	
	public function CharacterUnstick() {
		// filters
		if(!check_value($this->_username)) throw new Exception(lang('error_21'));
		if(!check_value($this->_character)) throw new Exception(lang('error_21'));
		if(!check_value($this->_userid)) throw new Exception(lang('error_21'));
		if(!$this->CharacterExists($this->_character)) throw new Exception(lang('error_37'));
		if(!$this->CharacterBelongsToAccount($this->_character, $this->_username)) throw new Exception(lang('error_37'));
		
		// check online status
		$Account = new Account();
		if($Account->accountOnline($this->_username)) throw new Exception(lang('error_14'));
		
		// character data
		$characterData = $this->CharacterData($this->_character);
		
		// check position
		if($characterData["MapNumber"] == $this->_unstickMap) {
			if($characterData['MapPosX'] == $this->_unstickCoordX && $characterData['MapPosY'] == $this->_unstickCoordY) throw new Exception(lang('error_115'));
		}
		
		// zen requirement
		$zenRequirement = mconfig('zen_cost');
		
		// credit requirement
		$creditConfig = mconfig('credit_config');
		$creditCost = mconfig('credit_cost');
		if($creditCost > 0 && $creditConfig != 0) {
			$creditSystem = new CreditSystem();
			$creditSystem->setConfigId($creditConfig);
			$configSettings = $creditSystem->showConfigs(true);
			switch($configSettings['config_user_col_id']) {
				case 'userid':
					$creditSystem->setIdentifier($this->_userid);
					break;
				case 'username':
					$creditSystem->setIdentifier($this->_username);
					break;
				case 'character':
					$creditSystem->setIdentifier($this->_character);
					break;
				default:
					throw new Exception("Invalid identifier (credit system).");
			}
			if($creditSystem->getCredits() < $creditCost) throw new Exception(langf('error_114', array($configSettings['config_title'])));
		}
		
		// check zen
		if($zenRequirement > 0) if($characterData["Money"] < $zenRequirement) throw new Exception(lang('error_34'));
		
		// deduct zen
		if($zenRequirement > 0) if(!$this->DeductZEN($this->_character, $zenRequirement)) throw new Exception(lang('error_34'));
		
		// move character
		$update = $this->_moveCharacter($this->_character, $this->_unstickMap, $this->_unstickCoordX, $this->_unstickCoordY);
		if(!$update) throw new Exception(lang('error_21'));
		
		// subtract credits
		if($creditCost > 0 && $creditConfig != 0) $creditSystem->subtractCredits($creditCost);
		
		// success
		message('success', lang('success_11'));
	}
	
	public function CharacterClearSkillTree() {
		// filters
		if(!check_value($this->_username)) throw new Exception(lang('error_21'));
		if(!check_value($this->_character)) throw new Exception(lang('error_21'));
		if(!check_value($this->_userid)) throw new Exception(lang('error_21'));
		if(!$this->CharacterExists($this->_character)) throw new Exception(lang('error_38'));
		if(!$this->CharacterBelongsToAccount($this->_character, $this->_username)) throw new Exception(lang('error_38'));
		
		// check online status
		$Account = new Account();
		if($Account->accountOnline($this->_username)) throw new Exception(lang('error_14'));
		
		// character data
		$characterData = $this->CharacterData($this->_character);
		
		// check required level (regular)
		if($characterData["cLevel"] < mconfig('required_level')) throw new Exception(lang('error_120'));
		
		// character master level data
		$characterMasterLvlData = $this->getMasterLevelInfo($this->_character);
		if(!is_array($characterMasterLvlData)) throw new Exception(lang('error_119'));
		
		// check required level (master)
		if($characterMasterLvlData["MasterLevel"] < mconfig('required_master_level')) throw new Exception(lang('error_121'));
		
		// combined character level
		$characterLevel = $characterData["cLevel"]+$characterMasterLvlData["MasterLevel"];
		
		// skill enhancement tree points
		$skillEnhancementPoints = 0;
		
		// skill enhancement support
		if(defined('MasterSkillTreeExt')) {
			$skillEnhancementTreeEnabled = array_key_exists("MasterSkillTreeExt", $characterMasterLvlData) ? true : false;
		}
		
		// skill enhancement points
		if($skillEnhancementTreeEnabled) {
			if($characterLevel > $this->_skilEnhanceTreeLevel) {
				$skillEnhancementPoints = $characterLevel-$this->_skilEnhanceTreeLevel;
			}
		}
		
		// zen requirement
		$zenRequirement = mconfig('zen_cost');
		
		// credit requirement
		$creditConfig = mconfig('credit_config');
		$creditCost = mconfig('credit_cost');
		if($creditCost > 0 && $creditConfig != 0) {
			$creditSystem = new CreditSystem();
			$creditSystem->setConfigId($creditConfig);
			$configSettings = $creditSystem->showConfigs(true);
			switch($configSettings['config_user_col_id']) {
				case 'userid':
					$creditSystem->setIdentifier($this->_userid);
					break;
				case 'username':
					$creditSystem->setIdentifier($this->_username);
					break;
				case 'character':
					$creditSystem->setIdentifier($this->_character);
					break;
				default:
					throw new Exception("Invalid identifier (credit system).");
			}
			if($creditSystem->getCredits() < $creditCost) throw new Exception(langf('error_118', array($configSettings['config_title'])));
		}
		
		// check zen
		if($zenRequirement > 0) if($characterData["Money"] < $zenRequirement) throw new Exception(lang('error_34'));
		
		// data
		$data = array(
			'player' => $this->_character,
			'masterpoints' => $characterMasterLvlData["MasterLevel"]-$skillEnhancementPoints,
		);
		
		if($skillEnhancementTreeEnabled && $skillEnhancementPoints > 0) {
			$data['skillenhancementpoints'] = $skillEnhancementPoints;
		}
		
		// query
		$query = "UPDATE ".'MasterSkillTree'." SET MasterPoint = :masterpoints";
		if(defined('MasterSkillTreeExt')) if(array_key_exists("MasterSkillTreeExt", $characterMasterLvlData)) $query .= ", MasterSkillTreeExt = 0";
		if(defined('MasterSkillTreeExt')) if(array_key_exists("MasterSkillTreeExt", $characterMasterLvlData)) $query .= ", MasterSkillTreeExt = 0";
		if($skillEnhancementTreeEnabled && $skillEnhancementPoints > 0) $query .= ", MasterSkillTreeExt = :skillenhancementpoints";
		$query .= " WHERE Name = :player";
		
		// clear magic list (skills)
		$resetMagicList = $this->_resetMagicList($this->_character);
		if(!$resetMagicList) throw new Exception(lang('error_21'));
		
		// clear master skill tree
		$clearMasterSkillTree = $this->muonline->query($query, $data);
		if(!$clearMasterSkillTree) throw new Exception(lang('error_21'));
		
		// deduct zen
		if($zenRequirement > 0) if(!$this->DeductZEN($this->_character, $zenRequirement)) throw new Exception(lang('error_34'));
		
		// subtract credits
		if($creditCost > 0 && $creditConfig != 0) $creditSystem->subtractCredits($creditCost);
		
		// success
		message('success', lang('success_12'));
	}
	
	public function CharacterAddStats() {
		// filters
		if(!check_value($this->_username)) throw new Exception(lang('error_21'));
		if(!check_value($this->_character)) throw new Exception(lang('error_21'));
		if(!check_value($this->_userid)) throw new Exception(lang('error_21'));
		if(!$this->CharacterExists($this->_character)) throw new Exception(lang('error_64'));
		if(!$this->CharacterBelongsToAccount($this->_character, $this->_username)) throw new Exception(lang('error_64'));
		
		// points
		$pointsTotal = $this->_strength+$this->_agility+$this->_vitality+$this->_energy+$this->_command;
		
		// points minimum limit
		if($pointsTotal < mconfig('minimum_limit')) throw new Exception(langf('error_54', array(mconfig('minimum_limit'))));
		
		// check online status
		$Account = new Account();
		if($Account->accountOnline($this->_username)) throw new Exception(lang('error_14'));
		
		// character data
		$characterData = $this->CharacterData($this->_character);
		
		// check level up points
		if($characterData["LevelUpPoint"] < $pointsTotal) throw new Exception(lang('error_51'));
		
		// new stats
		$str = $characterData["Strength"]+$this->_strength;
		$agi = $characterData["Dexterity"]+$this->_agility;
		$vit = $characterData["Vitality"]+$this->_vitality;
		$ene = $characterData["Energy"]+$this->_energy;
		
		// check stat limits
		if($str > mconfig('max_stats')) throw new Exception(langf('error_53', array(number_format(mconfig('max_stats')))));
		if($agi > mconfig('max_stats')) throw new Exception(langf('error_53', array(number_format(mconfig('max_stats')))));
		if($vit > mconfig('max_stats')) throw new Exception(langf('error_53', array(number_format(mconfig('max_stats')))));
		if($ene > mconfig('max_stats')) throw new Exception(langf('error_53', array(number_format(mconfig('max_stats')))));
		
		// cmd
		$cmd = 0;
		if(array_key_exists("Leadership", $characterData) && $this->_command >= 1) {
			if(!in_array($characterData["Class"], custom('character_cmd'))) throw new Exception(lang('error_52'));
			$cmd = $characterData["Leadership"]+$this->_command;
			if($cmd > mconfig('max_stats')) throw new Exception(langf('error_53', array(number_format(mconfig('max_stats')))));
		}
		
		// check required level (regular)
		if($characterData["cLevel"] < mconfig('required_level')) throw new Exception(lang('error_123'));
		
		if(mconfig('required_master_level') >= 1) {
			// character master level data
			$characterMasterLvlData = $this->getMasterLevelInfo($this->_character);
			if(!is_array($characterMasterLvlData)) throw new Exception(lang('error_119'));
			
			// check required level (master)
			if($characterMasterLvlData["MasterLevel"] < mconfig('required_master_level')) throw new Exception(lang('error_124'));
		}
		
		// zen requirement
		$zenRequirement = mconfig('zen_cost');
		
		// check zen
		if($zenRequirement > 0) if($characterData["Money"] < $zenRequirement) throw new Exception(lang('error_34'));
		
		// credit requirement
		$creditConfig = mconfig('credit_config');
		$creditCost = mconfig('credit_cost');
		if($creditCost > 0 && $creditConfig != 0) {
			$creditSystem = new CreditSystem();
			$creditSystem->setConfigId($creditConfig);
			$configSettings = $creditSystem->showConfigs(true);
			switch($configSettings['config_user_col_id']) {
				case 'userid':
					$creditSystem->setIdentifier($this->_userid);
					break;
				case 'username':
					$creditSystem->setIdentifier($this->_username);
					break;
				case 'character':
					$creditSystem->setIdentifier($this->_character);
					break;
				default:
					throw new Exception("Invalid identifier (credit system).");
			}
			if($creditSystem->getCredits() < $creditCost) throw new Exception(langf('error_125', array($configSettings['config_title'])));
		}
		
		// deduct zen
		if($zenRequirement > 0) if(!$this->DeductZEN($this->_character, $zenRequirement)) throw new Exception(lang('error_34'));
		
		// add stats
		$data = array(
			'str' => $str,
			'agi' => $agi,
			'vit' => $vit,
			'ene' => $ene,
			'total' => $pointsTotal,
			'player' => $characterData["Name"],
		);
		if($cmd >= 1) $data['cmd'] = $cmd;
		
		$query = "UPDATE Character SET LevelUpPoint = LevelUpPoint - :total, ";
		if($cmd >= 1) $query .= "Leadership" . " = :cmd, ";
		$query .= "Strength" . " = :str, ";
		$query .= "Dexterity" . " = :agi, ";
		$query .= "Vitality" . " = :vit, ";
		$query .= "Energy" . " = :ene";
		$query .= " WHERE Name = :player";
		
		$result = $this->muonline->query($query, $data);
		if(!$result) throw new Exception(lang('error_21'));
		
		// subtract credits
		if($creditCost > 0 && $creditConfig != 0) $creditSystem->subtractCredits($creditCost);
		
		// success
		message('success', lang('success_17'));
	}	
	
	public function AccountCharacter($username) {
		if(!check_value($username)) return;
		if(!Validator::UsernameLength($username)) return;
		if(!Validator::AlphaNumeric($username)) return;
		
		$result = $this->muonline->query_fetch("SELECT Name FROM Character WHERE AccountID = ?", array($username));
		if(!is_array($result)) return;
		
		foreach($result as $row) {
			if(!check_value($row["Name"])) continue;
			$return[] = $row["Name"];
		}
		
		if(!is_array($return)) return;
		return $return;
	}
	
	public function CharacterData($character_name) {
		if(!check_value($character_name)) return;
		$result = $this->muonline->query_fetch_single("SELECT * FROM Character WHERE Name = ?", array($character_name));
		if(!is_array($result)) return;
		return $result;
		
	}
	
	public function CharacterBelongsToAccount($character_name,$username) {
		if(!check_value($character_name)) return;
		if(!check_value($username)) return;
		if(!Validator::UsernameLength($username)) return;
		if(!Validator::AlphaNumeric($username)) return;
		$characterData = $this->CharacterData($character_name);
		if(!is_array($characterData)) return;
		if(strtolower($characterData["AccountID"]) != strtolower($username)) return;
		return true;
		
	}
	
	public function CharacterExists($character_name) {
		if(!check_value($character_name)) return;
		$check = $this->muonline->query_fetch_single("SELECT * FROM Character WHERE Name = ?", array($character_name));
		if(!is_array($check)) return;
		return true;
	}
	
	public function DeductZEN($character_name,$zen_amount) {
		if(!check_value($character_name)) return;
		if(!check_value($zen_amount)) return;
		if(!Validator::UnsignedNumber($zen_amount)) return;
		if($zen_amount < 1) return;
		if(!$this->CharacterExists($character_name)) return;
		$characterData = $this->CharacterData($character_name);
		if(!is_array($characterData)) return;
		if($characterData["Money"] < $zen_amount) return;
		$deduct = $this->muonline->query("UPDATE Character SET Money = Money - ? WHERE Name = ?", array($zen_amount, $character_name));
		if(!$deduct) return;
		return true;
	}
	
	public function AccountCharacterIDC($username) {
		if(!check_value($username)) return;
		if(!Validator::UsernameLength($username)) return;
		if(!Validator::AlphaNumeric($username)) return;
		$data = $this->muonline->query_fetch_single("SELECT * FROM Account WHERE ID = ?", array($username));
		if(!is_array($data)) return;
		return $data["GameIDC"];
	}
	
	// To be removed (backwards compatibility)
	public function GenerateCharacterClassAvatar($code=0,$alt=true,$img_tags=true) {
		return getPlayerClassAvatar($code, $img_tags, $alt, 'tables-character-class-img');
	}
	
	public function getMasterLevelInfo($character_name) {
		if(!check_value($character_name)) return;
		if(!$this->CharacterExists($character_name)) return;
		$CharInfo = $this->muonline->query_fetch_single("SELECT * FROM MasterSkillTree WHERE Name = ?", array($character_name));
		if(!is_array($CharInfo)) return;
		return $CharInfo;
	}
	
	protected function _moveCharacter($character_name,$map=0,$x=125,$y=125) {
		if(!check_value($character_name)) return;
		$move = $this->muonline->query("UPDATE Character SET MapNumber = ?, MapPosX = ?, MapPosY = ? WHERE Name = ?", array($map, $x, $y, $character_name));
		if(!$move) return;
		return true;
	}
	
	protected function _resetMagicList($character) {
		$result = $this->muonline->query("UPDATE Character SET MagicList = null WHERE Name = ?", array($character));
		if(!$result) return;
		return true;
	}
	
	protected function _getClassBaseStats($class) {
		if(!array_key_exists($class, $this->_classData)) throw new Exception(lang('error_109'));
		if(!array_key_exists('base_stats', $this->_classData[$class])) throw new Exception(lang('error_110'));
		if(!is_array($this->_classData[$class]['base_stats'])) throw new Exception(lang('error_110'));
		return $this->_classData[$class]['base_stats'];
	}
	
}