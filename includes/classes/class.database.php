<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.5
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2023 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

class dB {
	
	public $error;
	public $ok;
	public $dead;
	
	private $_enableErrorLogs = true;
	
	// what are you doing around here?
	function __construct($SQLHOST, $SQLPORT, $SQLDB, $SQLUSER, $SQLPWD, $SQLDRIVER) {
		try {
			
			if($SQLDRIVER == 3) {
				$this->db = new PDO("odbc:Driver={SQL Server};Server=".$SQLHOST.";Database=".$SQLDB."; Uid=".$SQLUSER.";Pwd=".$SQLPWD.";");
			} else {
				if($SQLDRIVER == 2) {
					$pdo_connect = "sqlsrv:Server=".$SQLHOST.",".$SQLPORT.";Database=".$SQLDB."";
				} else {
					$pdo_connect = 'dblib:host='.$SQLHOST.':'.$SQLPORT.';dbname='.$SQLDB;
				}
				$this->db = new PDO($pdo_connect, $SQLUSER, $SQLPWD);
			}

			
		} catch (PDOException $e) {
			$this->dead = true;
			$this->error = "PDOException: ".$e->getMessage();
		}
		
	}
	
	public function query($sql, $array='') {
		if(!is_array($array)) {
        	if($array == '') {
            	$array = array();
            } else {
        		$array = array($array);
            }
        }
		$query = $this->db->prepare($sql);
		if (!$query) {
			$this->error = $this->trow_error();
			$query->closeCursor();
			return false;
		} else {
			if($query->execute($array)) {
				$query->closeCursor();
				return true;
			} else {
				$this->error = $this->trow_error($query);
				return false;
			}
		}
	}
	
	public function query_fetch($sql, $array='') {
		if(!is_array($array)) {
        	if($array == '') {
            	$array = array();
            } else {
        		$array = array($array);
            }
        }
		$query = $this->db->prepare($sql);
		if (!$query) {
			$this->error = $this->trow_error();
			$query->closeCursor();
			return false;
		} else {
			if($query->execute($array)) {
				$result = $query->fetchAll(PDO::FETCH_ASSOC);
				$query->closeCursor();
				return (check_value($result)) ? $result : NULL;
			} else {
				$this->error = $this->trow_error($query);
				return false;
			}
		}
	}
	
	public function query_fetch_single($sql, $array='') {
		$result = $this->query_fetch($sql, $array);
		return (isset($result[0])) ? $result[0] : NULL;
	}
	
	private function trow_error($state='') {
		if(!check_value($state)) {
			$error = $this->db->errorInfo();
		} else {
			$error = $state->errorInfo();
		}
		
		$errorMessage = '['.date('Y/m/d h:i:s').'] [SQL '.$error[0].'] ['.$this->db->getAttribute(PDO::ATTR_DRIVER_NAME).' '.$error[1].'] > '.$error[2];
		if($this->_enableErrorLogs) @error_log($errorMessage . "\r\n", 3, WEBENGINE_DATABASE_ERRORLOG);
		return $errorMessage;
	}

}