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

if(!defined('access') or !access or access != 'install') die();
?>
<h3>Add Cron Jobs</h3>
<br />
<?php
try {
	if(check_value($_POST['install_step_4_submit'])) {
		if(!check_value($_POST['install_step_4_error'])) {
			# move to next step
			$_SESSION['install_cstep']++;
			header('Location: install.php');
			die();
		} else {
			echo '<div class="alert alert-danger" role="alert">One of more errors have been logged, cannot continue.</div>';
		}
	}
	
	if(check_value($_SESSION['install_sql_db2'])) {
		$mudb = new dB($_SESSION['install_sql_host'], $_SESSION['install_sql_port'], $_SESSION['install_sql_db2'], $_SESSION['install_sql_user'], $_SESSION['install_sql_pass'], $_SESSION['install_sql_dsn']);
	} else {
		$mudb = new dB($_SESSION['install_sql_host'], $_SESSION['install_sql_port'], $_SESSION['install_sql_db1'], $_SESSION['install_sql_user'], $_SESSION['install_sql_pass'], $_SESSION['install_sql_dsn']);
	}
	
	if($mudb->dead) {
		throw new Exception("Could not connect to database");
	}
	
	# check cron job files
	foreach($install['cron_jobs'] as $key => $cron) {
		$cronPath = __PATH_CRON__ . $cron[2];
		if(!file_exists($cronPath)) throw new Exception('One of more cron job files are missing from your webengine cron folder.');
		array_push($install['cron_jobs'][$key], md5_file($cronPath));
	}
	
	$error = false;
	
	# add crons
	echo '<div class="list-group">';
	foreach($install['cron_jobs'] as $cron) {
		$cronExists = $mudb->query_fetch_single("SELECT * FROM ".WEBENGINE_CRON." WHERE cron_file_run = ?", array($cron[2]));
		if(!$cronExists) {
			$addCron = $mudb->query("INSERT INTO ".WEBENGINE_CRON." (cron_name,cron_description,cron_file_run,cron_run_time,cron_status,cron_protected,cron_file_md5) VALUES (?, ?, ?, ?, ?, ?, ?)", $cron);
			if($addCron) {
				echo '<div class="list-group-item">'.$cron[0].' ('.$cron[2].')<span class="label label-success pull-right">Added</span></div>';
			} else {
				echo '<div class="list-group-item">'.$cron[0].' ('.$cron[2].')<span class="label label-danger pull-right">Error</span></div>';
				$error = true;
			}
		} else {
			echo '<div class="list-group-item">'.$cron[0].' ('.$cron[2].')<span class="label label-default pull-right">Already Exists</span></div>';
		}
	}
	echo '</div>';
	
	echo '<form method="post">';
		if($error) echo '<input type="hidden" name="install_step_4_error" value="1"/>';
		echo '<a href="'.__INSTALL_URL__.'install.php" class="btn btn-default">Re-Check</a> ';
		echo '<button type="submit" name="install_step_4_submit" value="continue" class="btn btn-success">Continue</button>';
	echo '</form>';
	
} catch (Exception $ex) {
	echo '<div class="alert alert-danger" role="alert">'.$ex->getMessage().'</div>';
	echo '<a href="'.__INSTALL_URL__.'install.php" class="btn btn-default">Re-Check</a>';
}