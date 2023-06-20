<?php
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
	array("Administrar Noticias", array(
		"addnews" => "Publicar",
		"managenews" => "Editar / Eliminar",
	), "fas fa-file-alt"),
	array("Cuentas", array(
		"searchaccount" => "Buscar",
		"accountsfromip" => "Buscar cuenta por IP",
		"onlineaccounts" => "Cuentas conectadas",
		"accountinfo" => "", // HIDDEN
	), "fa fa-user"),
	array("Personajes", array(
		"searchcharacter" => "Buscar",
		"editcharacter" => "", // HIDDEN
	), "fa fa-users"),
	array("Baneados", array(
		"searchban" => "Buscador",
		"banaccount" => "Banear Cuenta",
		"latestbans" => "Ultimos Baneos",
		"blockedips" => "Bloquear IP (web)",
	), "fas fa-ban"),
	array("Creditos", array(
		"creditsconfigs" => "Configuracion",
		"creditsmanager" => "Administrador",
		"latestpaypal" => "Donaciones PayPal",
		"topvotes" => "Top Votadores",
	), "far fa-money-bill-alt"),
	array("Configuracion de la Web", array(
		"admincp_access" => "Acceso AdminCP",
		"connection_settings" => "Configuracion de Conexion",
		"website_settings" => "Configuracion Web",
		"modules_manager" => "Administrador de Modulos",
		"navbar" => "Menu de Navegacion",
		"usercp" => "Menu de Panel Usuario",
	), "fas fa-cogs"),
	array("Herramientas", array(
		"cachemanager" => "Administrador Cache",
		"cronmanager" => "Administrador Cron Job",
	), "fa fa-wrench"),
	array("Lenguajes", array(
		"phrases" => "Lista de Frases",
	), "fa fa-language"),
  array("Plugins", array(
		"plugins" => "Administrador de Plugins",
		"plugin_install" => "Importar Plugin",
	), "fa fa-plug"),
);
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="WebEngine AdminCP 2.0">
  <meta name="author" content="Lautaro Angelico">
  <meta name="robots" content="noindex,nofollow" />
  <title>WebEngine AdminCP</title>
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="favicon.ico" />
  <!-- Custom CSS -->
  <link href="assets/libs/flot/css/float-chart.css" rel="stylesheet" />
  <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet"/>
  <!-- Custom CSS -->
  <link href="css/style.min.css" rel="stylesheet" />
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
  <!-- ============================================================== -->
  <!-- Preloader - style you can find in spinners.css -->
  <!-- ============================================================== -->
  <div class="preloader">
    <div class="lds-ripple">
      <div class="lds-pos"></div>
      <div class="lds-pos"></div>
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- Main wrapper - style you can find in pages.scss -->
  <!-- ============================================================== -->
  <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
    data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <header class="topbar" data-navbarbg="skin5">
      <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header" data-logobg="skin5">
          <!-- ============================================================== -->
          <!-- Logo -->
          <!-- ============================================================== -->
          <a class="navbar-brand" href="<?php echo admincp_base(); ?>">
            <!-- Logo icon -->
            <b class="logo-icon ps-2">
              <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
              <!-- Dark Logo icon -->
              <img src="assets/images/logo-icon.png" alt="homepage" class="light-logo" width="35" />
            </b>
            <!--End Logo icon -->
            <!-- Logo text -->
            <span class="logo-text ms-2">
              <!-- dark Logo text -->
              <img src="assets/images/logo-text.png" alt="homepage" class="light-logo" />
            </span>
            <!-- Logo icon -->
            <!-- <b class="logo-icon"> -->
            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
            <!-- Dark Logo icon -->
            <!-- <img src="../assets/images/logo-text.png" alt="homepage" class="light-logo" /> -->

            <!-- </b> -->
            <!--End Logo icon -->
          </a>
          <!-- ============================================================== -->
          <!-- End Logo -->
          <!-- ============================================================== -->
          <!-- ============================================================== -->
          <!-- Toggle which is visible on mobile only -->
          <!-- ============================================================== -->
          <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
              class="ti-menu ti-close"></i></a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
          <!-- ============================================================== -->
          <!-- toggle and nav items -->
          <!-- ============================================================== -->
          <ul class="navbar-nav float-start me-auto">
            <li class="nav-item d-none d-lg-block">
              <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a>
            </li>
          </ul>
          <!-- ============================================================== -->
          <!-- Right side toggle and nav items -->
          <!-- ============================================================== -->
          <ul class="navbar-nav float-end">
            <li class="nav-item d-none d-lg-block">
              <a class="nav-link sidebartoggler waves-effect waves-light" href="<?php echo __BASE_URL__; ?>" target="_blank"><i class="fa fa-fw fa-home"></i> Inicio Web</a>
            </li>
            <li class="nav-item d-none d-lg-block">
              <a class="nav-link sidebartoggler waves-effect waves-light" href="<?php echo __BASE_URL__; ?>logout" target="_blank"><i class="fa fa-fw fa-power-off"></i> Salir</a>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <!-- ============================================================== -->
    <!-- End Topbar header -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <aside class="left-sidebar" data-sidebarbg="skin5">
      <!-- Sidebar scroll-->
      <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
          <ul id="sidebarnav" class="pt-4">

          <?php
							foreach($admincpSidebar as $sidebarItem) {
								$active = '';
								if(isset($_GET['module'])) {
									if(array_key_exists($_GET['module'], $sidebarItem[1])) {
										$active = ' active';
									}
								}

                echo '<li class="sidebar-item'.$active.'">';
                $itemIcon = (check_value($sidebarItem[2]) ? '<i class="'.$sidebarItem[2].'"></i>&nbsp;' : '');
                if(is_array($sidebarItem[1])) {
                  echo '<a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"aria-expanded="false">';
                    echo ''.$itemIcon.'<span class="hide-menu">'.$sidebarItem[0].' </span>';
                  echo '</a>';
                  echo '<ul aria-expanded="false" class="collapse first-level">';
                  foreach($sidebarItem[1] as $sidebarSubItemModule => $sidebarSubItemTitle) {
                    if(check_value($sidebarSubItemTitle)) echo '<li class="sidebar-item" style="background-color:#141619;"><a href="'.admincp_base($sidebarSubItemModule).'" class="sidebar-link"><span class="hide-menu"><i class="fas fa-angle-double-right"></i> '.$sidebarSubItemTitle.' </span></a></li>';
                  }
                  echo '</ul>';
                } else{
                  echo '<a href="'.admincp_base($sidebarItem[1]).'" class="sidebar-link"><span class="hide-menu"> '.$itemIcon.$sidebarItem[0].' </span></a>';
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
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!-- ============================================================== -->
    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
      <!-- ============================================================== -->
      <!-- Container fluid  -->
      <!-- ============================================================== -->
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <?php
              $req = isset($_REQUEST['module']) ? $_REQUEST['module'] : '';
              $handler->loadAdminCPModule($req);
            ?>
          </div>
        </div>
      </div>
      <!-- ============================================================== -->
      <!-- End Container fluid  -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- footer -->
      <!-- ============================================================== -->
      <footer class="footer text-center">
      </footer>
      <!-- ============================================================== -->
      <!-- End footer -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
    <!-- ============================================================== -->
  </div>
  <!-- ============================================================== -->
  <!-- End Wrapper -->
  <!-- ============================================================== -->
  <!-- ============================================================== -->
  <!-- All Jquery -->
  <!-- ============================================================== -->
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap tether Core JavaScript -->
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
  <script src="assets/extra-libs/sparkline/sparkline.js"></script>
  <!--Wave Effects -->
  <script src="js/waves.js"></script>
  <!--Menu sidebar -->
  <script src="js/sidebarmenu.js"></script>
  <!--Custom JavaScript -->
  <script src="js/custom.min.js"></script>
  <!--This page JavaScript -->
  <!-- <script src="../dist/js/pages/dashboards/dashboard1.js"></script> -->
  <!-- Charts js Files -->
  <script src="assets/libs/flot/excanvas.js"></script>
  <script src="assets/libs/flot/jquery.flot.js"></script>
  <script src="assets/libs/flot/jquery.flot.pie.js"></script>
  <script src="assets/libs/flot/jquery.flot.time.js"></script>
  <script src="assets/libs/flot/jquery.flot.stack.js"></script>
  <script src="assets/libs/flot/jquery.flot.crosshair.js"></script>
  <script src="assets/libs/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
  <script src="js/pages/chart/chart-page-init.js"></script>
  <script src="assets/extra-libs/DataTables/datatables.min.js"></script>
    <script>
      $(document).ready(function() {
            $('#zero_config').DataTable({
            lengthChange: false,
            ordering: false,
            "searching": true,
            "pageLength": 10,
            "info": true,
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
    <script>
      $(document).ready(function() {
            $('#zero_config2').DataTable({
            lengthChange: false,
            ordering: false,
            "searching": true,
            "pageLength": 10,
            "info": true,
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
    <script>
      $(function() {

	// Initiate bootstrap tooltips
	$('[data-bs-toggle="tooltip"]').tooltip();
	$('[data-bs-toggle="popover"]').popover();
});
    </script>
</body>

</html>