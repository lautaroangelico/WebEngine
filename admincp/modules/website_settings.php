<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.5
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2023 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

echo '<h1 class="page-header">Configuracion de Web</h1>';

$allowedSettings = array(
	'settings_submit', # the submit button
	'system_active',
	'error_reporting',
	'website_template',
	'maintenance_page',
	'server_name',
	'website_title',
	'website_meta_description',
	'website_meta_keywords',
	'website_forum_link',
	'server_files',
	'language_switch_active',
	'language_default',
	'language_debug',
	'plugins_system_enable',
	'ip_block_system_enable',
	'player_profiles',
	'guild_profiles',
	'username_min_len',
	'username_max_len',
	'password_min_len',
	'password_max_len',
	'cron_api',
	'cron_api_key',
	'social_link_facebook',
	'social_link_instagram',
	'social_link_discord',
	'server_info_season',
	'server_info_exp',
	'server_info_masterexp',
	'server_info_drop',
	'maximum_online',
	'color_template',
	'ip_game_server',
	'port_game_server',
);

if(check_value($_POST['settings_submit'])) {
	try {
		
		# website status
		if(!check_value($_POST['system_active'])) throw new Exception('Invalid Website Status setting.');
		if(!in_array($_POST['system_active'], array(0, 1))) throw new Exception('Invalid Website Status setting.');
		$setting['system_active'] = ($_POST['system_active'] == 1 ? true : false);
		
		# error reporting
		if(!check_value($_POST['error_reporting'])) throw new Exception('Invalid Error Reporting setting.');
		if(!in_array($_POST['error_reporting'], array(0, 1))) throw new Exception('Invalid Error Reporting setting.');
		$setting['error_reporting'] = ($_POST['error_reporting'] == 1 ? true : false);
		
		# default template
		if(!check_value($_POST['website_template'])) throw new Exception('Invalid Default Template setting.');
		if(!file_exists(__PATH_TEMPLATES__.$_POST['website_template'].'/index.php')) throw new Exception('The selected template doesn\'t exist.');
		$setting['website_template'] = $_POST['website_template'];
		
		# maintenance page
		if(!check_value($_POST['maintenance_page'])) throw new Exception('Invalid Maintenance Page setting.');
		if(!Validator::Url($_POST['maintenance_page'])) throw new Exception('The maintenance page setting is not a valid URL.');
		$setting['maintenance_page'] = $_POST['maintenance_page'];
		
		# server name
		if(!check_value($_POST['server_name'])) throw new Exception('Invalid Server Name setting.');
		$setting['server_name'] = $_POST['server_name'];
		
		# website title
		if(!check_value($_POST['website_title'])) throw new Exception('Invalid Website Title setting.');
		$setting['website_title'] = $_POST['website_title'];
		
		# meta description
		if(!check_value($_POST['website_meta_description'])) throw new Exception('Invalid Meta Description setting.');
		$setting['website_meta_description'] = $_POST['website_meta_description'];
		
		# meta keywords
		if(!check_value($_POST['website_meta_keywords'])) throw new Exception('Invalid Meta Keywords setting.');
		$setting['website_meta_keywords'] = $_POST['website_meta_keywords'];
		
		# forum link
		if(!check_value($_POST['website_forum_link'])) throw new Exception('Invalid Forum Link setting.');
		if(!Validator::Url($_POST['website_forum_link'])) throw new Exception('The forum link setting is not a valid URL.');
		$setting['website_forum_link'] = $_POST['website_forum_link'];
		
		# server files
		if(!check_value($_POST['server_files'])) throw new Exception('Invalid Server Files setting.');
		if(!array_key_exists($_POST['server_files'], $webengine['file_compatibility'])) throw new Exception('Invalid Server Files setting.');
		$setting['server_files'] = $_POST['server_files'];
		
		# language switch
		if(!check_value($_POST['language_switch_active'])) throw new Exception('Invalid Language Switch setting.');
		if(!in_array($_POST['language_switch_active'], array(0, 1))) throw new Exception('Invalid Language Switch setting.');
		$setting['language_switch_active'] = ($_POST['language_switch_active'] == 1 ? true : false);
		
		# language default
		if(!check_value($_POST['language_default'])) throw new Exception('Invalid Default Language setting.');
		if(!file_exists(__PATH_LANGUAGES__.$_POST['language_default'].'/language.php')) throw new Exception('The default language doesn\'t exist.');
		$setting['language_default'] = $_POST['language_default'];
		
		# language debug
		if(!check_value($_POST['language_debug'])) throw new Exception('Invalid Language Debug setting.');
		if(!in_array($_POST['language_debug'], array(0, 1))) throw new Exception('Invalid Language Debug setting.');
		$setting['language_debug'] = ($_POST['language_debug'] == 1 ? true : false);
		
		# plugin system
		if(!check_value($_POST['plugins_system_enable'])) throw new Exception('Invalid Plugin System setting.');
		if(!in_array($_POST['plugins_system_enable'], array(0, 1))) throw new Exception('Invalid Plugin System setting.');
		$setting['plugins_system_enable'] = ($_POST['plugins_system_enable'] == 1 ? true : false);
		
		# ip block system
		if(!check_value($_POST['ip_block_system_enable'])) throw new Exception('Invalid IP Block System setting.');
		if(!in_array($_POST['ip_block_system_enable'], array(0, 1))) throw new Exception('Invalid IP Block System setting.');
		$setting['ip_block_system_enable'] = ($_POST['ip_block_system_enable'] == 1 ? true : false);
		
		# player_profiles
		if(!check_value($_POST['player_profiles'])) throw new Exception('Invalid setting (player_profiles)');
		if(!in_array($_POST['player_profiles'], array(0, 1))) throw new Exception('Invalid setting (player_profiles)');
		$setting['player_profiles'] = ($_POST['player_profiles'] == 1 ? true : false);
		
		# guild_profiles
		if(!check_value($_POST['guild_profiles'])) throw new Exception('Invalid setting (guild_profiles)');
		if(!in_array($_POST['guild_profiles'], array(0, 1))) throw new Exception('Invalid setting (guild_profiles)');
		$setting['guild_profiles'] = ($_POST['guild_profiles'] == 1 ? true : false);
		
		# username_min_len
		if(!check_value($_POST['username_min_len'])) throw new Exception('Invalid setting (username_min_len)');
		if(!Validator::UnsignedNumber($_POST['username_min_len'])) throw new Exception('Invalid setting (username_min_len)');
		$setting['username_min_len'] = $_POST['username_min_len'];
		
		# username_max_len
		if(!check_value($_POST['username_max_len'])) throw new Exception('Invalid setting (username_max_len)');
		if(!Validator::UnsignedNumber($_POST['username_max_len'])) throw new Exception('Invalid setting (username_max_len)');
		$setting['username_max_len'] = $_POST['username_max_len'];
		
		# password_min_len
		if(!check_value($_POST['password_min_len'])) throw new Exception('Invalid setting (password_min_len)');
		if(!Validator::UnsignedNumber($_POST['password_min_len'])) throw new Exception('Invalid setting (password_min_len)');
		$setting['password_min_len'] = $_POST['password_min_len'];
		
		# password_max_len
		if(!check_value($_POST['password_max_len'])) throw new Exception('Invalid setting (password_max_len)');
		if(!Validator::UnsignedNumber($_POST['password_max_len'])) throw new Exception('Invalid setting (password_max_len)');
		$setting['password_max_len'] = $_POST['password_max_len'];
		
		# cron_api
		if(!check_value($_POST['cron_api'])) throw new Exception('Invalid setting (cron_api)');
		if(!in_array($_POST['cron_api'], array(0, 1))) throw new Exception('Invalid setting (cron_api)');
		$setting['cron_api'] = ($_POST['cron_api'] == 1 ? true : false);
		
		# cron_api_key
		if(!check_value($_POST['cron_api_key'])) throw new Exception('Invalid setting (cron_api_key)');
		$setting['cron_api_key'] = $_POST['cron_api_key'];
		
		# social link facebook
		if(check_value($_POST['social_link_facebook'])) if(!Validator::Url($_POST['social_link_facebook'])) throw new Exception('The facebook link setting is not a valid URL.');
		$setting['social_link_facebook'] = $_POST['social_link_facebook'];
		
		# social link instagram
		if(check_value($_POST['social_link_instagram'])) if(!Validator::Url($_POST['social_link_instagram'])) throw new Exception('The instagram link setting is not a valid URL.');
		$setting['social_link_instagram'] = $_POST['social_link_instagram'];
		
		# social link discord
		if(check_value($_POST['social_link_discord'])) if(!Validator::Url($_POST['social_link_discord'])) throw new Exception('The discord link setting is not a valid URL.');
		$setting['social_link_discord'] = $_POST['social_link_discord'];
		
		# server info season
		$setting['server_info_season'] = $_POST['server_info_season'];
		
		# server info exp
		$setting['server_info_exp'] = $_POST['server_info_exp'];
		
		# server info master exp
		$setting['server_info_masterexp'] = $_POST['server_info_masterexp'];
		
		# server info drop
		$setting['server_info_drop'] = $_POST['server_info_drop'];
		
		# maximum online
		if(check_value($_POST['maximum_online'])) if(!Validator::UnsignedNumber($_POST['maximum_online'])) throw new Exception('Invalid setting (maximum_online)');
		$setting['maximum_online'] = $_POST['maximum_online'];

		# color template
		$setting['color_template'] = $_POST['color_template'];

		# ip game server
		$setting['ip_game_server'] = $_POST['ip_game_server'];

		# port game server
		$setting['port_game_server'] = $_POST['port_game_server'];

	
		# webengine configs
		$webengineConfigurations = webengineConfigs();
		
		# make sure the settings are in the allow list
		foreach(array_keys($setting) as $settingName) {
			if(!in_array($settingName, $allowedSettings)) throw new Exception('One or more submitted setting is not editable.');
			
			$webengineConfigurations[$settingName] = $setting[$settingName];
		}
		
		$newWebEngineConfig = json_encode($webengineConfigurations, JSON_PRETTY_PRINT);
		$cfgFile = fopen(__PATH_CONFIGS__.'webengine.json', 'w');
		if(!$cfgFile) throw new Exception('There was a problem opening the configuration file.');
		
		fwrite($cfgFile, $newWebEngineConfig);
		fclose($cfgFile);
		
		message('success', 'Settings successfully saved!');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="col-md-12">';
echo '<div class="card">';
			echo '<div class="card-body">';
	echo '<form action="" method="post">';
		echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
			
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Estado de Web</strong>';
					echo '<p class="setting-description">Activa/Desactiva tu web. Si esta desactivada, los visitantes seran redirigidos a la <b>pagina de mantenimiento</b>.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<input type="radio" class="btn-check" name="system_active" value="1" '.(config('system_active',true) ? 'checked' : null).' id="Op1StatusWeb">';
						echo '<label class="btn btn-outline-success" for="Op1StatusWeb"> <i class="fas fa-check"></i> Activado </label>';
						echo '&nbsp;';
						echo '<input type="radio" class="btn-check" name="system_active" value="0" '.(!config('system_active',true) ? 'checked' : null).' id="Op2StatusWeb">';
						echo '<label class="btn btn-outline-danger" for="Op2StatusWeb"> <i class="fas fa-times"></i> Desactivado </label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Modo Debug</strong>';
					echo '<p class="setting-description">El Modo Debug, solo activalo si quieres ver todos los errores en la web.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<input type="radio" class="btn-check" name="error_reporting" value="1" '.(config('error_reporting',true) ? 'checked' : null).' id="Op1ModeDebug">';
						echo '<label class="btn btn-outline-success" for="Op1ModeDebug"> <i class="fas fa-check"></i> Activado </label>';
						echo '&nbsp;';
						echo '<input type="radio" class="btn-check" name="error_reporting" value="0" '.(!config('error_reporting',true) ? 'checked' : null).' id="Op2ModeDebug">';
						echo '<label class="btn btn-outline-danger" for="Op2ModeDebug"> <i class="fas fa-times"></i> Desactivado </label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Template Pretederminado</strong>';
					echo '<p class="setting-description">El template predeterminado de tu web.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="website_template" value="'.config('website_template',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>URL Pagina de Mantenimiento</strong>';
					echo '<p class="setting-description">Cuando tu Web este desactivada. Los visitantes seran redirigidos a esta URL que configures.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="maintenance_page" value="'.config('maintenance_page',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Nombre de Servidor</strong>';
					echo '<p class="setting-description">El nombre de tu Servidor.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="server_name" value="'.config('server_name',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Titulo de la Web</strong>';
					echo '<p class="setting-description">El titulo de tu web.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="website_title" value="'.config('website_title',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Meta Descripcion</strong>';
					echo '<p class="setting-description">Define la descripcion de tu servidor.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="website_meta_description" value="'.config('website_meta_description',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Meta Keywords</strong>';
					echo '<p class="setting-description">Define las palabras con la cual buscaran tu web.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="website_meta_keywords" value="'.config('website_meta_keywords',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Enlace de Foro</strong>';
					echo '<p class="setting-description">URL Completa de tu foro.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="website_forum_link" value="'.config('website_forum_link',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Files del Servidor</strong>';
					echo '<p class="setting-description">Selecciona los Files que usa tu servidor.</p>';
				echo '</td>';
				echo '<td>';
					
					echo '<select class="form-select" name="server_files">';
						$fileCompatibilityList = $webengine['file_compatibility'];
						if(is_array($fileCompatibilityList)) {
							foreach($fileCompatibilityList as $value => $fileCompatibilityInfo) {
								echo '<option value="'.$value.'" '.(strtolower(config('server_files',true)) == $value ? 'selected' : '').'>'.$fileCompatibilityInfo['name'].'</option>';
							}
						}
					echo '</select>';
					
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Cambio de Lenguaje</strong>';
					echo '<p class="setting-description">Activa/Desactiva el sistema para cambiar el lenguaje a tu web.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<input type="radio" class="btn-check" name="language_switch_active" value="1" '.(config('language_switch_active',true) ? 'checked' : null).' id="Op1Lenguaje">';
						echo '<label class="btn btn-outline-success" for="Op1Lenguaje"> <i class="fas fa-check"></i> Activado </label>';
						echo '&nbsp;';
						echo '<input type="radio" class="btn-check" name="language_switch_active" value="0" '.(!config('language_switch_active',true) ? 'checked' : null).' id="Op2Lenguaje">';
						echo '<label class="btn btn-outline-danger" for="Op2Lenguaje"> <i class="fas fa-times"></i> Desactivado </label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Lenguaje Predeterminado</strong>';
					echo '<p class="setting-description">El lenguaje predeterminado que usara Webengine.</p>';
				echo '</td>';
				echo '<td>';
					echo '<select class="form-select" name="language_default">';
						if(config('language_default',true) == 'es') $Lenguaje = 'Español';
						if(config('language_default',true) == 'en') $Lenguaje = 'English';
						if(config('language_default',true) == 'pt') $Lenguaje = 'Portugues';
						if(config('language_default',true) == 'ph') $Lenguaje = 'Filipino';
						if(config('language_default',true) == 'lt') $Lenguaje = 'Lithuanian';;
						if(config('language_default',true) == 'cn') $Lenguaje = 'Chinese';
						if(config('language_default',true) == 'ro') $Lenguaje = 'Romanian';
						if(config('language_default',true) == 'ru') $Lenguaje = 'Russian'; 

						$langList = array(
							'en' => array('English', 'US'),
							'es' => array('Español', 'ES'),
							'ph' => array('Filipino', 'PH'),
							'br' => array('Português', 'BR'),
							'ro' => array('Romanian', 'RO'),
							'cn' => array('Simplified Chinese', 'CN'),
							'ru' => array('Russian', 'RU'),
							'lt' => array('Lithuanian', 'LT'),
						);
					
						echo '<option value="'.config('language_default',true).'" selected>'.$Lenguaje.'</option>';
						echo '<option class="bg-dark border-1 border-top border-dark mt-1 mb-1" style="font-size:1px;" disabled></option>';
						
						$lang = config('language_default', true);
						foreach($langList as $language => $languageInfo) {
							if($language == $lang) continue;
							echo '<option value="'.strtolower($language).'">'.$languageInfo[0].'</option>';
						}
					echo '</select>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Lenguaje Debug</strong>';
					echo '<p class="setting-description">Activa/Desactiva el sistema debug del lenguaje. Si esta activado, al pasar el mouse por un texto, te dira el nombre de la frase. Mantenerlo desactivado recomendado.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<input type="radio" class="btn-check" name="language_debug" value="1" '.(config('language_debug',true) ? 'checked' : null).' id="Op1LenguajeDeb">';
						echo '<label class="btn btn-outline-success" for="Op1LenguajeDeb"> <i class="fas fa-check"></i> Activado </label>';
						echo '&nbsp;';
						echo '<input type="radio" class="btn-check" name="language_debug" value="0" '.(!config('language_debug',true) ? 'checked' : null).' id="Op2LenguajeDeb">';
						echo '<label class="btn btn-outline-danger" for="Op2LenguajeDeb"> <i class="fas fa-times"></i> Desactivado </label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Estado de Sistema de Plugins</strong>';
					echo '<p class="setting-description">Activar/Desactivar el sistema de plugins.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<input type="radio" class="btn-check" name="plugins_system_enable" value="1" '.(config('plugins_system_enable',true) ? 'checked' : null).' id="Op1Plugin">';
						echo '<label class="btn btn-outline-success" for="Op1Plugin"> <i class="fas fa-check"></i> Activado </label>';
						echo '&nbsp;';
						echo '<input type="radio" class="btn-check" name="plugins_system_enable" value="0" '.(!config('plugins_system_enable',true) ? 'checked' : null).' id="Op2Plugin">';
						echo '<label class="btn btn-outline-danger" for="Op2Plugin"> <i class="fas fa-times"></i> Desactivado </label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Estado de Sistema de Ban de IP</strong>';
					echo '<p class="setting-description">Activa/Desactiva el sistema de Ban de IP.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<input type="radio" class="btn-check" name="ip_block_system_enable" value="1" '.(config('ip_block_system_enable',true) ? 'checked' : null).' id="Op1BanIP">';
						echo '<label class="btn btn-outline-success" for="Op1BanIP"> <i class="fas fa-check"></i> Activado </label>';
						echo '&nbsp;';
						echo '<input type="radio" class="btn-check" name="ip_block_system_enable" value="0" '.(!config('ip_block_system_enable',true) ? 'checked' : null).' id="Op2BanIP">';
						echo '<label class="btn btn-outline-danger" for="Op2BanIP"> <i class="fas fa-times"></i> Desactivado </label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Enlace de Perfil de Usuario</strong>';
					echo '<p class="setting-description">Si esta activo, los jugadores tendran un link en su nombre al cual los llevara a su perfil.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<input type="radio" class="btn-check" name="player_profiles" value="1" '.(config('player_profiles',true) ? 'checked' : null).' id="Op1PerfilJugador">';
						echo '<label class="btn btn-outline-success" for="Op1PerfilJugador"> <i class="fas fa-check"></i> Activado </label>';
						echo '&nbsp;';
						echo '<input type="radio" class="btn-check" name="player_profiles" value="0" '.(!config('player_profiles',true) ? 'checked' : null).' id="Op2PerfilJugador">';
						echo '<label class="btn btn-outline-danger" for="Op2PerfilJugador"> <i class="fas fa-times"></i> Desactivado </label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Enlace de Perfil de Clanes</strong>';
					echo '<p class="setting-description">Si esta activo, los clanes tendran un link en su nombre al cual los llevara a su perfil.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<input type="radio" class="btn-check" name="guild_profiles" value="1" '.(config('guild_profiles',true) ? 'checked' : null).' id="Op1PerfilClase">';
						echo '<label class="btn btn-outline-success" for="Op1PerfilClase"> <i class="fas fa-check"></i> Activado </label>';
						echo '&nbsp;';
						echo '<input type="radio" class="btn-check" name="guild_profiles" value="0" '.(!config('guild_profiles',true) ? 'checked' : null).' id="Op2PerfilClase">';
						echo '<label class="btn btn-outline-danger" for="Op2PerfilClase"> <i class="fas fa-times"></i> Desactivado </label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Minimos Caracteres en Usuario</strong>';
					echo '<p class="setting-description"></p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="username_min_len" value="'.config('username_min_len',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Maximos Caracteres en Usuario</strong>';
					echo '<p class="setting-description"></p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="username_max_len" value="'.config('username_max_len',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Minimos Caracteres en Password</strong>';
					echo '<p class="setting-description"></p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="password_min_len" value="'.config('password_min_len',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Maximos Caracteres en Password</strong>';
					echo '<p class="setting-description"></p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="password_max_len" value="'.config('password_max_len',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Cron API</strong>';
					echo '<p class="setting-description">Activa/Desactiva el cron api.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<input type="radio" class="btn-check" name="cron_api" value="1" '.(config('cron_api',true) ? 'checked' : null).' id="Op1CronApi">';
						echo '<label class="btn btn-outline-success" for="Op1CronApi"> <i class="fas fa-check"></i> Activado </label>';
						echo '&nbsp;';
						echo '<input type="radio" class="btn-check" name="cron_api" value="0" '.(!config('cron_api',true) ? 'checked' : null).' id="Op2CronApi">';
						echo '<label class="btn btn-outline-danger" for="Op2CronApi"> <i class="fas fa-times"></i> Desactivado </label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Cron API Codigo</strong>';
					echo '<p class="setting-description"><br />Usarlo:<br />'.__BASE_URL__.'api/cron.php?key=<span style="color:red;">'.config('cron_api_key',true).'</span></p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="cron_api_key" value="'.config('cron_api_key',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Enlace Facebook</strong>';
					echo '<p class="setting-description">Enlace de tu Pagina de Facebook.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="social_link_facebook" value="'.config('social_link_facebook',true).'">';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Enlace Instagram</strong>';
					echo '<p class="setting-description">Enlace de tu Pagina de Instagram.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="social_link_instagram" value="'.config('social_link_instagram',true).'">';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Enlace Discord</strong>';
					echo '<p class="setting-description">Enlace de tu invitacion de discord..</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="social_link_discord" value="'.config('social_link_discord',true).'">';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Info del Servidor: Season</strong>';
					echo '<p class="setting-description">Dejalo en blanco si no quieres que aparezca nada.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="server_info_season" value="'.config('server_info_season',true).'">';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Info del Servidor: Experiencia</strong>';
					echo '<p class="setting-description">Dejalo en blanco si no quieres que aparezca nada.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="server_info_exp" value="'.config('server_info_exp',true).'">';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Info del Servidor: Master Experiencia</strong>';
					echo '<p class="setting-description">Dejalo en blanco si no quieres que aparezca nada.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="server_info_masterexp" value="'.config('server_info_masterexp',true).'">';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Info del Servidor: Drop</strong>';
					echo '<p class="setting-description">Dejalo en blanco si no quieres que aparezca nada.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="server_info_drop" value="'.config('server_info_drop',true).'">';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Maximo de Jugadores Conectados</strong>';
					echo '<p class="setting-description">Maximo de jugadores conectados en su servidor. Dejalo en blanco si no quieres que aparezca nada.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="maximum_online" value="'.config('maximum_online',true).'">';
				echo '</td>';
			echo '</tr>';

			echo '<tr>';
				echo '<td>';
					echo '<strong>Color Template</strong>';
					echo '<p class="setting-description">El color padre que tendra el template por defecto es <b><font color=#a70f00>Rojo</font> ( #a70f00 )</b></p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="color_template" value="'.config('color_template',true).'">';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>';
					echo '<strong>IP Game Server</strong>';
					echo '<p class="setting-description">Colocar la IP de tu Servidor</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="ip_game_server" value="'.config('ip_game_server',true).'">';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>';
					echo '<strong>PORT Game Server</strong>';
					echo '<p class="setting-description">Colocar el PORT de tu Game Server (Por defecto es 55901)</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="port_game_server" value="'.config('port_game_server',true).'">';
				echo '</td>';
			echo '</tr>';
			
		echo '</table>';
		
		echo '<button type="submit" name="settings_submit" value="ok" class="btn btn-info">Guardar Configuracion</button>';
	echo '</form>';
	echo '</div>';echo '</div>';echo '</div>';