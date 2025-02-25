<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.6
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2025 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

if(!defined('access') or !access) die();
include('inc/template.functions.php');

$serverInfoCache = LoadCacheData('server_info.cache');
if(is_array($serverInfoCache)) {
	$srvInfo = explode("|", $serverInfoCache[1][0]);
}

$maxOnline = config('maximum_online', true);
$onlinePlayers = isset($srvInfo[3]) ? $srvInfo[3] : 0;
$onlinePlayersPercent = check_value($maxOnline) ? $onlinePlayers*100/$maxOnline : 0;

if(!isset($_REQUEST['page'])) {
	$_REQUEST['page'] = '';
}

if(!isset($_REQUEST['subpage'])) {
	$_REQUEST['subpage'] = '';
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title><?php $handler->websiteTitle(); ?></title>
		<meta name="generator" content="WebEngine <?php echo __WEBENGINE_VERSION__; ?>"/>
		<meta name="author" content="Lautaro Angelico"/>
		<meta name="description" content="<?php config('website_meta_description'); ?>"/>
		<meta name="keywords" content="<?php config('website_meta_keywords'); ?>"/>
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php $handler->websiteTitle(); ?>" />
		<meta property="og:description" content="<?php config('website_meta_description'); ?>" />
		<meta property="og:image" content="<?php echo __PATH_IMG__; ?>webengine.jpg" />
		<meta property="og:url" content="<?php echo __BASE_URL__; ?>" />
		<meta property="og:site_name" content="<?php $handler->websiteTitle(); ?>" />
		<link rel="shortcut icon" href="<?php echo __PATH_TEMPLATE__; ?>favicon.ico"/>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Cinzel" rel="stylesheet">
		<link href="<?php echo __PATH_TEMPLATE_CSS__; ?>style.css" rel="stylesheet" media="screen">
		<link href="<?php echo __PATH_TEMPLATE_CSS__; ?>profiles.css" rel="stylesheet" media="screen">
		<link href="<?php echo __PATH_TEMPLATE_CSS__; ?>castle-siege.css" rel="stylesheet" media="screen">
		<link href="<?php echo __PATH_TEMPLATE_CSS__; ?>override.css" rel="stylesheet" media="screen">
		<script>
			var baseUrl = '<?php echo __BASE_URL__; ?>';
		</script>
	</head>
	<body>
		<div class="global-top-bar">
			<div class="global-top-bar-content">
				<div class="row">
					<div class="col-xs-6 text-left global-top-bar-nopadding">
					<?php if(config('language_switch_active',true)) templateLanguageSelector(); ?>
					</div>
					<div class="col-xs-6 text-right global-top-bar-nopadding">
					<?php if(isLoggedIn()) { ?>
						<a href="<?php echo __BASE_URL__; ?>usercp/"><?php echo lang('module_titles_txt_3'); ?></a>
						<span class="global-top-bar-separator">|</span>
						<a href="<?php echo __BASE_URL__; ?>logout/" class="logout"><?php echo lang('menu_txt_6'); ?></a>
					<?php } else { ?>
						<a href="<?php echo __BASE_URL__; ?>register/"><?php echo lang('menu_txt_3'); ?></a>
						<span class="global-top-bar-separator">|</span>
						<a href="<?php echo __BASE_URL__; ?>login/"><?php echo lang('menu_txt_4'); ?></a>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div id="navbar">
			<?php templateBuildNavbar(); ?>
		</div>
		<div id="header">
			<a href="<?php echo __BASE_URL__; ?>">
				<img class="webengine-mu-logo" src="<?php echo __PATH_TEMPLATE_IMG__; ?>logo.png" title="<?php config('server_name'); ?>"/>
			</a>
		</div>
		<div class="header-info-container">
		<div class="header-info">
			<div class="row">
				<div class="col-xs-12">
					<div class="col-xs-12 header-info-block">
						<?php if(check_value(config('maximum_online', true))) { ?>
						<div class="row">
							<div class="col-xs-6 text-left">
								<?php echo lang('sidebar_srvinfo_txt_5'); ?>:
							</div>
							<div class="col-xs-6 text-right online-count">
								<?php echo number_format($onlinePlayers); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<div class="webengine-online-bar">
									<div class="webengine-online-bar-progress" style="width:<?php echo $onlinePlayersPercent; ?>%;"></div>
								</div>
							</div>
						</div>
						<?php } ?>
						<div class="row">
							<div class="col-xs-6 text-left">
								<?php echo lang('server_time'); ?>:
							</div>
							<div class="col-xs-6 text-right">
								<time id="tServerTime">&nbsp;</time> <span id="tServerDate">&nbsp;</span>
							</div>
							
							<div class="col-xs-6 text-left">
								<?php echo lang('user_time'); ?>:
							</div>
							<div class="col-xs-6 text-right">
								<time id="tLocalTime">&nbsp;</time> <span id="tLocalDate">&nbsp;</span>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		</div>
		<div id="container">
			<div id="content">
				<?php if($_REQUEST['page'] == 'usercp' && $_REQUEST['subpage'] != '') { ?>
				<div class="col-xs-8">
					<?php $handler->loadModule($_REQUEST['page'],$_REQUEST['subpage']); ?>
				</div>
				<div class="col-xs-4">
					<?php include(__PATH_TEMPLATE_ROOT__ . 'inc/modules/sidebar.php'); ?>
				</div>
				<?php } else { ?>
				<div class="col-xs-12">
					<?php $handler->loadModule($_REQUEST['page'],$_REQUEST['subpage']); ?>
				</div>
				<?php } ?>
			</div>
		</div>
		<footer class="footer">
			<?php include(__PATH_TEMPLATE_ROOT__ . 'inc/modules/footer.php'); ?>
		</footer>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script src="<?php echo __PATH_TEMPLATE_JS__; ?>main.js"></script>
		<script src="<?php echo __PATH_TEMPLATE_JS__; ?>events.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
	</body>
</html>