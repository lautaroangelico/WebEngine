<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.2
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

$serverInfoCache = LoadCacheData('server_info.cache');
if(is_array($serverInfoCache)) {
	$srvInfo = explode("|", $serverInfoCache[1][0]);
}

$maxOnline = config('maximum_online', true);
$onlinePlayers = check_value($srvInfo[3]) ? $srvInfo[3] : 0;
$onlinePlayersPercent = check_value($maxOnline) ? $onlinePlayers*100/$maxOnline : 0;

$ColorTemplate = config('color_template',true);
$ColorTemplateAlpha = config('color_template',true)."80";

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
    	<meta name="viewport" content="width=device-width, initial-scale=1">
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
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Cinzel" rel="stylesheet">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
		<link href="<?php echo __PATH_TEMPLATE_CSS__; ?>style.css" rel="stylesheet" media="screen">
		<link href="<?php echo __PATH_TEMPLATE_CSS__; ?>profiles.css" rel="stylesheet" media="screen">
		<link href="<?php echo __PATH_TEMPLATE_CSS__; ?>castle-siege.css" rel="stylesheet" media="screen">
		<link href="<?php echo __PATH_TEMPLATE_CSS__; ?>override.css" rel="stylesheet" media="screen">
		<script src="https://kit.fontawesome.com/8b5cc27615.js" crossorigin="anonymous"></script>
		<script>
			var baseUrl = '<?php echo __BASE_URL__; ?>';
			var ColorTemplate = '<?php echo $ColorTemplate;?>';
			var rootElement = document.documentElement;
			rootElement.style.setProperty("--ColorTemplate", ColorTemplate);
			rootElement.style.setProperty("--ColorTemplateAlpha", ColorTemplateAlpha);
		</script>
	</head>
	<body>

	<script src="<?php echo __PATH_TEMPLATE_JS__; ?>tooltip.js"></script>
		<div class="global-top-bar">
			<div class="global-top-bar-content">
				<div class="row">
					<div class="col-lg-6 text-left global-top-bar-nopadding">
					<?php if(config('language_switch_active',true)) templateLanguageSelector(); ?>
					</div>
					<div class="col-lg-6 text-end global-top-bar-nopadding">
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
		<div id="header">
			<a href="<?php echo __BASE_URL__; ?>">
				<img class="webengine-mu-logo" src="<?php echo __PATH_TEMPLATE_IMG__; ?>logo.png" title="<?php config('server_name'); ?>"/>
			</a>
		</div>
		<div class="header-info-container">
		<div class="header-info">
			<div class="row">
				<div class="col-lg-12">
					<div class="headersito">
						<?php templateBuildNavbar(); ?>
					</div>
				</div>
			</div>
		</div>
		</div>
		<div id="container">
			<div id="content">
				<div class="row">
				<?php if(in_array($_REQUEST['page'], $disabledSidebar)) { ?>
				<div class="col-lg-12">
					<?php $handler->loadModule($_REQUEST['page'],$_REQUEST['subpage']); ?>
				</div>
				<?php } else { ?>
				<div class="col-lg-8">
					<?php $handler->loadModule($_REQUEST['page'],$_REQUEST['subpage']); ?>
				</div>
				<div class="col-lg-4">
					<?php include(__PATH_TEMPLATE_ROOT__ . 'inc/modules/sidebar.php'); ?>
				</div>
				<?php } ?>
				</div>
			</div>
		</div>
		<footer class="footer">
			<?php include(__PATH_TEMPLATE_ROOT__ . 'inc/modules/footer.php'); ?>
		</footer>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

		<script type="text/javascript" src="<?php echo __PATH_TEMPLATE_JS__; ?>datatables.min.js"></script>

		<script>
        $(document).ready(function() {
            $('#RankingGeneral').DataTable({
            lengthChange: false,
            ordering: false,
            "searching": true,
            "pageLength": 100,
            "info": false,
			"bPaginate": false,
            "language": {
                        "sProcessing":     "Procesando...",
                        "sZeroRecords":    "No se encontraron resultados",
                        "sEmptyTable":     "No hay datos disponibles",
                        "sSearch":         "",
                        "sLoadingRecords": "Cargando...",
                        "sSearchPlaceholder":    "Buscador",
                        "oAria": {
                            "sSortAscending":  ": Ordena la columna de forma ascendente",
                            "sSortDescending": ": Ordena la columna de forma descendente"
                        },
                        "paginate": {
                                    "next":       "Siguiente",
                                    "previous":   "Anterior"
                        },
                    }
            });
        } );
    </script>

		<script src="<?php echo __PATH_TEMPLATE_JS__; ?>main.js"></script>
		<script src="<?php echo __PATH_TEMPLATE_JS__; ?>tooltip.js"></script>
		
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
		
		<script>
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>
		
	</body>
</html>