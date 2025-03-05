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

if(!isLoggedIn()) redirect(1,'login');

echo '<div class="page-title"><span>'.lang('module_titles_txt_5',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	if(isset($_POST['webengineEmail_submit'])) {
		try {
			$Account = new Account();
			$Account->changeEmailAddress($_SESSION['userid'], $_POST['webengineEmail_newemail'], $_SERVER['REMOTE_ADDR']);
			if(mconfig('require_verification')) {
				message('success', lang('success_19',true));
			} else {
				message('success', lang('success_20',true));
			}
		} catch (Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<div class="col-12" style="margin-top:30px;">';
		echo '<form action="" method="post">';
			echo '<div class="row mb-3 align-items-center">';
				echo '<label for="webengineEmail" class="col-sm-3 col-form-label">'.lang('changemail_txt_1',true).'</label>';
				echo '<div class="col-sm-9">';
					echo '<input type="text" class="form-control" id="webengineEmail" name="webengineEmail_newemail">';
				echo '</div>';
			echo '</div>';
			echo '<div class="row mb-3 align-items-center">';
				echo '<div class="offset-sm-3 col-sm-9">';
					echo '<button type="submit" name="webengineEmail_submit" value="submit" class="btn btn-primary">'.lang('changemail_txt_1',true).'</button>';
				echo '</div>';
			echo '</div>';
		echo '</form>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}