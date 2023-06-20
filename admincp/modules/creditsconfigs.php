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

echo '<h1 class="page-header">Configuraciones de Creditos</h1>';

$creditSystem = new CreditSystem();

// NEW CONFIG
if(check_value($_POST['new_submit'])) {
	try {
		if(!check_value($_POST['new_title'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['new_database'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['new_table'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['new_credits_column'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['new_user_column'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['new_user_column_id'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['new_checkonline'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['new_display'])) throw new Exception("Please fill all the required fields.");
		
		$creditSystem->setConfigTitle($_POST['new_title']);
		$creditSystem->setConfigDatabase($_POST['new_database']);
		$creditSystem->setConfigTable($_POST['new_table']);
		$creditSystem->setConfigCreditsColumn($_POST['new_credits_column']);
		$creditSystem->setConfigUserColumn($_POST['new_user_column']);
		$creditSystem->setConfigUserColumnId($_POST['new_user_column_id']);
		$creditSystem->setConfigCheckOnline($_POST['new_checkonline']);
		$creditSystem->setConfigDisplay($_POST['new_display']);
		$creditSystem->saveConfig();
		
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// EDIT CONFIG
if(check_value($_POST['edit_submit'])) {
	try {
		if(!check_value($_POST['edit_id'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_title'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_database'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_table'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_credits_column'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_user_column'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_user_column_id'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_checkonline'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_display'])) throw new Exception("Please fill all the required fields.");
		
		$creditSystem->setConfigId($_POST['edit_id']);
		$creditSystem->setConfigTitle($_POST['edit_title']);
		$creditSystem->setConfigDatabase($_POST['edit_database']);
		$creditSystem->setConfigTable($_POST['edit_table']);
		$creditSystem->setConfigCreditsColumn($_POST['edit_credits_column']);
		$creditSystem->setConfigUserColumn($_POST['edit_user_column']);
		$creditSystem->setConfigUserColumnId($_POST['edit_user_column_id']);
		$creditSystem->setConfigCheckOnline($_POST['edit_checkonline']);
		$creditSystem->setConfigDisplay($_POST['edit_display']);
		
		$creditSystem->editConfig();
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// DELETE CONFIG
if(check_value($_GET['delete'])) {
	try {
		$creditSystem->setConfigId($_GET['delete']);
		$creditSystem->deleteConfig();
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="row">';
	echo '<div class="col-md-4">';
	
		if(!check_value($_GET['edit'])) {
			echo '<div class="card">';
	echo '<div class="card-body">';
			// ADD NEW CONFIG
			echo '<div class="panel panel-primary">';
			echo '<div class="panel-heading">Nueva Configuracion</div>';
			echo '<div class="panel-body">';

				echo '<form role="form" action="'.admincp_base("creditsconfigs").'" method="post">';

					echo '<div class="input-group flex-nowrap mb-3 mt-3">';
						echo '<span class="input-group-text" id="addon-wrapping"><i class="fas fa-align-center"></i>&nbsp;Titulo</span>';
						echo '<input type="text" class="form-control" placeholder="Ingresa titulo" aria-label="Ingresa titulo" aria-describedby="addon-wrapping" id="input_1" name="new_title">';
				 	echo '</div>';

					 echo '<div class="input-group flex-nowrap mb-3 mt-3">';
						echo '<span class="input-group-text" id="addon-wrapping"><i class="fas fa-table"></i>&nbsp;Tabla</span>';
						echo '<input type="text" class="form-control" placeholder="Ingresa nombre de tabla" aria-label="Ingresa nombre de tabla" aria-describedby="addon-wrapping" id="input_2" name="new_table">';
					echo '</div>';

					echo '<div class="input-group flex-nowrap mb-3 mt-3">';
						echo '<span class="input-group-text" id="addon-wrapping"><i class="fas fa-columns"></i>&nbsp;Columna de Tabla</span>';
						echo '<input type="text" class="form-control" placeholder="Ingresa nombre de columna" aria-label="Ingresa nombre de columna" aria-describedby="addon-wrapping" id="input_3" name="new_credits_column">';
					echo '</div>';

					echo '<div class="input-group flex-nowrap mb-3 mt-3">';
						echo '<span class="input-group-text" id="addon-wrapping"><i class="fas fa-columns"></i>&nbsp;Columna de Usuario</span>';
						echo '<input type="text" class="form-control" placeholder="Ingresa nombre de columna" aria-label="Ingresa nombre de columna" aria-describedby="addon-wrapping" id="input_4" name="new_user_column">';
					echo '</div>';

					echo '<label><i class="fas fa-database"></i> Base de datos:</label>';
					echo '<div class="radio">';
						echo '<input type="radio" class="btn-check" name="new_database" id="database1" value="MuOnline" checked>';
						echo '<label class="btn btn-outline-info" for="database1"> <i class="fas fa-database"></i> ' . config('SQL_DB_NAME', true) .' </label>';
					
					if(config('SQL_USE_2_DB',true)) {
							echo '<input type="radio" class="btn-check" name="new_database" id="database2" value="Me_MuOnline">';
							echo '<label class="btn btn-outline-secondary" for="database2"> <i class="fas fa-database"></i> ' . config('SQL_DB_2_NAME', true) .' </label>';
						echo '</div><br />';
					} else{ echo '</div><br />'; }			
					
					echo '<label><i class="fas fa-id-badge"></i> Identificador de Usuario:</label>';
					echo '<div class="radio">';
						echo '<input type="radio" class="btn-check" name="new_user_column_id" id="userid" value="userid" checked>';
						echo '<label class="btn btn-outline-primary" for="userid"> <i class="fas fa-id-card"></i> User ID </label>';

						echo '<input type="radio" class="btn-check" name="new_user_column_id" id="username" value="username">';
						echo '<label class="btn btn-outline-info" for="username"> <i class="fas fa-user"></i> Usuario </label>';

						echo '<input type="radio" class="btn-check" name="new_user_column_id" id="email" value="email">';
						echo '<label class="btn btn-outline-dark" for="email"> <i class="fas fa-envelope"></i> Email </label>';

						echo '<input type="radio" class="btn-check" name="new_user_column_id" id="character" value="character">';
						echo '<label class="btn btn-outline-secondary" for="character"> <i class="fas fa-male"></i> Nombre de PJ </label>';

					echo '</div><br />';

					echo '<label><i class="fas fa-signal"></i> Chequear si esta conectado:</label>';
					echo '<div class="radio">';
						echo '<input type="radio" class="btn-check" name="new_checkonline" id="checkSI" value="1" checked>';
						echo '<label class="btn btn-outline-success" for="checkSI"> <i class="fas fa-check"></i> Si </label>';

						echo '<input type="radio" class="btn-check" name="new_checkonline" id="checkNO" value="0">';
						echo '<label class="btn btn-outline-danger" for="checkNO"> <i class="fas fa-times"></i> No </label>';
					echo '</div><br />';

					echo '<label><i class="fas fa-eye"></i> Mostrar en Mi Cuenta:</label>';
					echo '<div class="radio">';

						echo '<input type="radio" class="btn-check" name="new_display" id="displaySI" value="1" checked>';
						echo '<label class="btn btn-outline-success" for="displaySI"> <i class="fas fa-check"></i> Si </label>';

						echo '<input type="radio" class="btn-check" name="new_display" id="displayNO" value="0">';
						echo '<label class="btn btn-outline-danger" for="displayNO"> <i class="fas fa-times"></i> No </label>';

					echo '</div><br />';	

					

					echo '<button type="submit" name="new_submit" value="1" class="btn btn-default">Agregar Configuracion</button>';
				echo '</form>';

			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		} else {
			// EDIT
			$creditSystem->setConfigId($_GET['edit']);
			$cofigsData = $creditSystem->showConfigs(true);
			echo '<div class="card">';
	echo '<div class="card-body">';
			echo '<div class="panel panel-yellow">';
			echo '<div class="panel-heading">Editar Configuracion</div>';
			echo '<div class="panel-body">';
				echo '<form role="form" action="'.admincp_base("creditsconfigs").'" method="post">';

				echo '<input type="hidden" name="edit_id" value="'.$cofigsData['config_id'].'"/>';

				echo '<div class="input-group flex-nowrap mb-3 mt-3">';
					echo '<span class="input-group-text" id="addon-wrapping"><i class="fas fa-align-center"></i>&nbsp;Titulo</span>';
					echo '<input type="text" class="form-control" placeholder="Ingresa titulo" aria-label="Ingresa titulo" aria-describedby="addon-wrapping" id="input_1" name="edit_title" value="'.$cofigsData['config_title'].'">';
				echo '</div>';

				echo '<div class="input-group flex-nowrap mb-3 mt-3">';
					echo '<span class="input-group-text" id="addon-wrapping"><i class="fas fa-table"></i>&nbsp;Tabla</span>';
					echo '<input type="text" class="form-control" placeholder="Ingresa nombre de tabla" aria-label="Ingresa nombre de tabla" aria-describedby="addon-wrapping" id="input_2" name="edit_table" value="'.$cofigsData['config_table'].'">';
				echo '</div>';

				echo '<div class="input-group flex-nowrap mb-3 mt-3">';
					echo '<span class="input-group-text" id="addon-wrapping"><i class="fas fa-columns"></i>&nbsp;Columna de Tabla</span>';
					echo '<input type="text" class="form-control" placeholder="Ingresa nombre de columna" aria-label="Ingresa nombre de columna" aria-describedby="addon-wrapping" id="input_3" name="edit_credits_column" value="'.$cofigsData['config_credits_col'].'">';
				echo '</div>';

				echo '<div class="input-group flex-nowrap mb-3 mt-3">';
					echo '<span class="input-group-text" id="addon-wrapping"><i class="fas fa-columns"></i>&nbsp;Columna de Usuario</span>';
					echo '<input type="text" class="form-control" placeholder="Ingresa nombre de columna" aria-label="Ingresa nombre de columna" aria-describedby="addon-wrapping" id="input_4" name="edit_user_column" value="'.$cofigsData['config_user_col'].'">';
				echo '</div>';

				echo '<label><i class="fas fa-database"></i> Base de datos:</label>';
					echo '<div class="radio">';
						echo '<input type="radio" class="btn-check" name="edit_database" id="database1" value="MuOnline" '.($cofigsData['config_database'] == "MuOnline" ? 'checked' : null).'>';
						echo '<label class="btn btn-outline-info" for="database1"> <i class="fas fa-database"></i> ' . config('SQL_DB_NAME', true) .' </label>';
					
				if(config('SQL_USE_2_DB',true)) {
						echo '<input type="radio" class="btn-check" name="edit_database" id="database2" value="Me_MuOnline" '.($cofigsData['config_database'] == "Me_MuOnline" ? 'checked' : null).'>';
						echo '<label class="btn btn-outline-secondary" for="database2"> <i class="fas fa-database"></i> ' . config('SQL_DB_2_NAME', true) .' </label>';
					echo '</div><br />';
				} else{ echo '</div><br />'; }	

				echo '<label><i class="fas fa-id-badge"></i> Identificador de Usuario:</label>';
				echo '<div class="radio">';
					echo '<input type="radio" class="btn-check" name="edit_user_column_id" id="userid" value="userid" '.($cofigsData['config_user_col_id'] == "userid" ? 'checked' : null).'>';
					echo '<label class="btn btn-outline-primary" for="userid"> <i class="fas fa-id-card"></i> User ID </label>';

					echo '<input type="radio" class="btn-check" name="edit_user_column_id" id="username" value="username" '.($cofigsData['config_user_col_id'] == "username" ? 'checked' : null).'>';
					echo '<label class="btn btn-outline-info" for="username"> <i class="fas fa-user"></i> Usuario </label>';

					echo '<input type="radio" class="btn-check" name="edit_user_column_id" id="email" value="email" '.($cofigsData['config_user_col_id'] == "email" ? 'checked' : null).'>';
					echo '<label class="btn btn-outline-dark" for="email"> <i class="fas fa-envelope"></i> Email </label>';

					echo '<input type="radio" class="btn-check" name="edit_user_column_id" id="character" value="character" '.($cofigsData['config_user_col_id'] == "character" ? 'checked' : null).'>';
					echo '<label class="btn btn-outline-secondary" for="character"> <i class="fas fa-male"></i> Nombre de PJ </label>';
				echo '</div><br />';

				echo '<label><i class="fas fa-signal"></i> Chequear si esta conectado:</label>';
				echo '<div class="radio">';
					echo '<input type="radio" class="btn-check" name="edit_checkonline" id="checkSI" value="1" '.($cofigsData['config_checkonline'] == 1 ? 'checked' : null).'>';
					echo '<label class="btn btn-outline-success" for="checkSI"> <i class="fas fa-check"></i> Si </label>';

					echo '<input type="radio" class="btn-check" name="edit_checkonline" id="checkNO" value="0" '.($cofigsData['config_checkonline'] == 0 ? 'checked' : null).'>';
					echo '<label class="btn btn-outline-danger" for="checkNO"> <i class="fas fa-times"></i> No </label>';
				echo '</div><br />';

				echo '<label><i class="fas fa-eye"></i> Mostrar en Mi Cuenta:</label>';
				echo '<div class="radio">';
					echo '<input type="radio" class="btn-check" name="edit_display" id="diplaySi" value="1" '.($cofigsData['config_display'] == 1 ? 'checked' : null).'>';
					echo '<label class="btn btn-outline-success" for="diplaySi"> <i class="fas fa-check"></i> Si </label>';

					echo '<input type="radio" class="btn-check" name="edit_display" id="displayNo" value="0" '.($cofigsData['config_display'] == 0 ? 'checked' : null).'>';
					echo '<label class="btn btn-outline-danger" for="displayNo"> <i class="fas fa-times"></i> No </label>';
				echo '</div><br />';


					echo '<button type="submit" name="edit_submit" value="1" class="btn btn-warning">Actualizar Configuracion</button>';
				echo '</form>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	
	echo '</div>';
	echo '<div class="col-md-8">';
		$cofigsList = $creditSystem->showConfigs();
		if(is_array($cofigsList)) {
			foreach($cofigsList as $data) {
				
				$checkOnline = ($data['config_checkonline'] ? 'Si' : 'No');
				$configdisplay = ($data['config_display'] ? 'Si' : 'No');
				$databaseDisplay = $data['config_database'] == 'MuOnline' ? config('SQL_DB_NAME', true) : config('SQL_DB_2_NAME', true);
				
		echo '<div class="card">';
			echo '<div class="card-body">';
				echo '<div class="panel panel-default">';
					echo '<div class="panel-heading">';
						echo '<button type="button" class="btn btn-outline-primary">'.$data['config_title'].'</button>';
						echo '<a href="'.admincp_base("creditsconfigs&delete=".$data['config_id']).'" class="btn btn-danger float-end">Borrar</a>';
						echo '&nbsp;';
						echo '<a href="'.admincp_base("creditsconfigs&edit=".$data['config_id']).'" class="btn btn-info float-end" style="margin-right:5px;"><i class="fas fa-edit"></i> Editar</a>';
					echo '</div>';
					echo '<div class="panel-body">';
					echo '<br>';
					echo '<div class="row">';
						echo '<div class="table-responsive">';

						echo '<table class="table table-sm">';
							echo '<thead>';
								echo '<tr>';
									echo '<th class="bg-dark text-white text-center p-1"><i class="fas fa-sliders-h"></i>&nbsp;Configuracion ID</th>';
									echo '<th class="bg-dark text-white text-center p-1"><i class="fas fa-database"></i>&nbsp;Base de Datos</th>';
								echo '</tr>';
							echo '</thead>';
							echo '<tbody>';
								echo '<tr>';
									echo '<td class="text-center border border-dark p-1">'.$data['config_id'].'</th>';
									echo '<td class="text-center border border-dark p-1">'.$databaseDisplay.'</td>';
								echo '</tr>';
							echo '</tbody>';
						echo '</table>';

						echo '<table class="table table-sm">';
							echo '<thead>';
								echo '<tr>';
									echo '<th class="bg-dark text-white text-center p-1"><i class="fas fa-table"></i>&nbsp;Tabla</th>';
									echo '<th class="bg-dark text-white text-center p-1"><i class="fas fa-columns"></i>&nbsp;Columna de Tabla</th>';
									echo '<th class="bg-dark text-white text-center p-1"><i class="fas fa-columns"></i>&nbsp;Columna de Usuario</th>';
								echo '</tr>';
							echo '</thead>';
							echo '<tbody>';
								echo '<tr>';
									echo '<td class="text-center border border-dark p-1">'.$data['config_table'].'</th>';
									echo '<td class="text-center border border-dark p-1">'.$data['config_credits_col'].'</td>';
									echo '<td class="text-center border border-dark p-1">'.$data['config_user_col'].'</td>';
								echo '</tr>';
							echo '</tbody>';
						echo '</table>';

						echo '<table class="table table-sm">';
							echo '<thead>';
								echo '<tr>';
									if($data['config_user_col_id'] == 'userid'){
										echo '<th class="bg-primary text-white text-center p-1"><i class="fas fa-id-badge"></i>&nbsp;ID Columna de Usuario</th>';
									}else if($data['config_user_col_id'] == 'username'){
										echo '<th class="bg-info text-white text-center p-1"><i class="fas fa-id-badge"></i>&nbsp;ID Columna de Usuario</th>';
									}else if($data['config_user_col_id'] == 'email'){
										echo '<th class="bg-dark text-white text-center p-1"><i class="fas fa-id-badge"></i>&nbsp;ID Columna de Usuario</th>';
									}else if($data['config_user_col_id'] == 'character'){
										echo '<th class="bg-secondary text-white text-center p-1"><i class="fas fa-id-badge"></i>&nbsp;ID Columna de Usuario</th>';
									}
									if($data['config_checkonline']){ echo '<th class="bg-success text-white text-center p-1"><i class="fas fa-signal"></i>&nbsp;Chequear si esta Conectado</th>'; } 
									else { echo '<th class="bg-danger text-white text-center p-1"><i class="fas fa-signal"></i>&nbsp;Chequear si esta Conectado</th>'; }
									if($data['config_display']){ echo '<th class="bg-success text-white text-center p-1"><i class="fas fa-eye"></i>&nbsp;Mostrar en Mi Cuenta</th>'; }
									else { echo '<th class="bg-danger text-white text-center p-1"><i class="fas fa-eye"></i>&nbsp;Mostrar en Mi Cuenta</th>'; }
								echo '</tr>';
							echo '</thead>';
							echo '<tbody>';
								echo '<tr>';
									if($data['config_user_col_id'] == 'userid'){
										echo '<td class="text-center border border-primary text-primary p-1">'.$data['config_user_col_id'].'</th>';
									}else if($data['config_user_col_id'] == 'username'){
										echo '<td class="text-center border border-info text-info p-1">'.$data['config_user_col_id'].'</th>';
									}else if($data['config_user_col_id'] == 'email'){
										echo '<td class="text-center border border-dark text-dark p-1">'.$data['config_user_col_id'].'</th>';
									}else if($data['config_user_col_id'] == 'character'){
										echo '<td class="text-center border border-secondary text-secondary p-1">'.$data['config_user_col_id'].'</th>';
									}
									if($data['config_checkonline']){ echo '<td class="text-center border border-success text-success p-1"><i class="fas fa-check"></i> '.$checkOnline.'</td>'; }
									else { echo '<td class="text-center border border-danger text-danger p-1"><i class="fas fa-times"></i> '.$checkOnline.'</td>'; }
									if($data['config_display']){ echo '<td class="text-center border border-success text-success p-1"><i class="fas fa-check"></i> '.$configdisplay.'</td>'; }
									else { echo '<td class="text-center border border-danger text-danger p-1"><i class="fas fa-times"></i> '.$configdisplay.'</td>'; }
								echo '</tr>';
							echo '</tbody>';
						echo '</table>';

						echo '</div>';
					echo '</div>';


					

						
					echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
				
			}
		} else {
			message('warning', 'You have not created any configuration.');
		}
		
	echo '</div>';
echo '</div>';