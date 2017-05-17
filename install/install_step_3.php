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

if(!defined('access') or !access or access != 'install') die();
?>
<h3>Create Tables</h3>
<br />
<?php
try {
	if(check_value($_POST['install_step_3_submit'])) {
		if(!check_value($_POST['install_step_3_error'])) {
			# move to next step
			$_SESSION['install_cstep']++;
			header('Location: install.php');
			die();
		} else {
			echo '<div class="alert alert-danger" role="alert">One of more errors have been logged, cannot continue.</div>';
		}
	}
	
	# db connection (db1)
	$db1 = new dB($_SESSION['install_sql_host'], $_SESSION['install_sql_port'], $_SESSION['install_sql_db1'], $_SESSION['install_sql_user'], $_SESSION['install_sql_pass'], $_SESSION['install_sql_dsn']);
	if($db1->dead) {
		throw new Exception("Could not connect to database (1)");
	}
	
	foreach($install['sql_list'] as $fileName) {
		if(!file_exists('sql/' . $fileName . '.txt')) {
			throw new Exception('The installation script is missing SQL tables.');
		}
	}
	
	$error = false;
	
	echo '<div class="list-group">';
	foreach($install['sql_list'] as $fileName) {
		$query = file_get_contents('sql/'.$fileName.'.txt');
		
		$tableExists = $db1->query_fetch_single("SELECT * FROM sysobjects WHERE xtype = 'U' AND name = ?", array($fileName));
		
		if(!$tableExists) {
			$create = $db1->query($query);
			if($create) {
				echo '<div class="list-group-item">'.$fileName.'<span class="label label-success pull-right">Created</span></div>';
			} else {
				echo '<div class="list-group-item">'.$fileName.'<span class="label label-danger pull-right">Error</span></div>';
				$error = true;
			}
		} else {
			echo '<div class="list-group-item">'.$fileName.'<span class="label label-default pull-right">Already Exists</span></div>';
		}
	}
	echo '</div>';
	
	echo '<form method="post">';
		if($error) echo '<input type="hidden" name="install_step_3_error" value="1"/>';
		echo '<a href="'.__INSTALL_URL__.'install.php" class="btn btn-default">Re-Check</a> ';
		echo '<button type="submit" name="install_step_3_submit" value="continue" class="btn btn-success">Continue</button>';
	echo '</form>';
	
} catch (Exception $ex) {
	echo '<div class="alert alert-danger" role="alert">'.$ex->getMessage().'</div>';
}