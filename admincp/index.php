<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.1
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

// access
define('access', 'admincp');

try {
	
	// Load WebENGINE
	if(!@include_once('../includes/webengine.php')) throw new Exception('Could not load WebEngine.');

	// Check if user is logged in
	if(!isLoggedIn()) { redirect(); }

	// Check if user has access
	if(!canAccessAdminCP($_SESSION['username'])) { redirect(); }

	// Load AdminCP Tools
	if(!@include_once(__PATH_ADMINCP_INC__ . 'functions.php')) throw new Exception('Could not load AdminCP functions.');
	
	// Check Configurations
	if(!@include_once(__PATH_ADMINCP_INC__ . 'check.php')) throw new Exception('Could not load AdminCP configuration check.');
	
} catch (Exception $ex) {
	$errorPage = file_get_contents('../includes/error.html');
	echo str_replace("{ERROR_MESSAGE}", $ex->getMessage(), $errorPage);
	die();
}

$admincpSidebar = array(
	array("News Management", array(
		"addnews" => "Publish",
		"managenews" => "Edit / Delete",
	), "fa-newspaper-o"),
	array("Account", array(
		"searchaccount" => "Search",
		"accountsfromip" => "Find Accounts from IP",
		"onlineaccounts" => "Online Accounts",
		"newregistrations" => "New Registrations",
		"accountinfo" => "", // HIDDEN
	), "fa-users"),
	array("Character", array(
		"searchcharacter" => "Search",
		"editcharacter" => "", // HIDDEN
	), "fa-user"),
	array("Bans", array(
		"searchban" => "Search",
		"banaccount" => "Ban Account",
		"latestbans" => "Latest Bans",
		"blockedips" => "Block IP (web)",
	), "fa-exclamation-circle"),
	array("Credits", array(
		"creditsconfigs" => "Credit Configurations",
		"creditsmanager" => "Credit Manager",
		"latestpaypal" => "PayPal Donations",
		"topvotes" => "Top Voters",
	), "fa-money"),
	array("Website Configuration", array(
		"admincp_access" => "AdminCP Access",
		"connection_settings" => "Connection Settings",
		"website_settings" => "Website Settings",
		"modules_manager" => "Modules Manager",
		"navbar" => "Navigation Menu",
		"usercp" => "UserCP Menu",
	), "fa-toggle-on"),
	array("Tools", array(
		"cachemanager" => "Cache Manager",
		"cronmanager" => "Cron Job Manager",
	), "fa-wrench"),
	array("Languages", array(
		"phrases" => "Phrase List",
	), "fa-language"),
	array("Plugins", array(
		"plugins" => "Plugins Manager",
		"plugin_install" => "Import Plugin",
	), "fa-plug"),
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="WebEngine AdminCP 2.0">
    <meta name="author" content="Lautaro Angelico">

    <title>WebEngine AdminCP</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<script src="css/plugins/dataTables.bootstrap.css"></script>
    <link href="css/webengine.css" rel="stylesheet">
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
	<div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo admincp_base(); ?>"><img src="img/logo.jpg" /></a>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <li><a href="<?php echo __BASE_URL__; ?>" target="_blank"><i class="fa fa-fw fa-home"></i> Website Home</a></li>
                <li><a href="<?php echo __BASE_URL__; ?>logout/"><i class="fa fa-fw fa-power-off"></i> Log Out</a></li>
            </ul>

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
						<?php
							foreach($admincpSidebar as $sidebarItem) {
								$active = '';
								if(isset($_GET['module'])) {
									if(array_key_exists($_GET['module'], $sidebarItem[1])) {
										$active = ' class="active"';
									}
								}
								
								echo '<li'.$active.'>';
									$itemIcon = (check_value($sidebarItem[2]) ? '<i class="fa '.$sidebarItem[2].' fa-fw"></i>&nbsp;' : '');
									if(is_array($sidebarItem[1])) {
										echo '<a href="#">'.$itemIcon.$sidebarItem[0].'<span class="fa arrow"></span></a>';
										echo '<ul class="nav nav-second-level">';
											foreach($sidebarItem[1] as $sidebarSubItemModule => $sidebarSubItemTitle) {
												if(check_value($sidebarSubItemTitle)) echo '<li><a href="'.admincp_base($sidebarSubItemModule).'">'.$sidebarSubItemTitle.'</a></li>';
											}
										echo '</ul>';
									} else {
										echo '<a href="'.admincp_base($sidebarItem[1]).'">'.$itemIcon.$sidebarItem[0].'</a>';
									}
								echo '</li>';
							}
							
							if(isset($extra_admincp_sidebar)) {
								if(is_array($extra_admincp_sidebar)) {
									echo '<li>';
										echo '<a href="#"><i class="fa fa-square fa-fw"></i>Active Plugins<span class="fa arrow"></span></a>';
										echo '<ul class="nav nav-second-level">';
											foreach($extra_admincp_sidebar as $pluginSidebarItem) {
												if(is_array($pluginSidebarItem) && is_array($pluginSidebarItem[1])) {
													echo '<li>';
														echo '<a href="#">'.$pluginSidebarItem[0].' <span class="fa arrow"></span></a>';
														echo '<ul class="nav nav-third-level collapse" aria-expanded="false" style="height: 0px;">';
															foreach($pluginSidebarItem[1] as $pluginSidebarSubItem) {
																echo '<li><a href="'.admincp_base($pluginSidebarSubItem[1]).'">'.$pluginSidebarSubItem[0].'</a></li>';
															}
														echo '</ul>';
													echo '</li>';
												}
											}
										echo '</ul>';
									echo '</li>';
								}
							}
						?>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row contentpadding">
                <div class="col-lg-12">
					<?php
						$req = isset($_REQUEST['module']) ? $_REQUEST['module'] : '';
						$handler->loadAdminCPModule($req);
					?>
                </div>
            </div>
        </div>

    </div>
    <script src="js/jquery-1.11.0.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>
    <script src="js/sb-admin-2.js"></script>
	<script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
	<script type="text/javascript" language="javascript" class="init">
		$(document).ready(function() {
			$('#new_registrations').DataTable({
				"searching":		false,
				"ordering":			false,
				"lengthChange":		false,
				"pageLength":		10,
				"info":				false
			});
			$('#blocked_ips').DataTable({
				"searching":		false,
				"ordering":			false,
				"lengthChange":		false,
				"pageLength":		10,
				"info":				false
			});
			$('#paypal_donations').DataTable({
				"searching":		true,
				"ordering":			false,
				"lengthChange":		false,
				"pageLength":		10,
				"info":				true
			});
			$('#superrewards_donations').DataTable({
				"searching":		true,
				"ordering":			false,
				"lengthChange":		false,
				"pageLength":		10,
				"info":				true
			});
			$('#credits_logs').DataTable({
				"searching":		true,
				"ordering":			false,
				"lengthChange":		false,
				"pageLength":		10,
				"info":				true
			});
		} );
	</script>
</body>
</html>

