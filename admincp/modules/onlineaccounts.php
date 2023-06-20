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

echo '<h1 class="page-header">Cuentas Conectadas</h1>';

$Account = new Account();
$serverList = $Account->getServerList();

echo '<div class="row">';
	echo '<div class="col-md-12">';
		echo '<div class="card">';
			echo '<div class="card-body">';
				echo '<h3>Servidor:</h3>';
				echo '<div class="row">';
				if(is_array($serverList)) {
					foreach($serverList as $server) {
						echo '<div class="col-md-2 col-6">';
							echo '<div class="bg-dark p-10 text-white text-center m-1">';
							echo '<i class="fas fa-server fs-3 mb-1 font-16"></i>';
							echo '<h5 class="mb-0 mt-1">'.number_format($Account->getOnlineAccountCount($server)).'</h5>';
							echo '<small class="font-light">'.$server.'</small>';
							echo '</div>';
						echo '</div>';
					}
				}
						echo '<div class="col-md-2 col-6">';
							echo '<div class="bg-success p-10 text-white text-center m-1">';
							echo '<i class="fas fa-wifi fs-3 mb-1 font-16"></i>';
							echo '<h5 class="mb-0 mt-1">'.number_format($Account->getOnlineAccountCount()).'</h5>';
							echo '<small class="font-light">Total Conectados</small>';
							echo '</div>';
						echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';

$onlineAccounts = $Account->getOnlineAccountList();
echo '<div class="row">';
	echo '<div class="col-md-12">';
		echo '<div class="card">';
			echo '<div class="card-body">';
	echo '<h3>Lista de Cuentas:</h3>';
	if(is_array($onlineAccounts)) {
		echo '<div class="table-responsive">';
			echo '<table id="zero_config" class="table table-striped table-bordered">';
				echo '<thead>';
					echo '<tr>';
						echo '<th>Cuentas</th>';
						echo '<th>IP</th>';
						echo '<th>Servidor</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				foreach($onlineAccounts as $row) {
					echo '<tr>';
						echo '<td><a href="'.admincp_base('accountinfo&u='.$row[_CLMN_MS_MEMBID_]).'" target="_blank">'.$row[_CLMN_MS_MEMBID_].'</a></td>';
						echo '<td>'.$row[_CLMN_MS_IP_].'</td>';
						echo '<td>'.$row[_CLMN_MS_GS_].'</td>';
					echo '</tr>';
				}
				echo '</tbody>';
			echo '</table>';
		echo '</div>';                  
		echo '</div>';
	echo '</div>';
echo '</div>';
	} else {
		message('warning', 'No hay cuentas conectadas.');
	}
echo '</div>';
