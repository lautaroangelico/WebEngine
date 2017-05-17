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

class Vip {
	
	private $_packages = array(
		1 => array('name' => 'bronze', 'cost' => 0),
		2 => array('name' => 'silver', 'cost' => 0),
		3 => array('name' => 'gold', 'cost' => 0),
		4 => array('name' => 'platinum', 'cost' => 0)
	);
	
	private $_dateFormat = "Y-m-d H:i:s";
	private $_vipDays = 30;
	private $_accountInfo;
	private $_username;
	private $_vipData;
	private $_package;
	
	function __construct() {
		global $dB;
		
		# common class
		$this->common = new common($dB);
		
		# check files config
		if(strtolower(config('server_files',true)) != 'igcn') throw new Exception('This library is only compatible with IGCN server files.');
		
		# database
		$this->mu = $dB;
		
		# configs
		$vipConfigs = loadConfigurations('usercp.vip');
		if(!is_array($vipConfigs)) throw new Exception('There was a problem loading VIP configurations.');
		$this->config = $vipConfigs;
		
		if($this->config['credit_config'] == 0) throw new Exception('This module\'s credit configuration is not properly configured.');
		
		$this->_packages[1]['cost'] = (check_value($this->config['igcn_bronze_cost']) ? $this->config['igcn_bronze_cost'] : 0);
		$this->_packages[2]['cost'] = (check_value($this->config['igcn_silver_cost']) ? $this->config['igcn_silver_cost'] : 0);
		$this->_packages[3]['cost'] = (check_value($this->config['igcn_gold_cost']) ? $this->config['igcn_gold_cost'] : 0);
		$this->_packages[4]['cost'] = (check_value($this->config['igcn_platinum_cost']) ? $this->config['igcn_platinum_cost'] : 0);
	}
	
	public function setUserid($userid) {
		if(!check_value($userid)) throw new Exception(lang('error_25',true));
		$accountInfo = $this->common->accountInformation($userid);
		if(!is_array($accountInfo)) throw new Exception(lang('error_12',true));
		
		$this->_accountInfo = $accountInfo;
		$this->_username = $accountInfo[_CLMN_USERNM_];
		$this->_loadVipData();
	}
	
	private function _loadVipData() {
		if(!check_value($this->_username)) return;
		$result = $this->mu->query_fetch_single("SELECT * FROM "._TBL_VIP_." WHERE "._CLMN_VIP_ID_." = ?", array($this->_username));
		if(!is_array($result)) return;
		
		$this->_vipData = $result;
	}
	
	public function isVip() {
		if(!check_value($this->_username)) return;
		if(!check_value($this->_vipData)) return;
		if(!is_array($this->_vipData)) return;
		
		if(time() > strtotime($this->_vipData[_CLMN_VIP_DATE_])) return;
		return true;
	}
	
	public function getVipData() {
		if(!check_value($this->_username)) return;
		if(!check_value($this->_vipData)) return;
		if(!is_array($this->_vipData)) return;
		
		return $this->_vipData;
	}
	
	public function setPackage($id) {
		if(!array_key_exists($id, $this->_packages)) throw new Exception(lang('error_60', true));
		if($this->_packages[$id]['cost'] < 1) throw new Exception(lang('error_60', true));
		
		$this->_package = $id;
	}
	
	private function _subtractCredits($credits) {
		if(!check_value($this->_username)) return;
		if(!is_array($this->_accountInfo)) return;
		try {
			$creditSystem = new CreditSystem($this->common, new Character(), $this->mu);
			$creditSystem->setConfigId($this->config['credit_config']);
			$configSettings = $creditSystem->showConfigs(true);
			switch($configSettings['config_user_col_id']) {
				case 'userid':
					$creditSystem->setIdentifier($this->_accountInfo[_CLMN_MEMBID_]);
					break;
				case 'username':
					$creditSystem->setIdentifier($this->_accountInfo[_CLMN_USERNM_]);
					break;
				default:
					throw new Exception("Invalid identifier (credit system).");
			}
			$creditSystem->subtractCredits($credits);
		} catch(Exception $ex) {
			throw new Exception(lang('error_40',true));
		}
	}
	
	public function buyVip() {
		if(!check_value($this->_username)) throw new Exception(lang('error_25',true));
		if($this->isVip()) throw new Exception(lang('error_61',true));
		if(!check_value($this->_package)) throw new Exception(lang('error_25',true));
		
		# subtract credits
		$this->_subtractCredits($this->_packages[$this->_package]['cost']);
		
		# give vip
		$expirationDate = date($this->_dateFormat, time()+($this->_vipDays*86400));
		
		if(is_array($this->_vipData)) {
			# update
			$result = $this->mu->query("UPDATE "._TBL_VIP_." SET "._CLMN_VIP_DATE_." = ?, "._CLMN_VIP_TYPE_." = ? WHERE "._CLMN_VIP_ID_." = ?", array($expirationDate, $this->_package, $this->_username));
		} else {
			# insert
			$result = $this->mu->query("INSERT INTO "._TBL_VIP_." ("._CLMN_VIP_ID_.", "._CLMN_VIP_DATE_.", "._CLMN_VIP_TYPE_.") VALUES (?, ?, ?)", array($this->_username, $expirationDate, $this->_package));
		}
		
		if(!$result) throw new Exception(lang('error_23',true));
	}
	
	public function extendVip() {
		if(!check_value($this->_username)) throw new Exception(lang('error_25',true));
		if(!$this->isVip()) throw new Exception(lang('error_62',true));
		if(!check_value($this->_package)) throw new Exception(lang('error_25',true));
		
		# check if extending to the same type of vip
		if($this->_package != $this->_vipData[_CLMN_VIP_TYPE_]) throw new Exception(lang('error_63',true));
		
		# subtract credits
		$this->_subtractCredits($this->_packages[$this->_package]['cost']);
		
		# extend vip
		$expirationDate = date($this->_dateFormat, strtotime($this->_vipData[_CLMN_VIP_DATE_])+($this->_vipDays*86400));

		# update
		$result = $this->mu->query("UPDATE "._TBL_VIP_." SET "._CLMN_VIP_DATE_." = ? WHERE "._CLMN_VIP_ID_." = ?", array($expirationDate, $this->_username));
		
		if(!$result) throw new Exception(lang('error_23',true));
	}
}