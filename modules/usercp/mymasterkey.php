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

if(!isLoggedIn()) redirect(1,'login');
if(config('server_files', true) != 'MUE') redirect();

echo '<div class="page-title"><span>'.lang('module_titles_txt_24',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	if(check_value($_POST['submit'])) {
		try {
			$Account = new Account();
			$Account->masterKeyRecoveryProcess($_SESSION['userid']);
		} catch (Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<div class="col-xs-8 col-xs-offset-2 text-center" style="margin-top:30px;">';
		echo '<form action="" method="post">';
			echo '<button name="submit" value="submit" class="btn btn-primary" >'.lang('masterkey_txt_1',true).'</button>';
		echo '</form>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}