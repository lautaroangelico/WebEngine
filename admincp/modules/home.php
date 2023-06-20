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

// check install directory
if(file_exists(__ROOT_DIR__ . 'install/')) {
	message('warning', 'Su Webengine contiene la carpeta <strong>install</strong> en el directorio, es recomendable renombrarla o eliminar la carpeta.', 'ATENCION');
}
			$database = (config('SQL_USE_2_DB',true) ? $dB2 : $dB);
echo '<div class="row">';
	echo '<div class="col-md-12">';
		echo '<div class="card">';
			echo '<div class="card-body">';
				echo '<div class="d-md-flex align-items-center">';
					echo '<div>';
						echo '<h4 class="card-title">Informacion Web</h4>';
						echo '<h5 class="card-subtitle">Informacion general de la web.</h5>';
					echo '</div>';
				echo '</div>';
				echo '<div class="row">';
					echo '<div class="col-md-2 col-6">';
						echo '<div class="bg-dark p-10 text-white text-center m-1">';
						echo '<i class="fab fa-windows fs-3 mb-1 font-16"></i>';
						echo '<h5 class="mb-0 mt-1">'.PHP_OS.'</h5>';
						echo '<small class="font-light">Sistema Operativo</small>';
						echo '</div>';
					echo '</div>';
					echo '<div class="col-md-2 col-6">';
						echo '<div class="bg-dark p-10 text-white text-center m-1">';
						echo '<i class="fab fa-php fs-3 font-16"></i>';
						echo '<h5 class="mb-0 mt-1">'.phpversion().'</h5>';
						echo '<small class="font-light">Version PHP</small>';
						echo '</div>';
					echo '</div>';
					echo '<div class="col-md-2 col-6">';
						echo '<div class="bg-dark p-10 text-white text-center m-1">';
						echo '<i class="fab fa-wikipedia-w fs-3 mb-1 font-16"></i>';
						echo '<h5 class="mb-0 mt-1">'.__WEBENGINE_VERSION__.'</h5>';
						echo '<small class="font-light">Webengine Version</small>';
						echo '</div>';
					echo '</div>';
					// Plugins Status
					$pluginStatus = (config('plugins_system_enable',true) ? 'Enabled' : 'Disabled');
					echo '<div class="col-md-2 col-6">';
						echo '<div class="bg-dark p-10 text-white text-center m-1">';
						echo '<i class="fas fa-plug fs-3 mb-1 font-16"></i>';
						echo '<h5 class="mb-0 mt-1">'.$pluginStatus.'</h5>';
						echo '<small class="font-light">Sistema de Plugins</small>';
						echo '</div>';
					echo '</div>';
					// Scheduled Tasks
					$scheduledTasks = $database->query_fetch_single("SELECT COUNT(*) as result FROM ".WEBENGINE_CRON."");
					echo '<div class="col-md-2 col-6">';
						echo '<div class="bg-dark p-10 text-white text-center m-1">';
						echo '<i class="fas fa-tasks fs-3 mb-1 font-16"></i>';
						echo '<h5 class="mb-0 mt-1">'.number_format($scheduledTasks['result']).'</h5>';
						echo '<small class="font-light">Cron Jobs Activos</small>';
						echo '</div>';
					echo '</div>';
					// Admins
					$admincpUsers = implode(", ", array_keys(config('admins',true)));
					echo '<div class="col-md-2 col-6">';
						echo '<div class="bg-dark p-10 text-white text-center m-1">';
						echo '<i class="fas fa-user-secret fs-3 mb-1 font-16"></i>';
						echo '<h5 class="mb-0 mt-1">'.$admincpUsers.'</h5>';
						echo '<small class="font-light">Administradores</small>';
						echo '</div>';
					echo '</div>';
					echo '</div>';
			echo '</div>';                  
		echo '</div>';
	echo '</div>';
echo '</div>';

echo '<div class="row">';
	echo '<div class="col-md-12">';
		echo '<div class="card">';
			echo '<div class="card-body">';
				echo '<div class="d-md-flex align-items-center">';
					echo '<div>';
						echo '<h4 class="card-title">Informacion Servidor</h4>';
						echo '<h5 class="card-subtitle">Informacion general del servidor.</h5>';
					echo '</div>';
				echo '</div>';
				echo '<div class="row">';
				
				// Total Accounts
				$totalAccounts = $database->query_fetch_single("SELECT COUNT(*) as result FROM MEMB_INFO");
					echo '<div class="col-md-2 col-6">';
						echo '<div class="bg-info p-10 text-white text-center m-1">';
						echo '<i class="fas fa-address-book fs-3 mb-1 font-16"></i>';
						echo '<h5 class="mb-0 mt-1">'.number_format($totalAccounts['result']).'</h5>';
						echo '<small class="font-light">Cuentas Creadas</small>';
						echo '</div>';
					echo '</div>';
				// Total Characters
				$totalCharacters = $dB->query_fetch_single("SELECT COUNT(*) as result FROM Character");
					echo '<div class="col-md-2 col-6">';
						echo '<div class="bg-primary p-10 text-white text-center m-1">';
						echo '<i class="fas fa-user fs-3 font-16"></i>';
						echo '<h5 class="mb-0 mt-1">'.number_format($totalCharacters['result']).'</h5>';
						echo '<small class="font-light">Personajes Creados</small>';
						echo '</div>';
					echo '</div>';
				// Total Guilds
				$totalGuilds = $dB->query_fetch_single("SELECT COUNT(*) as result FROM Guild");
					echo '<div class="col-md-2 col-6">';
						echo '<div class="bg-cyan p-10 text-white text-center m-1">';
						echo '<i class="fas fa-shield-alt fs-3 mb-1 font-16"></i>';
						echo '<h5 class="mb-0 mt-1">'.number_format($totalGuilds['result']).'</h5>';
						echo '<small class="font-light">Clanes Creados</small>';
						echo '</div>';
					echo '</div>';
				// Banned Accounts
				$bannedAccounts = $database->query_fetch_single("SELECT COUNT(*) as result FROM MEMB_INFO WHERE bloc_code = 1");
					echo '<div class="col-md-2 col-6">';
						echo '<div class="bg-danger p-10 text-white text-center m-1">';
						echo '<i class="fas fa-ban fs-3 mb-1 font-16"></i>';
						echo '<h5 class="mb-0 mt-1">'.number_format($bannedAccounts['result']).'</h5>';
						echo '<small class="font-light">Baneados</small>';
						echo '</div>';
					echo '</div>';
				// Usuarios Vip
				$VipUsers = $database->query_fetch_single("SELECT COUNT(*) as result FROM MEMB_INFO WHERE AccountLevel > 0");	
					echo '<div class="col-md-2 col-6">';
						echo '<div class="bg-warning p-10 text-white text-center m-1">';
						echo '<i class="fas fa-star fs-3 mb-1 font-16"></i>';
						echo '<h5 class="mb-0 mt-1">'.number_format($VipUsers['result']).'</h5>';
						echo '<small class="font-light">Usuarios VIP</small>';
						echo '</div>';
					echo '</div>';
				// Usuarios Onlines
				$OnlineUsers = $database->query_fetch_single("SELECT COUNT(*) as result FROM MEMB_STAT WHERE ConnectStat = 1");	
					echo '<div class="col-md-2 col-6">';
						echo '<div class="bg-success p-10 text-white text-center m-1">';
						echo '<i class="fas fa-wifi fs-3 mb-1 font-16"></i>';
						echo '<h5 class="mb-0 mt-1">'.number_format($OnlineUsers['result']).'</h5>';
						echo '<small class="font-light">Usuarios Conectados</small>';
						echo '</div>';
					echo '</div>';
					echo '</div>';
				echo '</div>';
			echo '</div>';                  
		echo '</div>';
	echo '</div>';
echo '</div>';


echo '<div class="row">';
	echo '<div class="col-md-12">';
		echo '<div class="card">';
			echo '<div class="card-body">';
				echo '<div class="d-md-flex align-items-center">';
					echo '<div>';
						echo '<h4 class="card-title">Ultimos Registrados</h4>';
						echo '<h5 class="card-subtitle">Ultimos registrados en el servidor.</h5>';
					echo '</div>';
				echo '</div>';
				echo '<div class="row">';
					$newRegs = $database->query_fetch("SELECT TOP 200 memb_guid, memb___id, mail_addr,bloc_code,AccountLevel,AccountExpireDate FROM MEMB_INFO ORDER BY memb_guid DESC");
					echo '<div class="table-responsive">';
						echo '<table id="zero_config" class="table table-striped table-bordered">';
							echo '<thead>';
								echo '<tr>';
									echo '<th>Id</th>';
									echo '<th>Username</th>';
									echo '<th>Email</th>';
									echo '<th class="text-center">Cuenta</th>';
									echo '<th class="text-center">Vip</th>';
									echo '<th>Expiracion VIP</th>';
									echo '<th></th>';
								echo '</tr>';
							echo '</thead>';
							echo '<tbody>';
							foreach($newRegs as $thisReg) {
								echo '<tr>';
									echo '<td>'.$thisReg['memb_guid'].'</td>';
									echo '<td>'.$thisReg['memb___id'].'</td>';
									echo '<td>'.$thisReg['mail_addr'].'</td>';
									if($thisReg['bloc_code'] == 0){
										echo '<td class="text-center"><span class="badge bg-success">Activa</span></td>';
									} else{
										echo '<td class="text-center"><span class="badge bg-danger">Baneada</span></td>';
									}
									if($thisReg['AccountLevel'] == 1){
										echo '<td class="text-center"><span class="badge bg-danger">Bronce</span></td>';
									}else if($thisReg['AccountLevel'] == 2){
										echo '<td class="text-center"><span class="badge bg-secondary">Plata</span></td>';
									}else if($thisReg['AccountLevel'] == 3){
										echo '<td class="text-center"><span class="badge bg-warning">Oro</span></td>';
									}else {
										echo '<td class="text-center"><span class="badge bg-dark">Sin Vip</span></td>';
									}
									if($thisReg['AccountLevel'] > 0){
										setlocale(LC_TIME, 'es_ES.UTF-8');
										setlocale(LC_TIME, 'spanish');
										$Vencimiento = strftime("%d de %B del %Y @ %H:%M", strtotime($thisReg['AccountExpireDate']));	
										echo '<td>'.$Vencimiento.'</td>';
									}else {
										echo '<td>Expirado</td>';
									}
									echo '<td style="text-align:right;"><a href="'.admincp_base("accountinfo&id=".$thisReg[_CLMN_MEMBID_]).'" class="btn btn-xs btn-default">Account Information</a></td>';
								echo '</tr>';
							}
							echo '</tbody>';
						echo '</table>';
					echo '</div>';
				echo '</div>';
			echo '</div>';                  
		echo '</div>';
	echo '</div>';
echo '</div>';


echo '<div class="row">';
	echo '<div class="col-md-12">';
		echo '<div class="card">';
			echo '<div class="card-body">';
				echo '<div class="row">';
					echo '<div class="col-md-12 col-12">';
						echo '<div class="alert alert-info text-center" role="alert">';
							date_default_timezone_set('America/Argentina/Tucuman');
							setlocale(LC_TIME, "spanish");
							$dia = strftime("%A");
							$dian = strftime("%d");
							$mes = strftime("%B");
							$anio = strftime("%Y");
							$hora = strftime("%H:%M:%S");
							echo '<h3>'.ucfirst($dia).' '.$dian.' de '.ucfirst($mes).' del '.$anio.' - '.$hora.'</h3>';
						echo '</div>';	
					echo '</div>';
				echo '</div>';
			echo '</div>';                  
		echo '</div>';
	echo '</div>';
echo '</div>';