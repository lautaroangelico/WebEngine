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

if(isLoggedIn()) redirect();
echo '<h1 class="page-title">Registro</h1>';
try {
	
	if(!mconfig('active')) throw new Exception(lang('error_17',true));
	
	// Register Process
	if(check_value($_POST['webengineRegister_submit'])) {
		try {
			$Account = new Account();
			
			if(mconfig('register_enable_recaptcha')) {
				if(!@include_once(__PATH_CLASSES__ . 'recaptcha/autoload.php')) throw new Exception(lang('error_60'));
				$recaptcha = new \ReCaptcha\ReCaptcha(mconfig('register_recaptcha_secret_key'));
				
				$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
				if(!$resp->isSuccess()) {
					# recaptcha failed
					$errors = $resp->getErrorCodes();
					throw new Exception(lang('error_18',true));
				}
			}
			
			$Account->registerAccount($_POST['webengineRegister_user'], $_POST['webengineRegister_pwd'], $_POST['webengineRegister_pwdc'], $_POST['webengineRegister_email']);
			
		} catch (Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<div class="col-12 col-sm-6 col-md-6 mx-auto" style="margin-top:30px;">';
		echo '<form class="form-horizontal needs-validation" action="" method="post" novalidate>';

		// Usuario
		echo '<div class="form-row">';
		echo '	<div class="col-md-12 mb-12">';
		echo '	<div class="input-group">';
		echo '		<div class="input-group-prepend">';
		echo '		<span class="input-group-text" id="inputGroupPrepend"><i class="fa fa-user"></i></span>';
		echo '		</div>';
		echo '		<input type="text" class="form-control" id="webengineRegistration1" placeholder="'.lang('register_txt_1',true).'" name="webengineRegister_user" required>';
		echo '		<div class="invalid-feedback">';
		echo '		Por favor elige un Usuario.';
		echo '		</div>';
		echo '	</div>';
		echo '	<small>'.lang('register_txt_6',true).'</small>';
		echo '	</div>';
		echo '</div>';

		echo '<br>';

		// Email
		echo '<div class="form-row">';
		echo '	<div class="col-md-12 mb-12">';
		echo '	<div class="input-group">';
		echo '		<div class="input-group-prepend">';
		echo '		<span class="input-group-text" id="inputGroupPrepend"><i class="fa fa-envelope"></i></span>';
		echo '		</div>';
		echo '		<input type="text" class="form-control" id="webengineRegistration4" placeholder="'.lang('register_txt_4',true).'" name="webengineRegister_email" required>';
		echo '		<div class="invalid-feedback">';
		echo '		Por favor elige un Correo.';
		echo '		</div>';
		echo '	</div>';
		echo '	<small>'.lang('register_txt_9',true).'</small>';
		echo '	</div>';
		echo '</div>';

		echo '<br>';
		echo '<div class="row">
				<div class="col"><hr></div>
				<div class="col-auto">Seguridad</div>
				<div class="col"><hr></div>
			</div>';
		echo '<br>';

		// Contraseña
		echo '<div class="form-row">';
		echo '	<div class="col-md-12 mb-12">';
		echo '	<div class="input-group">';
		echo '		<div class="input-group-prepend">';
		echo '		<span class="input-group-text" id="inputGroupPrepend"><i class="fa fa-lock"></i></span>';
		echo '		</div>';
		echo '		<input type="password" class="form-control" id="webengineRegistration2" placeholder="'.lang('register_txt_2',true).'" name="webengineRegister_pwd" required>';
		echo '		<div class="invalid-feedback">';
		echo '		Por favor elige una Contraseña.';
		echo '		</div>';
		echo '	</div>';
		echo '	<small>'.lang('register_txt_7',true).'</small>';
		echo '	</div>';
		echo '</div>';

		echo '<br>';
		
		// Contraseña Confirm
		echo '<div class="form-row">';
		echo '	<div class="col-md-12 mb-12">';
		echo '	<div class="input-group">';
		echo '		<div class="input-group-prepend">';
		echo '		<span class="input-group-text" id="inputGroupPrepend"><i class="fa fa-lock"></i></span>';
		echo '		</div>';
		echo '		<input type="password" class="form-control" id="webengineRegistration3" placeholder="'.lang('register_txt_3',true).'" name="webengineRegister_pwdc" required>';
		echo '		<div class="invalid-feedback">';
		echo '		Confirme su Contraseña.';
		echo '		</div>';
		echo '	</div>';
		echo '	<small>'.lang('register_txt_8',true).'</small>';
		echo '	</div>';
		echo '</div>';

		
			if(mconfig('register_enable_recaptcha')) {

				echo '<br>';
				echo '<div class="row">
						<div class="col"><hr></div>
						<div class="col-auto">Verificacion</div>
						<div class="col"><hr></div>
					</div>';
				echo '<br>';

				// Verificacion
				echo '<div class="form-row">';
				echo '	<div class="col-md-12 mb-12">';
				echo '	<div class="input-group">';
				echo '		<div class="col-sm-offset-4 col-sm-8">';
				echo '			<div class="g-recaptcha" data-sitekey="'.mconfig('register_recaptcha_site_key').'"></div>';
				echo '		</div>';
				echo '	</div>';
				echo '	<script src=\'https://www.google.com/recaptcha/api.js\'></script>';
				echo '	</div>';
				echo '</div>';
			}
			
			echo '<br>';
			echo '<div class="row">
					<div class="col"><hr></div>
					<div class="col-auto">Reglamento</div>
					<div class="col"><hr></div>
				</div>';
			echo '<br>';

			echo '<div class="form-group">';
			echo '	<div class="form-check">';
			echo '	<input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>';
			echo '	<label class="form-check-label" for="invalidCheck">';
			echo 	langf('register_txt_10', array(__BASE_URL__.'tos'));
			echo '	</label>';
			echo '	<div class="invalid-feedback">';
			echo '		Debes aceptar los Terminos para poder registrarte.';
			echo '	</div>';
			echo '	</div>';
			echo '</div>';

			echo '<div class="form-group">';
				echo '<div class="col-auto">';
					echo '<button type="submit" name="webengineRegister_submit" value="submit" class="btn btn-primary">'.lang('register_txt_5',true).'</button>';
				echo '</div>';
			echo '</div>';
		echo '</form>';
	echo '</div>';

	?><script>
	// Example starter JavaScript for disabling form submissions if there are invalid fields
	(function() {
	  'use strict';
	  window.addEventListener('load', function() {
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.getElementsByClassName('needs-validation');
		// Loop over them and prevent submission
		var validation = Array.prototype.filter.call(forms, function(form) {
		  form.addEventListener('submit', function(event) {
			if (form.checkValidity() === false) {
			  event.preventDefault();
			  event.stopPropagation();
			}
			form.classList.add('was-validated');
		  }, false);
		});
	  }, false);
	})();
	</script><?php

} catch(Exception $ex) {
	message('error', $ex->getMessage());
}