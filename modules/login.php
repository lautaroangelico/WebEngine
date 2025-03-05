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

if(isLoggedIn()) redirect();

echo '<div class="page-title"><span>'.lang('module_titles_txt_2',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	// Login Process
	if(isset($_POST['webengineLogin_submit'])) {
		try {
			$userLogin = new login();
			$userLogin->validateLogin($_POST['webengineLogin_user'],$_POST['webengineLogin_pwd']);
		} catch (Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<div class="col-12" style="margin-top:30px;">';
		echo '<form action="" method="post">';
			echo '<div class="row mb-3 align-items-center">';
				echo '<label for="webengineLogin1" class="col-sm-3 col-form-label">'.lang('login_txt_1',true).'</label>';
				echo '<div class="col-sm-9">';
					echo '<input type="text" class="form-control" id="webengineLogin1" name="webengineLogin_user" required>';
				echo '</div>';
			echo '</div>';
			echo '<div class="row mb-3 align-items-center">';
				echo '<label for="webengineLogin2" class="col-sm-3 col-form-label">'.lang('login_txt_2',true).'</label>';
				echo '<div class="col-sm-9">';
					echo '<input type="password" class="form-control" id="webengineLogin2" name="webengineLogin_pwd" required>';
					echo '<span id="helpBlock" class="help-block"><a href="'.__BASE_URL__.'forgotpassword/">'.lang('login_txt_4',true).'</a></span>';
				echo '</div>';
			echo '</div>';
			echo '<div class="row mb-3 align-items-center">';
				echo '<div class="offset-sm-3 col-sm-9">';
					echo '<button type="submit" name="webengineLogin_submit" value="submit" class="btn btn-primary">'.lang('login_txt_3',true).'</button>';
				echo '</div>';
			echo '</div>';
		echo '</form>';
	echo '</div>';

} catch(Exception $ex) {
	message('error', $ex->getMessage());
}