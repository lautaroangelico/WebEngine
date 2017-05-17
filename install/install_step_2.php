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
<h3>Database Connection</h3>
<br />
<?php
if(check_value($_POST['install_step_2_submit'])) {
	try {

		$_SESSION['install_sql_host'] = $_POST['install_step_2_1'];
		if(!check_value($_POST['install_step_2_1'])) throw new Exception('You must complete all required fields.');
		
		$_SESSION['install_sql_port'] = $_POST['install_step_2_7'];
		if(!check_value($_POST['install_step_2_7'])) throw new Exception('You must complete all required fields.');
		
		$_SESSION['install_sql_user'] = $_POST['install_step_2_2'];
		if(!check_value($_POST['install_step_2_2'])) throw new Exception('You must complete all required fields.');
		
		$_SESSION['install_sql_pass'] = $_POST['install_step_2_3'];
		if(!check_value($_POST['install_step_2_3'])) throw new Exception('You must complete all required fields.');
		
		$_SESSION['install_sql_db1'] = $_POST['install_step_2_4'];
		if(!check_value($_POST['install_step_2_4'])) throw new Exception('You must complete all required fields.');
		
		$_SESSION['install_sql_dsn'] = $_POST['install_step_2_8'];
		if(!check_value($_POST['install_step_2_4'])) throw new Exception('You must complete all required fields.');
		if(!array_key_exists($_POST['install_step_2_8'], $install['PDO_DSN'])) throw new Exception('You must complete all required fields.');
		
		$_SESSION['install_sql_db2'] = (check_value($_POST['install_step_2_5']) ? $_POST['install_step_2_5'] : null);
		$_SESSION['install_sql_md5'] = (check_value($_POST['install_step_2_6']) ? true : false);
		
		# test connection (db1)
		$db1 = new dB($_SESSION['install_sql_host'], $_SESSION['install_sql_port'], $_SESSION['install_sql_db1'], $_SESSION['install_sql_user'], $_SESSION['install_sql_pass'], $_SESSION['install_sql_dsn']);
		if($db1->dead) {
			throw new Exception("Could not connect to database (1)");
		}
		
		# test connection (db2)
		if(check_value($_SESSION['install_sql_db2'])) {
			$db2 = new dB($_SESSION['install_sql_host'], $_SESSION['install_sql_port'], $_SESSION['install_sql_db2'], $_SESSION['install_sql_user'], $_SESSION['install_sql_pass'], $_SESSION['install_sql_dsn']);
			if($db2->dead) {
				throw new Exception("Could not connect to database (2)");
			}
		}
		
		# move to next step
		$_SESSION['install_cstep']++;
		header('Location: install.php');
		die();
	} catch (Exception $ex) {
		echo '<div class="alert alert-danger" role="alert">'.$ex->getMessage().'</div>';
	}
}
?>
<form class="form-horizontal" method="post">
	<div class="form-group">
		<label for="input_1" class="col-sm-2 control-label">Host</label>
		<div class="col-sm-10">
			<input type="text" name="install_step_2_1" class="form-control" id="input_1" value="<?php echo (check_value($_SESSION['install_sql_host']) ? $_SESSION['install_sql_host'] : null); ?>">
			<p class="help-block">Set the IP address of your MSSQL server.</p>
		</div>
	</div>
	<div class="form-group">
		<label for="input_7" class="col-sm-2 control-label">Port</label>
		<div class="col-sm-10">
			<input type="text" name="install_step_2_7" class="form-control" id="input_7" value="<?php echo (check_value($_SESSION['install_sql_port']) ? $_SESSION['install_sql_port'] : '1433'); ?>">
			<p class="help-block">Default: 1433.</p>
		</div>
	</div>
	<div class="form-group">
		<label for="input_2" class="col-sm-2 control-label">Username</label>
		<div class="col-sm-10">
			<input type="text" name="install_step_2_2" class="form-control" id="input_2" value="<?php echo (check_value($_SESSION['install_sql_user']) ? $_SESSION['install_sql_user'] : 'sa'); ?>">
			<p class="help-block">It is recommended that you create a new MSSQL user just for the web connection (better security).</p>
		</div>
	</div>
	<div class="form-group">
		<label for="input_3" class="col-sm-2 control-label">Password</label>
		<div class="col-sm-10">
			<input type="text" name="install_step_2_3" class="form-control" id="input_3" value="<?php echo (check_value($_SESSION['install_sql_pass']) ? $_SESSION['install_sql_pass'] : null); ?>">
			<p class="help-block">It is recommended that you use a strong password to ensure maximum security.</p>
		</div>
	</div>
	<div class="form-group">
		<label for="input_4" class="col-sm-2 control-label">Database (1)</label>
		<div class="col-sm-10">
			<input type="text" name="install_step_2_4" class="form-control" id="input_4" value="<?php echo (check_value($_SESSION['install_sql_db1']) ? $_SESSION['install_sql_db1'] : 'MuOnline'); ?>">
			<p class="help-block">Usually <strong>MuOnline</strong>. WebEngine tables will be created in this database.</p>
		</div>
	</div>
	<div class="form-group">
		<label for="input_5" class="col-sm-2 control-label">Database (2)</label>
		<div class="col-sm-10">
			<input type="text" name="install_step_2_5" class="form-control" id="input_5" value="<?php echo (check_value($_SESSION['install_sql_db2']) ? $_SESSION['install_sql_db2'] : null); ?>">
			<p class="help-block">Usually <strong>Me_MuOnline</strong>. Leave empty if you only use one database.</p>
		</div>
	</div>
	<div class="form-group">
		<label for="input_8" class="col-sm-2 control-label">PDO Driver</label>
		<div class="col-sm-10">
			<div class="radio">
				<label>
					<input type="radio" name="install_step_2_8" name="optionsRadios" id="input_81" value="1" checked="checked">
					Dblib (Linux)
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="install_step_2_8" name="optionsRadios" id="input_82" value="2">
					SqlSrv (Windows)
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="install_step_2_8" name="optionsRadios" id="input_83" value="3">
					ODBC (Windows)
				</label>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<div class="checkbox">
				<label>
					<input type="checkbox" value="1" name="install_step_2_6" <?php if($_SESSION['install_sql_md5'] == 1) echo 'checked'; ?>> Enable MD5
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" name="install_step_2_submit" value="continue" class="btn btn-success">Continue</button>
		</div>
	</div>
</form>