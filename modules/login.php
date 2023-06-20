<?php
/**
 * WebEngine
 * http://muengine.net/
 * 
 * @version 1.0.9
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2017 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

if(isLoggedIn()) redirect();

echo '<div class="page-title"><span>'.lang('module_titles_txt_2',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	// Login Process
	if(check_value($_POST['webengineLogin_submit'])) {
		try {
			$userLogin = new login();
			$userLogin->validateLogin($_POST['webengineLogin_user'],$_POST['webengineLogin_pwd']);
		} catch (Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<div class="col-12 col-sm-6 col-md-8 mx-auto" style="margin-top:30px;">';
		echo '<form class="form-horizontal needs-validation" action="" method="post">';


				echo '<div class="input-group">';
					echo '<span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>';
					echo '<input type="text" class="form-control" id="webengineLogin1" name="webengineLogin_user" required>';
				echo '</div>';
				echo '<small class="text-muted">Ingrese su usuario</small>';

				echo '<div class="input-group mt-2">';
					echo '<span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>';
					echo '<input type="password" class="form-control" id="webengineLogin2" name="webengineLogin_pwd" required>';
				echo '</div>';
				echo '<small class="text-muted">Ingrese su password</small>';
				echo '<span id="helpBlock" class="help-block text-end"><br><a href="'.__BASE_URL__.'forgotpassword/">'.lang('login_txt_4',true).'</a></span>';

				echo '<div class="input-group mt-2">';
					echo '<div class="d-grid gap-2 col-12 mx-auto">';
						echo '<button type="submit" name="webengineLogin_submit" value="submit" class="btn btn-primary">'.lang('login_txt_3',true).'</button>';
					echo '</div>';
				echo '</div>';
	


		echo '</form>';
	echo '</div>';

} catch(Exception $ex) {
	message('error', $ex->getMessage());
}