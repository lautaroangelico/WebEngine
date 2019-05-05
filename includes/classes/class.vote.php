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

class Vote {
	
	private $_userid;
	private $_username;
	private $_votesideId;
	private $_accountInfo;
	private $_ip;
	
	private $_configXml = 'usercp.vote.xml';
	private $_active = true;
	private $_saveLogs = true;
	private $_creditConfig;
	
	function __construct() {
		
		// common class
		$this->common = new common();
		
		// load databases
		$this->muonline = Connection::Database('MuOnline');
		$this->memuonline = Connection::Database('Me_MuOnline');
		
		# Load Configurations
		$this->xml = simplexml_load_file(__PATH_MODULE_CONFIGS__ . $this->_configXml);
		if(!$this->xml) throw new Exception(lang('error_100'));
		
		$xmlConfig = convertXML($this->xml);
		$this->_active = $xmlConfig['active'];
		$this->_saveLogs = $xmlConfig['vote_save_logs'];
		$this->_creditConfig = $xmlConfig['credit_config'];
	}
	
	public function setUserid($userid) {
		if(!check_value($userid)) throw new Exception(lang('error_23', true));
		if(!Validator::UnsignedNumber($userid)) throw new Exception(lang('error_23', true));
		
		$accountInfo = $this->common->accountInformation($userid);
		if(!is_array($accountInfo)) throw new Exception(lang('error_23', true));
		
		$this->_accountInfo = $accountInfo;
		$this->_userid = $userid;
		$this->_username = $this->_accountInfo[_CLMN_USERNM_];
	}
	
	public function setVotesiteId($votesiteid) {
		if(!check_value($votesiteid)) throw new Exception(lang('error_23', true));
		if(!Validator::UnsignedNumber($votesiteid)) throw new Exception(lang('error_23', true));
		if(!$this->_siteExists($votesiteid)) throw new Exception(lang('error_23', true));
		
		$this->_votesideId = $votesiteid;
	}
	
	public function setIp($ip) {
		if(!check_value($ip)) throw new Exception(lang('error_101'));
		if(!Validator::Ip($ip)) throw new Exception(lang('error_101'));
		
		$this->_ip = $ip;
	}
	
	public function vote() {
		if(!check_value($this->_userid)) throw new Exception(lang('error_23', true));
		if(!check_value($this->_ip)) throw new Exception(lang('error_23', true));
		if(!check_value($this->_votesideId)) throw new Exception(lang('error_23', true));
		
		# check if voting is active
		if(!$this->_active) throw new Exception(lang('error_47', true));
		
		# check credit config
		if($this->_creditConfig == 0) throw new Exception(lang('error_102'));
		
		# check if user can vote
		if(!$this->_canUserVote()) throw new Exception(lang('error_15', true));
		
		# check if ip can vote
		if(!$this->_canIPVote()) throw new Exception(lang('error_16', true));
		
		# retrieve votesite data
		$voteSite = $this->retrieveVotesites($this->_votesideId);
		if(!is_array($voteSite)) throw new Exception(lang('error_23', true));
		
		$voteLink = $voteSite['votesite_link'];
		$creditsReward = $voteSite['votesite_reward'];
		
		# reward user
		$creditSystem = new CreditSystem();
		$creditSystem->setConfigId($this->_creditConfig);
		$configSettings = $creditSystem->showConfigs(true);
		switch($configSettings['config_user_col_id']) {
			case 'userid':
				$creditSystem->setIdentifier($this->_userid);
				break;
			case 'username':
				$creditSystem->setIdentifier($this->_username);
				break;
			default:
				throw new Exception(lang('error_73'));
		}
		$creditSystem->addCredits($creditsReward);
		
		# add vote record
		$this->_addRecord();
		
		# add vote log
		if($this->_saveLogs) {
			$this->_logVote();
		}
		
		# redirect
		redirect(3, $voteLink);
	}

	private function _canUserVote() {
		if(!check_value($this->_userid)) throw new Exception(lang('error_23', true));
		if(!check_value($this->_votesideId)) throw new Exception(lang('error_23', true));
		
		$query = "SELECT * FROM ".WEBENGINE_VOTES." WHERE user_id = ? AND vote_site_id = ?";
		$check = $this->memuonline->query_fetch_single($query, array($this->_userid, $this->_votesideId));
		
		if(!is_array($check)) return true;
		if($this->_timePassed($check['timestamp'])) {
			if($this->_removeRecord($check['id'])) return true;
		}
	}
	
	private function _canIPVote() {
		if(!check_value($this->_ip)) throw new Exception(lang('error_23', true));
		if(!check_value($this->_votesideId)) throw new Exception(lang('error_23', true));
		
		$query = "SELECT * FROM ".WEBENGINE_VOTES." WHERE user_ip = ? AND vote_site_id = ?";
		$check = $this->memuonline->query_fetch_single($query, array($this->_ip, $this->_votesideId));
		
		if(!is_array($check)) return true;
		if($this->_timePassed($check['timestamp'])) {
			if($this->_removeRecord($check['id'])) return true;
		}
	}
	
	
	private function _addRecord() {
		if(!check_value($this->_userid)) throw new Exception(lang('error_23', true));
		if(!check_value($this->_ip)) throw new Exception(lang('error_23', true));
		if(!check_value($this->_votesideId)) throw new Exception(lang('error_23', true));
		
		$voteSiteInfo = $this->retrieveVotesites($this->_votesideId);
		if(!is_array($voteSiteInfo)) throw new Exception(lang('error_23', true));
		
		$timestamp = time() + $voteSiteInfo['votesite_time']*60*60;
		$data = array(
			$this->_userid,
			$this->_ip,
			$this->_votesideId,
			$timestamp
		);
		
		$add = $this->memuonline->query("INSERT INTO ".WEBENGINE_VOTES." (user_id, user_ip, vote_site_id, timestamp) VALUES (?, ?, ?, ?)", $data);
		if(!$add) throw new Exception(lang('error_23', true));
	}
	
	private function _removeRecord($id) {
		$remove = $this->memuonline->query("DELETE FROM ".WEBENGINE_VOTES." WHERE id = ?", array($id));
		if($remove) return true;
		return false;
	}
	
	private function _timePassed($timestamp) {
		if(time() > $timestamp) return true;
		return false;
	}
	
	private function _siteExists($id) {
		if(!check_value($id)) return;
		$check = $this->memuonline->query_fetch_single("SELECT * FROM ".WEBENGINE_VOTE_SITES." WHERE votesite_id = ?", array($id));
		if(is_array($check)) return true;
		return false;
	}
	
	private function _logVote() {
		if(!check_value($this->_userid)) throw new Exception(lang('error_23', true));
		if(!check_value($this->_votesideId)) throw new Exception(lang('error_23', true));
		
		$add_data = array(
			$this->_userid,
			$this->_votesideId,
			time()
		);
		
		$add_log = $this->memuonline->query("INSERT INTO ".WEBENGINE_VOTE_LOGS." (user_id,votesite_id,timestamp) VALUES (?,?,?)", $add_data);
		if(!$add_log) return false;
		return true;
	}
	
	public function addVotesite($title, $link, $reward, $time) {
		$result = $this->memuonline->query("INSERT INTO ".WEBENGINE_VOTE_SITES." (votesite_title,votesite_link,votesite_reward,votesite_time) VALUES (?,?,?,?)", array($title,$link,$reward,$time));
		if($result) return true;
	}
	
	public function deleteVotesite($id) {
		if(!$this->_siteExists($id)) return;
		$result = $this->memuonline->query("DELETE FROM ".WEBENGINE_VOTE_SITES." WHERE votesite_id = ?", array($id));
		if($result) return $result;
	}
	
	public function retrieveVotesites($id=null) {
		if(check_value($id)) return $this->memuonline->query_fetch_single("SELECT * FROM ".WEBENGINE_VOTE_SITES." WHERE votesite_id = ?", array($id));
		return $this->memuonline->query_fetch("SELECT * FROM ".WEBENGINE_VOTE_SITES." ORDER BY votesite_id ASC");
	}

}