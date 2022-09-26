<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.3.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2021 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

class WebEngineDatabase extends dB {

	function __construct($database='WebEngine.db') {
		try {
			
			$this->db = new PDO('sqlite:' . __PATH_INCLUDES__ . $database);
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
		} catch (PDOException $e) {
			$this->dead = true;
			$this->error = "PDOException: ".$e->getMessage();
		}
		
	}
	
	public function query($sql, $array=array()) {
		return parent::query($sql, $array);
	}
	
	public function query_fetch($sql, $array=array()) {
		return parent::query_fetch($sql, $array);
	}
	
	public function query_fetch_single($sql, $array=array()) {
		return parent::query_fetch_single($sql, $array);
	}

}