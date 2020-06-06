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

if(!defined('access') or !access) die();
include('inc/template.functions.php');

$disabledSidebar = array(
	'rankings',
);
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
		<link rel="shortcut icon" href="<?php echo __PATH_TEMPLATE__; ?>favicon.ico"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
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
					<?php if(config('language_switch_active',true)) { ?>
						<ul class="webengine-language-switcher">
							<li><a href="<?php echo __BASE_URL__ . 'language/switch/to/en'; ?>" title="English"><img src="<?php echo getCountryFlag('US'); ?>" /> EN</a></li>
							<li><a href="<?php echo __BASE_URL__ . 'language/switch/to/es'; ?>" title="Español"><img src="<?php echo getCountryFlag('ES'); ?>" /> ES</a></li>
							<li><a href="<?php echo __BASE_URL__ . 'language/switch/to/ph'; ?>" title="Filipino"><img src="<?php echo getCountryFlag('PH'); ?>" /> PH</a></li>
							<li><a href="<?php echo __BASE_URL__ . 'language/switch/to/pt'; ?>" title="Português"><img src="<?php echo getCountryFlag('BR'); ?>" /> BR</a></li>
							<li><a href="<?php echo __BASE_URL__ . 'language/switch/to/ro'; ?>" title="Romanian"><img src="<?php echo getCountryFlag('RO'); ?>" /> RO</a></li>
							<li><a href="<?php echo __BASE_URL__ . 'language/switch/to/cn'; ?>" title="Simplified Chinese"><img src="<?php echo getCountryFlag('CN'); ?>" /> CN</a></li>
						</ul>
					<?php } ?>
					</div>
					<div class="col-xs-6 text-right global-top-bar-nopadding">
					<?php if(isLoggedIn()) { ?>
						<a href="<?php echo __BASE_URL__; ?>usercp/"><?php echo lang('menu_txt_5'); ?></a>
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
		<?php templateDisplayCSBanner(); ?>
		<div id="container">
			<div id="content">
				<?php if(in_array($_REQUEST['page'], $disabledSidebar)) { ?>
				<div class="col-xs-12">
					<?php $handler->loadModule($_REQUEST['page'],$_REQUEST['subpage']); ?>
				</div>
				<?php } else { ?>
				<div class="col-xs-8">
					<?php $handler->loadModule($_REQUEST['page'],$_REQUEST['subpage']); ?>
				</div>
				<div class="col-xs-4">
					<?php include(__PATH_TEMPLATE_ROOT__ . 'inc/modules/sidebar.php'); ?>
				</div>
				<?php } ?>
			</div>
		</div>
		<footer class="footer">
			<?php include(__PATH_TEMPLATE_ROOT__ . 'inc/modules/footer.php'); ?>
		</footer>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script src="<?php echo __PATH_TEMPLATE_JS__; ?>main.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</body>
</html>