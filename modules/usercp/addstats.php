<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.6
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2025 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

if(!isLoggedIn()) redirect(1,'login');

echo '<div class="page-title"><span>'.lang('module_titles_txt_25').'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	if(!is_array($custom['character_cmd'])) throw new Exception(lang('error_59',true));
	$maxStats = mconfig('addstats_max_stats');
	
	$Character = new Character();
	$AccountCharacters = $Character->AccountCharacter($_SESSION['username']);
	if(!is_array($AccountCharacters)) throw new Exception(lang('error_46',true));
	
	if(isset($_POST['submit'])) {
		try {
			$Character->setUserid($_SESSION['userid']);
			$Character->setUsername($_SESSION['username']);
			$Character->setCharacter($_POST['character']);
			if(isset($_POST['add_str']) && $_POST['add_str'] > 0) $Character->setStrength($_POST['add_str']);
			if(isset($_POST['add_agi']) && $_POST['add_agi'] > 0) $Character->setAgility($_POST['add_agi']);
			if(isset($_POST['add_vit']) && $_POST['add_vit'] > 0) $Character->setVitality($_POST['add_vit']);
			if(isset($_POST['add_ene']) && $_POST['add_ene'] > 0) $Character->setEnergy($_POST['add_ene']);
			if(isset($_POST['add_com']) && $_POST['add_com'] > 0) $Character->setCommand($_POST['add_com']);
			
			$Character->CharacterAddStats();
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	foreach($AccountCharacters as $thisCharacter) {
		$characterData = $Character->CharacterData($thisCharacter);
		$characterIMG = $Character->GenerateCharacterClassAvatar($characterData[_CLMN_CHR_CLASS_]);
		
		echo '<div class="card panel-addstats">';
			echo '<div class="card-body d-flex">';
				echo '<div class="col-3 nopadding text-center character-avatar">';
					echo $characterIMG;
				echo '</div>';
				echo '<div class="col-9 nopadding">';
					echo '<div class="col-12 nopadding character-name">';
						echo $characterData[_CLMN_CHR_NAME_];
					echo '</div>';
					echo '<div class="col-sm-10">';
						echo '<form action="" method="post">';
							echo '<input type="hidden" name="character" value="'.$characterData[_CLMN_CHR_NAME_].'"/>';
							
							echo '<div class="row mb-3 align-items-center">';
								echo '<label for="inputStat" class="col-sm-5 col-form-label"></label>';
								echo '<div class="col-sm-7">';
									echo langf('addstats_txt_2', array(number_format($characterData[_CLMN_CHR_LVLUP_POINT_])));
								echo '</div>';
							echo '</div>';
							echo '<div class="row mb-3 align-items-center">';
								echo '<label for="inputStat1" class="col-sm-5 col-form-label">'.lang('addstats_txt_3',true).' ('.$characterData[_CLMN_CHR_STAT_STR_].')</label>';
								echo '<div class="col-sm-7">';
									echo '<input type="number" class="form-control" id="inputStat1" min="1" step="1" max="'.$maxStats.'" name="add_str" placeholder="0">';
								echo '</div>';
							echo '</div>';
							echo '<div class="row mb-3 align-items-center">';
								echo '<label for="inputStat2" class="col-sm-5 col-form-label">'.lang('addstats_txt_4',true).' ('.$characterData[_CLMN_CHR_STAT_AGI_].')</label>';
								echo '<div class="col-sm-7">';
									echo '<input type="number" class="form-control" id="inputStat2" min="1" step="1" max="'.$maxStats.'" name="add_agi" placeholder="0">';
								echo '</div>';
							echo '</div>';
							echo '<div class="row mb-3 align-items-center">';
								echo '<label for="inputStat3" class="col-sm-5 col-form-label">'.lang('addstats_txt_5',true).' ('.$characterData[_CLMN_CHR_STAT_VIT_].')</label>';
								echo '<div class="col-sm-7">';
									echo '<input type="number" class="form-control" id="inputStat3" min="1" step="1" max="'.$maxStats.'" name="add_vit" placeholder="0">';
								echo '</div>';
							echo '</div>';
							echo '<div class="row mb-3 align-items-center">';
								echo '<label for="inputStat4" class="col-sm-5 col-form-label">'.lang('addstats_txt_6',true).' ('.$characterData[_CLMN_CHR_STAT_ENE_].')</label>';
								echo '<div class="col-sm-7">';
									echo '<input type="number" class="form-control" id="inputStat4" min="1" step="1" max="'.$maxStats.'" name="add_ene" placeholder="0">';
								echo '</div>';
							echo '</div>';
							
							if(in_array($characterData[_CLMN_CHR_CLASS_], $custom['character_cmd'])) {
								echo '<div class="row mb-3 align-items-center">';
									echo '<label for="inputStat5" class="col-sm-5 col-form-label">'.lang('addstats_txt_7',true).' ('.$characterData[_CLMN_CHR_STAT_CMD_].')</label>';
									echo '<div class="col-sm-7">';
										echo '<input type="number" class="form-control" id="inputStat5" min="1" step="1" max="'.$maxStats.'" name="add_com" placeholder="0">';
									echo '</div>';
								echo '</div>';
							}
							
							echo '<div class="row mb-3 align-items-center">';
								echo '<div class="col-sm-12 text-end">';
									echo '<button name="submit" value="submit" class="btn btn-primary">'.lang('addstats_txt_8',true).'</button>';
								echo '</div>';
							echo '</div>';
						echo '</form>';
					echo '</div>';
					
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
	
	echo '<div class="module-requirements text-center">';
		if(mconfig('required_level') > 0) echo '<p>'.langf('addstats_txt_11', array(number_format(mconfig('required_level')))).'</p>';
		if(mconfig('required_master_level') > 0) echo '<p>'.langf('addstats_txt_10', array(number_format(mconfig('required_master_level')))).'</p>';
		if(mconfig('zen_cost') > 0) echo '<p>'.langf('addstats_txt_9', array(number_format(mconfig('zen_cost')))).'</p>';
		echo '<p>'.langf('addstats_txt_12', array(number_format(mconfig('max_stats')))).'</p>';
		if(mconfig('minimum_limit') > 0) echo '<p>'.langf('addstats_txt_13', array(number_format(mconfig('minimum_limit')))).'</p>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}