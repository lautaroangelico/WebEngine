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

echo '<div class="page-title"><span>'.lang('module_titles_txt_6',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	// common class
	$common = new common();
	
	if(mconfig('change_password_email_verification') && $common->hasActivePasswordChangeRequest($_SESSION['userid'])) {
		throw new Exception(lang('error_19',true));
	}
	
	if(isset($_POST['webenginePassword_submit'])) {
		try {
			$Account = new Account();
			
			if(mconfig('change_password_email_verification')) {
				# verification required
				$Account->changePasswordProcess_verifyEmail($_SESSION['userid'], $_SESSION['username'], $_POST['webenginePassword_current'], $_POST['webenginePassword_new'], $_POST['webenginePassword_newconfirm'], $_SERVER['REMOTE_ADDR']);
			} else {
				# no verification
				$Account->changePasswordProcess($_SESSION['userid'], $_SESSION['username'], $_POST['webenginePassword_current'], $_POST['webenginePassword_new'], $_POST['webenginePassword_newconfirm']);
			}
		} catch (Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<div class="col-xs-8 col-xs-offset-2" style="margin-top:30px;">';
		echo '<form class="form-horizontal" action="" method="post">';
			echo '<div class="form-group">';
				echo '<label for="webenginePassword" class="col-sm-4 control-label">'.lang('changepassword_txt_1',true).'</label>';
				echo '<div class="col-sm-8">';
					echo '<input type="password" class="form-control" id="webenginePassword" name="webenginePassword_current">';
				echo '</div>';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<label for="webenginePassword" class="col-sm-4 control-label">'.lang('changepassword_txt_2',true).'</label>';
				echo '<div class="col-sm-8">';
					echo '<input type="password" class="form-control" id="webenginePassword" name="webenginePassword_new">';
				echo '</div>';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<label for="webenginePassword" class="col-sm-4 control-label">'.lang('changepassword_txt_3',true).'</label>';
				echo '<div class="col-sm-8">';
					echo '<input type="password" class="form-control" id="webenginePassword" name="webenginePassword_newconfirm">';
				echo '</div>';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<div class="col-sm-offset-4 col-sm-8">';
					echo '<button type="submit" name="webenginePassword_submit" value="submit" class="btn btn-primary">'.lang('changepassword_txt_4',true).'</button>';
				echo '</div>';
			echo '</div>';
		echo '</form>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}