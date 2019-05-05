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

try {
	if(!config('language_switch_active',true)) throw new Exception(lang('error_62'));
	if(!check_value($_GET['to'])) throw new Exception(lang('error_63'));
	if(strlen($_GET['to']) != 2) throw new Exception(lang('error_63'));
	if(!Validator::Alpha($_GET['to'])) throw new Exception(lang('error_63'));
	if(!$handler->switchLanguage($_GET['to'])) throw new Exception(lang('error_65'));
	redirect();
} catch (Exception $ex) {
	if(!config('error_reporting',true)) redirect();
	message('error', $ex->getMessage());
}