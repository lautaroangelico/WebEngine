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

class Validator{

	private static function textHit($string, $exclude=""){
		if(empty($exclude)) return false;
		if(is_array($exclude)){
			foreach($exclude as $text){
				if(strstr($string, $text)) return true;
			}
		}else{
			if(strstr($string, $exclude)) return true;
		}
		return false;
	}

	private static function numberBetween($integer, $max=null, $min=0){
		if(is_numeric($min) && $integer < $min) return false;
		if(is_numeric($max) && $integer > $max) return false;
		return true;
	}

	public static function Email($string, $exclude=""){
		if(self::textHit($string, $exclude)) return false;
		return (bool)preg_match("/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i", $string);
	}

	public static function Url($string, $exclude=""){
		if(self::textHit($string, $exclude)) return false;
		return (bool)preg_match("/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i", $string); 
	}

	public static function Ip($string){
		if(!filter_var($string, FILTER_VALIDATE_IP)) return false;
		return true;
	}

	public static function Number($integer, $max=null, $min=0){
		if(preg_match("/^\-?\+?[0-9e1-9]+$/",$integer)){
			if(!self::numberBetween($integer, $max, $min)) return false;
			return true;
		}
		return false;
	}

	public static function UnsignedNumber($integer){
		return (bool)preg_match("/^\+?[0-9]+$/",$integer);
	}

	public static function Float($string){
		return (bool)($string==strval(floatval($string)))? true : false;
	}

	public static function Alpha($string){
		return (bool)preg_match("/^[a-zA-Z]+$/", $string);	
	}

	public static function AlphaNumeric($string){
		return (bool)preg_match("/^[0-9a-zA-Z]+$/", $string);	
	}

	public static function Chars($string, $allowed=array("a-z")){
		return (bool)preg_match("/^[" . implode("", $allowed) . "]+$/", $string);	
	}
	
	public static function Length($string, $max=null, $min=0){
		$length = strlen($string);
		if(!self::numberBetween($length, $max, $min)) return false;
		return true;
	}

    public static function Date($string){
        $date = date('Y', strtotime($string));
        return ($date == "1970" || $date == '') ? false : true;
    }
	
	public static function UsernameLength($string){
		if((strlen($string) < config('username_min_len',true)) || (strlen($string) > config('username_max_len',true))) {
			return false;
		} else {
			return true;
		}
	}
	
	public static function PasswordLength($string){
		if((strlen($string) < config('password_min_len',true)) || (strlen($string) > config('password_max_len',true))) {
			return false;
		} else {
			return true;
		}
	}
    
}