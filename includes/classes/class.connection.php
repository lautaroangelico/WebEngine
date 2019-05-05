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

class Connection {
	
	public static function Database($database='') {
		switch($database) {
			case 'MuOnline':
				$db = new dB(self::_config('SQL_DB_HOST'), self::_config('SQL_DB_PORT'), self::_config('SQL_DB_NAME'), self::_config('SQL_DB_USER'), self::_config('SQL_DB_PASS'), self::_config('SQL_PDO_DRIVER'));
				if($db->dead) {
					if(self::_config('error_reporting')) {
						throw new Exception($db->error);
					}
					throw new Exception('Connection to database failed ('.self::_config('SQL_DB_NAME').')');
				}
				return $db;
				break;
			case 'Me_MuOnline':
				if(!self::_config('SQL_USE_2_DB')) return self::Database('MuOnline');
				$db = new dB(self::_config('SQL_DB_HOST'), self::_config('SQL_DB_PORT'), self::_config('SQL_DB_2_NAME'), self::_config('SQL_DB_USER'), self::_config('SQL_DB_PASS'), self::_config('SQL_PDO_DRIVER'));
				if($db->dead) {
					if(self::_config('error_reporting')) {
						throw new Exception($db->error);
					}
					throw new Exception('Connection to database failed ('.self::_config('SQL_DB_2_NAME').')');
				}
				return $db;
				break;
			default:
				return;
		}
	}
	
	private static function _config($config) {
		$webengineConfig = webengineConfigs();
		if(!is_array($webengineConfig)) return;
		if(!array_key_exists($config, $webengineConfig)) return;
		return $webengineConfig[$config];
	}
	
}