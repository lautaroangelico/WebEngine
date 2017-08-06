<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.0.9.7
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2017 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

if(!defined('access') or !access or access != 'install') die();
?>
<h3>Web Server Requirements</h3>
<br />
<?php
if(check_value($_POST['install_step_1_submit'])) {
	try {
		# move to next step
		$_SESSION['install_cstep']++;
		header('Location: install.php');
		die();
	} catch (Exception $ex) {
		echo '<div class="alert alert-danger" role="alert">'.$ex->getMessage().'</div>';
	}
}

$writablePaths = array(
	'cache/',
	'cache/news/',
	'cache/profiles/guilds/',
	'cache/profiles/players/',
	'cache/castle_siege.cache',
	'cache/cron.cache',
	'cache/downloads.cache',
	'cache/news.cache',
	'cache/plugins.cache',
	'cache/rankings_gens.cache',
	'cache/rankings_gr.cache',
	'cache/rankings_guilds.cache',
	'cache/rankings_level.cache',
	'cache/rankings_master.cache',
	'cache/rankings_online.cache',
	'cache/rankings_pk.cache',
	'cache/rankings_pvplaststand.cache',
	'cache/rankings_resets.cache',
	'cache/rankings_votes.cache',
	'cache/server_info.cache',
	'config/email.xml',
	'config/navbar.json',
	'config/usercp.json',
	'config/webengine.json',
	'config/modules/castlesiege.xml',
	'config/modules/contact.xml',
	'config/modules/donation.paymentwall.xml',
	'config/modules/donation.paypal.xml',
	'config/modules/donation.superrewards.xml',
	'config/modules/donation.westernunion.xml',
	'config/modules/donation.xml',
	'config/modules/downloads.xml',
	'config/modules/forgotpassword.xml',
	'config/modules/login.xml',
	'config/modules/news.xml',
	'config/modules/profiles.xml',
	'config/modules/rankings.xml',
	'config/modules/register.xml',
	'config/modules/usercp.addstats.xml',
	'config/modules/usercp.buyzen.xml',
	'config/modules/usercp.clearpk.xml',
	'config/modules/usercp.clearskilltree.xml',
	'config/modules/usercp.myaccount.xml',
	'config/modules/usercp.myemail.xml',
	'config/modules/usercp.mymasterkey.xml',
	'config/modules/usercp.mypassword.xml',
	'config/modules/usercp.reset.xml',
	'config/modules/usercp.resetstats.xml',
	'config/modules/usercp.unstick.xml',
	'config/modules/usercp.vip.xml',
	'config/modules/usercp.vote.xml',
);

echo '<div class="list-group">';

	$chk_1 = version_compare(PHP_VERSION, '5.4', '>=');
	$check_1 = ($chk_1 ? '<span class="label label-success">Ok</span>' : '<span class="label label-danger">Fix</span>');
	echo '<div class="list-group-item">';
		echo 'PHP 5.4 or higher';
		echo '<span class="pull-right">(PHP '.PHP_VERSION.') '.$check_1.'</span>';
	echo '</div>';

	$chk_2 = (ini_get('short_open_tag') == 1 ? true : false);
	$check_2 = ($chk_2 ? '<span class="label label-success">Ok</span>' : '<span class="label label-danger">Fix</span>');
	echo '<div class="list-group-item">';
		echo 'short_open_tag';
		echo '<span class="pull-right">'.$check_2.'</span>';
	echo '</div>';

	$chk_3 = (extension_loaded('openssl') ? true : false);
	$check_3 = ($chk_3 ? '<span class="label label-success">Ok</span>' : '<span class="label label-danger">Fix</span>');
	echo '<div class="list-group-item">';
		echo 'OpenSSL Extension';
		echo '<span class="pull-right">'.$check_3.'</span>';
	echo '</div>';

	$chk_4 = (extension_loaded('bcmath') ? true : false);
	$check_4 = ($chk_4 ? '<span class="label label-success">Ok</span>' : '<span class="label label-danger">Fix</span>');
	echo '<div class="list-group-item">';
		echo 'BC Math Extension';
		echo '<span class="pull-right">'.$check_4.'</span>';
	echo '</div>';

	$chk_5 = (extension_loaded('pdo') ? true : false);
	$check_5 = ($chk_5 ? '<span class="label label-success">Ok</span>' : '<span class="label label-danger">Fix</span>');
	echo '<div class="list-group-item">';
		echo 'PDO';
		echo '<span class="pull-right">'.$check_5.'</span>';
	echo '</div>';
	
	if($chk_5) {
		$chk_6 = (extension_loaded('pdo_dblib') ? true : false);
		$check_6 = ($chk_6 ? '<span class="label label-success">Ok</span>' : '<span class="label label-danger">Fix</span>');
		echo '<div class="list-group-item">';
			echo 'PDO dblib (linux)';
			echo '<span class="pull-right">'.$check_6.'</span>';
		echo '</div>';

		$chk_7 = (extension_loaded('pdo_odbc') ? true : false);
		$check_7 = ($chk_7 ? '<span class="label label-success">Ok</span>' : '<span class="label label-danger">Fix</span>');
		echo '<div class="list-group-item">';
			echo 'PDO odbc (linux/windows)';
			echo '<span class="pull-right">'.$check_7.'</span>';
		echo '</div>';

		$chk_8 = (extension_loaded('pdo_sqlsrv') ? true : false);
		$check_8 = ($chk_8 ? '<span class="label label-success">Ok</span>' : '<span class="label label-danger">Fix</span>');
		echo '<div class="list-group-item">';
			echo 'PDO sqlsrv (windows)';
			echo '<span class="pull-right">'.$check_8.'</span>';
		echo '</div>';
	}
	
	$chk_9 = (extension_loaded('json') ? true : false);
	$check_9 = ($chk_9 ? '<span class="label label-success">Ok</span>' : '<span class="label label-danger">Fix</span>');
	echo '<div class="list-group-item">';
		echo 'JSON';
		echo '<span class="pull-right">'.$check_9.'</span>';
	echo '</div>';
	
echo '</div>';

echo '<h3>Writable Directories and Files (chmod)</h3>';
foreach($writablePaths as $filepath) {
	if(file_exists(__PATH_INCLUDES__ . $filepath)) {
		if(!is_writable(__PATH_INCLUDES__ . $filepath)) {
			echo '<div class="list-group-item">';
				echo $filepath;
				echo '<span class="pull-right"><span class="label label-warning">Not Writable</span></span>';
			echo '</div>';
		} else {
			echo '<div class="list-group-item">';
				echo $filepath;
				echo '<span class="pull-right"><span class="label label-success">Ok</span></span>';
			echo '</div>';
		}
	} else {
		echo '<div class="list-group-item">';
			echo $filepath;
			echo '<span class="pull-right"><span class="label label-danger">File Missing</span></span>';
		echo '</div>';
	}
}

echo '<br /><br />';
echo '<p style="color:red;">We strongly advise that you fix any issues before proceeding.</p>';
echo '<br />';

echo '<form action="" method="post">';
	echo '<a href="'.__INSTALL_URL__.'install.php" class="btn btn-default">Re-Check</a> ';
	echo '<button type="submit" name="install_step_1_submit" value="ok" class="btn btn-success">Continue</button>';
echo '</form>';