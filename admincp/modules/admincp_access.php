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

echo '<h1 class="page-header">AdminCP Access</h1>';
echo '<p>To remove an admin set their access level to 0.</p>';

if(check_value($_POST['settings_submit'])) {
	try {
		# webengine configs
		$webengineConfigurations = webengineConfigs();
		
		$newAdminUser = $_POST['new_admin'];
		$newAdminLevel = $_POST['new_access'];
		
		# remove elements
		unset($_POST['settings_submit']);
		unset($_POST['new_admin']);
		unset($_POST['new_access']);
		
		# check configs
		foreach($_POST as $adminUsername => $accessLevel) {
			if(!Validator::AlphaNumeric($adminUsername)) throw new Exception('The entered username is not valid.');
			if(!Validator::UsernameLength($adminUsername)) throw new Exception('The entered username is not valid.');
			if(!array_key_exists($adminUsername, config('admins',true))) continue;
			if(!Validator::UnsignedNumber($accessLevel)) throw new Exception('Access level must be a number between 0 and 100');
			if(!Validator::Number($accessLevel, 100, 0)) throw new Exception('Access level must be a number between 0 and 100');
			if($accessLevel == 0) {
				if($adminUsername == $_SESSION['username']) throw new Exception('You cannot remove yourself.');
				continue; # admin removal
			}
			
			$adminAccounts[$adminUsername] = (int) $accessLevel;
		}
		
		if(check_value($newAdminUser)) {
			if(array_key_exists($newAdminUser, config('admins',true))) throw new Exception('An administrator with the same username is already in the list.');
			if(!Validator::UnsignedNumber($newAdminLevel)) throw new Exception('Access level must be a number between 1 and 100');
			if(!Validator::Number($newAdminLevel, 100, 0)) throw new Exception('Access level must be a number between 1 and 100');
			
			$adminAccounts[$newAdminUser] = (int) $newAdminLevel;
		}
		
		$webengineConfigurations['admins'] = $adminAccounts;
		
		$newWebEngineConfig = json_encode($webengineConfigurations, JSON_PRETTY_PRINT);
		$cfgFile = fopen(__PATH_CONFIGS__.'webengine.json', 'w');
		if(!$cfgFile) throw new Exception('There was a problem opening the configuration file.');
		
		fwrite($cfgFile, $newWebEngineConfig);
		fclose($cfgFile);
		
		message('success', 'Settings successfully saved!');
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$admins = config('admins',true);

if(is_array($admins)) {
	echo '<div class="col-sm-12 col-md-6 col-lg-6">';
		echo '<form action="" method="post">';
			echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
				echo '<thead>';
					echo '<tr>';
						echo '<th>Admin Account</th>';
						echo '<th>Access Level</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
					foreach($admins as $admin_account => $access_level) {
						echo '<tr>';
							echo '<td>';
								echo '<strong>'.$admin_account.'</strong>';
							echo '</td>';
							echo '<td>';
								echo '<input type="number" class="form-control" min="0" max="100" name="'.$admin_account.'" value="'.$access_level.'" required>';
							echo '</td>';
						echo '</tr>';
					}
					echo '<tr>';
						echo '<td>';
							echo '<input type="text" class="form-control" min="0" max="100" name="new_admin" placeholder="username">';
						echo '</td>';
						echo '<td>';
							echo '<input type="number" class="form-control" min="0" max="100" name="new_access" placeholder="0">';
						echo '</td>';
					echo '</tr>';
				echo '</tbody>';
			echo '</table>';
			
			echo '<button type="submit" name="settings_submit" value="ok" class="btn btn-success">Save Settings</button>';
		echo '</form>';
	echo '</div>';
} else {
	message('error', 'Admins list is empty.');
}