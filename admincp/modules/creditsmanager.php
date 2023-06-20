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

echo '<h1 class="page-header">Administrador de Creditos</h1>';

$creditSystem = new CreditSystem();

if(check_value($_POST['creditsconfig'], $_POST['identifier'], $_POST['credits'], $_POST['transaction'])) {
	try {
		$creditSystem->setConfigId($_POST['creditsconfig']);
		$creditSystem->setIdentifier($_POST['identifier']);
		switch($_POST['transaction']) {
			case 'add':
				$creditSystem->addCredits($_POST['credits']);
				message('success', 'Transaction completed.');
				break;
			case 'subtract':
				$creditSystem->subtractCredits($_POST['credits']);
				message('success', 'Transaction completed.');
				break;
			default:
				throw new Exception("Invalid transaction.");
		}
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="row">';
	echo '<div class="col-md-4">';
		echo '<div class="card">';
		echo '<div class="card-body">';

		echo '<div class="panel panel-primary">';
		echo '<div class="panel-heading">Agregar/Quitar Creditos</div>';
		echo '<div class="panel-body">';

			echo '<form role="form" method="post">';
				echo '<div class="input-group flex-nowrap mb-3 mt-3">';
					echo '<span class="input-group-text" id="addon-wrapping"><i class="fas fa-cogs"></i>&nbsp;Configuracion</span>';
					echo $creditSystem->buildSelectInput("creditsconfig", 1, "form-select");
				echo '</div>';

			echo '<div class="input-group flex-nowrap mb-3 mt-3">';
				echo '<span class="input-group-text" id="addon-wrapping"><i class="far fa-money-bill-alt"></i>&nbsp;Creditos</span>';
				echo '<input type="number" class="form-control" placeholder="0" aria-label="0" aria-describedby="addon-wrapping" id="credits1" name="credits">';
			echo '</div>';

			echo '<div class="input-group flex-nowrap mb-3 mt-3">';
				echo '<span class="input-group-text" id="addon-wrapping"><i class="fas fa-id-badge"></i>&nbsp;ID</span>';
				echo '<input type="text" class="form-control" placeholder="Ingresa ID" aria-label="Ingresa ID" aria-describedby="addon-wrapping" id="identifier1" name="identifier">';
			echo '</div>';
			echo '<p class="help-block">Dependiendo que selecciono en su configuracion, coloque el <b>userid</b>, <b>usuario</b>, <b>email</b> o <b>nombre del personaje</b>.</p>';

				echo '<div class="radio">';
					echo '<input type="radio" class="btn-check" name="transaction" id="AddCoins" value="add" checked>';
					echo '<label class="btn btn-outline-success" for="AddCoins"> <i class="fas fa-check"></i> Agregar Creditos </label>';
					echo '&nbsp;';
					echo '<input type="radio" class="btn-check" name="transaction" id="RemoveCoins" value="subtract">';
					echo '<label class="btn btn-outline-danger" for="RemoveCoins"> <i class="fas fa-times"></i> Quitar Creditos </label>';
				echo '</div><br />';

				echo '<button type="submit" class="btn btn-default">Ejecutar</button>';
			echo '</form>';

		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	
	echo '</div>';
	echo '<div class="col-md-8">';
		echo '<div class="card">';
			echo '<div class="card-body">';
				echo '<div class="panel panel-default">';
				echo '<div class="panel-body">';
					$creditsLogs = $creditSystem->getLogs();
					if(is_array($creditsLogs)) {
						echo '<table id="zero_config" class="table table-striped table-borderless">';
						echo '<thead>';
							echo '<tr>';
								echo '<th class="bg-dark text-white"><i class="fas fa-cog"></i> Creditos</th>';
								echo '<th class="bg-dark text-white"><i class="fas fa-id-badge"></i> ID</th>';
								echo '<th class="bg-dark text-white"><i class="fas fa-balance-scale"></i> Cantidad</th>';
								echo '<th class="bg-dark text-white"><i class="fas fa-arrows-alt"></i> Transaccion</th>';
								echo '<th class="bg-dark text-white"><i class="fas fa-calendar-alt"></i> Fecha</th>';
								echo '<th class="bg-dark text-white"><i class="fas fa-cogs"></i> Modulo</th>';
								echo '<th class="bg-dark text-white"><i class="fas fa-map-marker-alt"></i> IP</th>';
								echo '<th class="bg-dark text-white"><i class="fas fa-user-secret"></i> AdminCP</th>';
							echo '</tr>';
						echo '</thead>';
						echo '<tbody>';
						foreach($creditsLogs as $data) {
							
							$in_admincp = ($data['log_inadmincp'] == 1 ? '<span class="label label-success"><i class="fas fa-check"></i> Si</span>' : '<span class="label label-danger"><i class="fas fa-times"></i> No</span>');
							$transaction = ($data['log_transaction'] == "add" ? '<span class="label label-success"><i class="fas fa-check"></i> Agregado</span>' : '<span class="label label-danger"><i class="fas fa-times"></i> Quitado</span>');

							echo '<tr>';
								echo '<td>'.$data['log_config'].'</td>';
								echo '<td>'.$data['log_identifier'].'</td>';
								echo '<td>'.$data['log_credits'].'</td>';
								echo '<td>'.$transaction.'</td>';
								echo '<td>'.date("Y-m-d H:i", $data['log_date']).'</td>';
								echo '<td>'.$data['log_module'].'</td>';
								echo '<td>'.$data['log_ip'].'</td>';
								echo '<td>'.$in_admincp.'</td>';
							echo '</tr>';
						}
						echo '
						</tbody>
						</table>';
					} else {
						message('warning', 'There are no logs to display.');
					}
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
		
	echo '</div>';
echo '</div>';