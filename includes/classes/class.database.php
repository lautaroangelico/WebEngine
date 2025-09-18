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

class dB {
	
	public $error;
	public $ok;
	public $dead;
	
	private $_enableErrorLogs = true;
	
	protected $db;
	
	// what are you doing around here?
	function __construct($SQLHOST, $SQLPORT, $SQLDB, $SQLUSER, $SQLPWD, $SQLDRIVER) {
		try {
			// Validate required parameters
			if(empty($SQLHOST) || empty($SQLDB) || empty($SQLUSER)) {
				throw new PDOException("Missing required database connection parameters");
			}
			
			$pdo_connect = 'dblib:host='.$SQLHOST.':'.$SQLPORT.';dbname='.$SQLDB;
			if($SQLDRIVER == 2) {
				$pdo_connect = "sqlsrv:Server=".$SQLHOST.",".$SQLPORT.";Database=".$SQLDB."";
			}
			$this->db = new PDO($pdo_connect, $SQLUSER, $SQLPWD);
			
			// Set error mode to exception for better error handling
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// Keep prepared statements for better security (removed emulate_prepares = true)
			//$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
			// Mark connection as successful
			$this->ok = true;
			$this->dead = false;
			
		} catch (PDOException $e) {
			$this->dead = true;
			$this->ok = false;
			$this->error = "PDOException: ".$e->getMessage();
		}
		
	}
	
	public function query($sql, $array='') {
		// Check if database connection is alive
		if($this->dead || !$this->db) {
			$this->error = "Database connection is not available";
			return false;
		}
		
		if(!is_array($array)) {
        	if($array == '') {
            	$array = array();
            } else {
        		$array = array($array);
            }
        }
		$query = $this->db->prepare($sql);
		if (!$query) {
			$this->error = $this->throw_error();
			return false;
		} else {
			if($query->execute($array)) {
				$query->closeCursor();
				return true;
			} else {
				$this->error = $this->throw_error($query);
				$query->closeCursor();
				return false;
			}
		}
	}
	
	public function query_fetch($sql, $array='') {
		// Check if database connection is alive
		if($this->dead || !$this->db) {
			$this->error = "Database connection is not available";
			return false;
		}
		
		if(!is_array($array)) {
        	if($array == '') {
            	$array = array();
            } else {
        		$array = array($array);
            }
        }
		$query = $this->db->prepare($sql);
		if (!$query) {
			$this->error = $this->throw_error();
			return false;
		} else {
			if($query->execute($array)) {
				$result = $query->fetchAll(PDO::FETCH_ASSOC);
				$query->closeCursor();
				return (check_value($result)) ? $result : NULL;
			} else {
				$this->error = $this->throw_error($query);
				$query->closeCursor();
				return false;
			}
		}
	}
	
	public function query_fetch_single($sql, $array='') {
		$result = $this->query_fetch($sql, $array);
		return (isset($result[0])) ? $result[0] : NULL;
	}
	
	private function throw_error($state=null) {
		if(!check_value($state) || !is_object($state)) {
			$error = $this->db->errorInfo();
		} else {
			$error = $state->errorInfo();
		}
		
		$driverName = ($this->db) ? $this->db->getAttribute(PDO::ATTR_DRIVER_NAME) : 'Unknown';
		$errorMessage = '['.date('Y/m/d h:i:s').'] [SQL '.$error[0].'] ['.$driverName.' '.$error[1].'] > '.$error[2];
		if($this->_enableErrorLogs) @error_log($errorMessage . "\r\n", 3, WEBENGINE_DATABASE_ERRORLOG);
		return $errorMessage;
	}

}