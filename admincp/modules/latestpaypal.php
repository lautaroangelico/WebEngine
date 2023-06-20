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
?>
<h1 class="page-header">Donaciones de PayPal</h1>
<?php
try {
	$database = (config('SQL_USE_2_DB',true) ? $dB2 : $dB);
	
	$paypalDonations = $database->query_fetch("SELECT * FROM ".WEBENGINE_PAYPAL_TRANSACTIONS." ORDER BY id DESC");
	if(!is_array($paypalDonations)) throw new Exception("No hay transacciones en la Base de Datos.");
echo '<div class="row">';
	echo '<div class="col-md-12">';
		echo '<div class="card">';
			echo '<div class="card-body">';	
				echo '<table id="zero_config" class="table table-striped table-condensed">';
				echo '<thead class="table-dark">';
					echo '<tr>';
						echo '<th class="bg-dark text-white"><i class="fas fa-arrows-alt"></i> Transaccion ID</th>';
						echo '<th class="bg-dark text-white"><i class="fas fa-user"></i> Cuenta</th>';
						echo '<th class="bg-dark text-white"><i class="fas fa-balance-scale"></i> Cantidad</th>';
						echo '<th class="bg-dark text-white"><i class="fas fa-envelope"></i> PayPal Email</th>';
						echo '<th class="bg-dark text-white"><i class="fas fa-calendar-alt"></i> Fecha</th>';
						echo '<th class="bg-dark text-white"><i class="fas fa-exclamation-circle"></i> Estado</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				foreach($paypalDonations as $data) {
					$userData = $common->accountInformation($data['user_id']);
					$donation_status = ($data['transaction_status'] == 1 ? '<span class="badge bg-success"><i class="fas fa-check"></i> Aprobado</span>' : '<span class="badge bg-info"><i class="fas fa-exclamation-circle"></i> Pendiente</span>');
					
					echo '<tr>';
						echo '<td>'.$data['transaction_id'].'</td>';
						echo '<td><a href="'.admincp_base("accountinfo&id=".$data['user_id']).'">'.$userData[_CLMN_USERNM_].'</a></td>';
						echo '<td class="text-success"><b>$'.$data['payment_amount'].'</b></td>';
						echo '<td>'.$data['paypal_email'].'</td>';
						echo '<td>'.date("m/d/Y h:i A",$data['transaction_date']).'</td>';
						echo '<td class="text-center">'.$donation_status.'</td>';
					echo '</tr>';
				}
				echo '
				</tbody>
				</table>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}
?>