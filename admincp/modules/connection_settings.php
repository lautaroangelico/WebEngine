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

echo '<h1 class="page-header">Connection Settings</h1>';

$allowedSettings = array(
	'settings_submit', # the submit button
	'SQL_DB_HOST',
	'SQL_DB_NAME',
	'SQL_DB_2_NAME',
	'SQL_DB_USER',
	'SQL_DB_PASS',
	'SQL_DB_PORT',
	'SQL_USE_2_DB',
	'SQL_PDO_DRIVER',
	'SQL_ENABLE_MD5',
);

if(check_value($_POST['settings_submit'])) {
	try {
		
		# host
		if(!check_value($_POST['SQL_DB_HOST'])) throw new Exception('Invalid Host setting.');
		$setting['SQL_DB_HOST'] = $_POST['SQL_DB_HOST'];
		
		# database 1
		if(!check_value($_POST['SQL_DB_NAME'])) throw new Exception('Invalid Database (1) setting.');
		$setting['SQL_DB_NAME'] = $_POST['SQL_DB_NAME'];
		
		# database 2
		if(!check_value($_POST['SQL_DB_2_NAME'])) throw new Exception('Invalid Database (2) setting.');
		$setting['SQL_DB_2_NAME'] = $_POST['SQL_DB_2_NAME'];
		
		# user
		if(!check_value($_POST['SQL_DB_USER'])) throw new Exception('Invalid User setting.');
		$setting['SQL_DB_USER'] = $_POST['SQL_DB_USER'];
		
		# password
		if(!check_value($_POST['SQL_DB_PASS'])) throw new Exception('Invalid Password setting.');
		$setting['SQL_DB_PASS'] = $_POST['SQL_DB_PASS'];
		
		# port
		if(!check_value($_POST['SQL_DB_PORT'])) throw new Exception('Invalid Port setting.');
		if(!Validator::UnsignedNumber($_POST['SQL_DB_PORT'])) throw new Exception('Invalid Port setting.');
		$setting['SQL_DB_PORT'] = $_POST['SQL_DB_PORT'];
		
		# use me_muonline
		if(!check_value($_POST['SQL_USE_2_DB'])) throw new Exception('Invalid Use Two Database Structure setting.');
		if(!in_array($_POST['SQL_USE_2_DB'], array(0, 1))) throw new Exception('Invalid Use Two Database Structure setting.');
		$setting['SQL_USE_2_DB'] = ($_POST['SQL_USE_2_DB'] == 1 ? true : false);
		
		# pdo dsn
		if(!check_value($_POST['SQL_PDO_DRIVER'])) throw new Exception('Invalid PDO Driver setting.');
		if(!Validator::UnsignedNumber($_POST['SQL_PDO_DRIVER'])) throw new Exception('Invalid PDO Driver setting.');
		if(!in_array($_POST['SQL_PDO_DRIVER'], array(1, 2, 3))) throw new Exception('Invalid PDO Driver setting.');
		$setting['SQL_PDO_DRIVER'] = $_POST['SQL_PDO_DRIVER'];
		
		# md5
		if(!check_value($_POST['SQL_ENABLE_MD5'])) throw new Exception('Invalid MD5 setting.');
		if(!in_array($_POST['SQL_ENABLE_MD5'], array(0, 1))) throw new Exception('Invalid MD5 setting.');
		$setting['SQL_ENABLE_MD5'] = ($_POST['SQL_ENABLE_MD5'] == 1 ? true : false);
		
		# test connection (1)
		$testdB = new dB($setting['SQL_DB_HOST'], $setting['SQL_DB_PORT'], $setting['SQL_DB_NAME'], $setting['SQL_DB_USER'], $setting['SQL_DB_PASS'], $setting['SQL_PDO_DRIVER']);
		if($testdB->dead) {
			throw new Exception('The connection to database (1) was unsuccessful, settings not saved.');
		}
		
		# test connection (2)
		if($setting['SQL_USE_2_DB']) {
			$testdB2 = new dB($setting['SQL_DB_HOST'], $setting['SQL_DB_PORT'], $setting['SQL_DB_2_NAME'], $setting['SQL_DB_USER'], $setting['SQL_DB_PASS'], $setting['SQL_PDO_DRIVER']);
			if($testdB2->dead) {
				throw new Exception('The connection to database (2) was unsuccessful, settings not saved.');
			}
		}
		
		# webengine configs
		$webengineConfigurations = webengineConfigs();
		
		# make sure the settings are in the allow list
		foreach(array_keys($setting) as $settingName) {
			if(!in_array($settingName, $allowedSettings)) throw new Exception('One or more submitted setting is not editable.');
			
			$webengineConfigurations[$settingName] = $setting[$settingName];
		}
		
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

echo '<div class="col-md-12">';
	echo '<form action="" method="post">';
		echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
			
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Host</strong>';
					echo '<p class="setting-description">Hostname/IP address of your MSSQL server.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="SQL_DB_HOST" value="'.config('SQL_DB_HOST',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Database (1)</strong>';
					echo '<p class="setting-description">Usually "MuOnline".</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="SQL_DB_NAME" value="'.config('SQL_DB_NAME',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Database (2)</strong>';
					echo '<p class="setting-description">Usually "Me_MuOnline".</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="SQL_DB_2_NAME" value="'.config('SQL_DB_2_NAME',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>User</strong>';
					echo '<p class="setting-description">Usually "sa".</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="SQL_DB_USER" value="'.config('SQL_DB_USER',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Password</strong>';
					echo '<p class="setting-description">User\'s password.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="SQL_DB_PASS" value="'.config('SQL_DB_PASS',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Port</strong>';
					echo '<p class="setting-description">Port number to remotely connect to your MSSQL server. Usually 1433.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="number" class="form-control" name="SQL_DB_PORT" value="'.config('SQL_DB_PORT',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Use 2 Database Structure</strong>';
					echo '<p class="setting-description">Enables/disables the use of Me_MuOnline database (2 database structure).</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="SQL_USE_2_DB" value="1" '.(config('SQL_USE_2_DB',true) ? 'checked' : null).'>';
							echo 'Enabled';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="SQL_USE_2_DB" value="0" '.(!config('SQL_USE_2_DB',true) ? 'checked' : null).'>';
							echo 'Disabled';
						echo '</label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>PDO Driver</strong>';
					echo '<p class="setting-description">Choose which driver WebEngine should use to remotely connect to your MSSQL server.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="SQL_PDO_DRIVER" value="1" '.(config('SQL_PDO_DRIVER',true) == 1 ? 'checked' : null).'>';
							echo 'dblib (linux)';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="SQL_PDO_DRIVER" value="2" '.(config('SQL_PDO_DRIVER',true) == 2 ? 'checked' : null).'>';
							echo 'sqlsrv';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="SQL_PDO_DRIVER" value="3" '.(config('SQL_PDO_DRIVER',true) == 3 ? 'checked' : null).'>';
							echo 'odbc';
						echo '</label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>MD5 Status</strong>';
					echo '<p class="setting-description">Enables/disables the use of MD5.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="SQL_ENABLE_MD5" value="1" '.(config('SQL_ENABLE_MD5',true) ? 'checked' : null).'>';
							echo 'Enabled';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="SQL_ENABLE_MD5" value="0" '.(!config('SQL_ENABLE_MD5',true) ? 'checked' : null).'>';
							echo 'Disabled';
						echo '</label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			
		echo '</table>';
		
		echo '<button type="submit" name="settings_submit" value="ok" class="btn btn-success">Save Settings</button>';
	echo '</form>';
echo '</div>';