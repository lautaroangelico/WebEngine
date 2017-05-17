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

echo '<h1 class="page-header">AdminCP Permissions</h1>';

if(check_value($_POST['settings_submit'])) {
	try {
		# webengine configs
		$webengineConfigurations = webengineConfigs();
		
		# remove submit button element
		unset($_POST['settings_submit']);
		
		# check if module is in configs
		foreach($_POST as $moduleFile => $accessLevel) {
			if(!array_key_exists($moduleFile, config('admincp_modules_access',true))) continue;
			if(!Validator::UnsignedNumber($accessLevel)) throw new Exception('Access level must be a number between 0 and 100');
			if(!Validator::Number($accessLevel, 100, 0)) throw new Exception('Access level must be a number between 0 and 100');
			$modulesConfig[$moduleFile] = (int) $accessLevel;
		}
		
		$webengineConfigurations['admincp_modules_access'] = $modulesConfig;
		
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

$admincpModulesAccess = config('admincp_modules_access',true);

if(is_array($admincpModulesAccess)) {
	echo '<div class="col-md-12">';
		echo '<form action="" method="post">';
			echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
				echo '<thead>';
					echo '<tr>';
						echo '<th>Module</th>';
						echo '<th>Access Level</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
					foreach($admincpModulesAccess as $module_file => $moduleAccess) {
						echo '<tr>';
							echo '<td>';
								echo '<strong>'.$module_file.'</strong>';
							echo '</td>';
							echo '<td>';
								echo '<input type="number" class="form-control" min="0" max="100" name="'.$module_file.'" value="'.$moduleAccess.'" required>';
							echo '</td>';
						echo '</tr>';
					}
				echo '</tbody>';
			echo '</table>';
			
			echo '<button type="submit" name="settings_submit" value="ok" class="btn btn-success">Save Settings</button>';
		echo '</form>';
	echo '</div>';
} else {
	message('error', 'Modules list is empty.');
}