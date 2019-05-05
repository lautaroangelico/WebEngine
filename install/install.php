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

define('access', 'install');
if(!@include_once('loader.php')) die('Could not load WebEngine CMS Installer.');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>WebEngine CMS <?php echo INSTALLER_VERSION; ?> Installer</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<style>
	<!--
	.header {
		border-bottom: 1px solid #ccc;
		margin: 20px 0px;
		padding: 10px 0px;
	}
	h1 {
		color: #4EA7E3;
	}
	h2, h3, h4 {
		padding: 0px;
		margin: 0px;
	}
	.footer {
		border-top: 1px solid #ccc;
		margin: 20px 0px;
		padding: 10px 0px;
	}
	-->
	</style>
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>

	<div class="container">
		
		<div class="row header">
			<h1>WebEngine Installer</h1>
		</div>
		
		<div class="row">
			<div class="col-md-9">
				<?php
				try {
					if(array_key_exists($_SESSION['install_cstep'], $install['step_list'])) {
						$fileName = $install['step_list'][$_SESSION['install_cstep']][0];
						if(file_exists($fileName)) {
							if(!@include_once($fileName)) throw new Exception('Bad step file.');
						}
					}
				} catch (Exception $ex) {
					echo '<div class="alert alert-danger" role="alert">'.$ex->getMessage().'</div>';
				}
				?>
			</div>
			<div class="col-md-3">
				<?php stepListSidebar(); ?>
			</div>
		</div>

		<footer class="footer">
			<a href="https://webenginecms.org/" target="_blank">&copy; WebEngine CMS 2013-<?php echo date("Y"); ?></a>
		</footer>

	</div> <!-- /container -->

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>