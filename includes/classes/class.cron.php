<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.1
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

class CronManager {
	
	private $_api = 'cron.php';
	
	protected $_id;
	protected $_name;
	protected $_desc;
	protected $_file;
	protected $_interval;
	
	protected $_commonIntervals = array(
		60 => '1 minute',
		300 => '5 minutes',
		600 => '10 minutes',
		900 => '15 minutes',
		1800 => '30 minutes',
		3600 => '1 hour',
		7200 => '2 hours',
		14400 => '4 hours',
		21600 => '6 hours',
		43200 => '12 hours',
		86400 => '1 day ',
		604800 => '7 days',
		1296000 => '15 days',
		2592000 => '1 month',
		7776000 => '3 months',
		15552000 => '6 months',
		31104000 => '1 year',
	);
	
	function __construct() {
		
		$this->memuonline = Connection::Database('Me_MuOnline');
	}
	
	public function setId($id) {
		if(!Validator::UnsignedNumber($id)) throw new Exception(lang('error_49'));
		$this->_id = $id;
	}
	
	public function setName($name) {
		$this->_name = $name;
	}
	
	public function setDescription($desc) {
		$this->_desc = $desc;
	}
	
	public function setFile($file) {
		if(!$this->_cronFileExists($file)) throw new Exception(lang('error_50'));
		$this->_file = $file;
	}
	
	public function setInterval($interval) {
		$this->_interval = $interval;
	}
	
	public function getCronList() {
		$result = $this->memuonline->query_fetch("SELECT * FROM ".WEBENGINE_CRON." ORDER BY cron_id ASC");
		if(!is_array($result)) return;
		return $result;
	}
	
	public function enableCron() {
		$this->_setCronStatus(1);
	}
	
	public function disableCron() {
		$this->_setCronStatus(0);
	}
	
	public function resetCronLastRun() {
		if(!check_value($this->_id)) return;
		$result = $this->memuonline->query("UPDATE ".WEBENGINE_CRON." SET cron_last_run = NULL WHERE cron_id = ?", array($this->_id));
		if(!$result) throw new Exception($this->memuonline->error);
		return true;
	}
	
	public function deleteCron() {
		if(!check_value($this->_id)) return;
		$result = $this->memuonline->query("DELETE FROM ".WEBENGINE_CRON." WHERE cron_id = ?", array($this->_id));
		if(!$result) throw new Exception($this->memuonline->error);
		return true;
	}
	
	public function getCronApiUrl($id=null) {
		if(check_value($id)) return __PATH_API__ . $this->_api . '?key=' . config('cron_api_key',true) . '&id=' . $id;
		return __PATH_API__ . $this->_api . '?key=' . config('cron_api_key',true);
	}
	
	public function addCron() {
		if(!check_value($this->_name)) throw new Exception(lang('error_106'));
		if(!check_value($this->_file)) throw new Exception(lang('error_106'));
		if(!check_value($this->_interval)) throw new Exception(lang('error_106'));
		if($this->_cronAlreadyExists($this->_file)) throw new Exception(lang('error_107'));
		
		$data = array(
			$this->_name,
			$this->_file,
			$this->_interval,
			1,
			0,
			$this->_cronFileMd5($this->_file)
		);
		$result = $this->memuonline->query("INSERT INTO ".WEBENGINE_CRON." (cron_name, cron_file_run, cron_run_time, cron_status, cron_protected, cron_file_md5) VALUES (?, ?, ?, ?, ?, ?)", $data);
		if(!$result) throw new Exception($this->memuonline->error);
		return true;
	}
	
	public function enableAll() {
		$result = $this->memuonline->query("UPDATE ".WEBENGINE_CRON." SET cron_status = 1");
		if(!$result) throw new Exception($this->memuonline->error);
		return true;
	}
	
	public function disableAll() {
		$result = $this->memuonline->query("UPDATE ".WEBENGINE_CRON." SET cron_status = 0");
		if(!$result) throw new Exception($this->memuonline->error);
		return true;
	}
	
	public function resetAllLastRun() {
		$result = $this->memuonline->query("UPDATE ".WEBENGINE_CRON." SET cron_last_run = NULL");
		if(!$result) throw new Exception($this->memuonline->error);
		return true;
	}
	
	public function getCommonIntervals() {
		return $this->_commonIntervals;
	}
	
	public function listCronFiles($selected="") {
		$dir = opendir(__PATH_CRON__);
		while(($file = readdir($dir)) !== false) {
			if(filetype(__PATH_CRON__ . $file) == "file" && $file != ".htaccess" && $file != "cron.php") {
				
				if(check_value($selected) && $selected == $file) {
					$return[] = "<option value=\"$file\" selected=\"selected\">$file</option>";
				} else {
					$return[] = "<option value=\"$file\">$file</option>";
				}
			}
		}
		closedir($dir);
		return join('', $return);
	}
	
	protected function _cronFileExists($file) {
		if(!file_exists(__PATH_CRON__ . $file)) return;
		return true;
	}
	
	protected function _setCronStatus($status=1) {
		if(!check_value($this->_id)) return;
		$result = $this->memuonline->query("UPDATE ".WEBENGINE_CRON." SET cron_status = ? WHERE cron_id = ?", array($status, $this->_id));
		if(!$result) throw new Exception($this->memuonline->error);
		return true;
	}
	
	protected function _cronAlreadyExists($file) {
		$result = $this->memuonline->query_fetch_single("SELECT * FROM ".WEBENGINE_CRON." WHERE cron_file_run = ?", array($this->_file));
		if(!is_array($result)) return;
		return true;
	}
	
	protected function _cronFileMd5($file) {
		return md5_file(__PATH_CRON__ . $file);
	}
	
}