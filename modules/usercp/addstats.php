<?php
/**
 * WebEngine
 * http://muengine.net/
 * 
 * @version 1.0.9.5
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2017 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

if(!isLoggedIn()) redirect(1,'login');

echo '<div class="page-title"><span>'.lang('module_titles_txt_25',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	if(!is_array($custom['character_cmd'])) throw new Exception(lang('error_59',true));
	$maxStats = mconfig('addstats_max_stats');
	
	$Character = new Character();
	$AccountCharacters = $Character->AccountCharacter($_SESSION['username']);
	if(!is_array($AccountCharacters)) throw new Exception(lang('error_46',true));
	
	if(check_value($_POST['submit'])) {
		$Character->CharacterAddStats($_SESSION['username'], $_POST['character'], $_POST['add_str'], $_POST['add_agi'], $_POST['add_vit'], $_POST['add_ene'], $_POST['add_com']);
	}
	
	foreach($AccountCharacters as $thisCharacter) {
		$characterData = $Character->CharacterData($thisCharacter);
		$characterIMG = $Character->GenerateCharacterClassAvatar($characterData[_CLMN_CHR_CLASS_]);
		
		echo '<div class="panel panel-addstats">';
			echo '<div class="panel-body">';
				echo '<div class="col-xs-3 nopadding text-center character-avatar">';
					echo $characterIMG;
				echo '</div>';
				echo '<div class="col-xs-9 nopadding">';
					echo '<div class="col-xs-12 nopadding character-name">';
						echo $characterData[_CLMN_CHR_NAME_];
					echo '</div>';
					echo '<div class="col-sm-10">';
						echo '<form class="form-horizontal" action="" method="post">';
							
							echo '<input type="hidden" name="character" value="'.$characterData[_CLMN_CHR_NAME_].'"/>';
							
							echo '<div class="form-group">';
								echo '<label for="inputStat" class="col-sm-4 control-label"></label>';
								echo '<div class="col-sm-8">';
									echo langf('addstats_txt_2', array(number_format($characterData[_CLMN_CHR_LVLUP_POINT_])));
								echo '</div>';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<label for="inputStat1" class="col-sm-4 control-label">'.lang('addstats_txt_3',true).'</label>';
								echo '<div class="col-sm-8">';
									echo '<input type="number" class="form-control" id="inputStat1" min="1" step="1" max="'.$maxStats.'" name="add_str" placeholder="0">';
								echo '</div>';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<label for="inputStat2" class="col-sm-4 control-label">'.lang('addstats_txt_4',true).'</label>';
								echo '<div class="col-sm-8">';
									echo '<input type="number" class="form-control" id="inputStat2" min="1" step="1" max="'.$maxStats.'" name="add_agi" placeholder="0">';
								echo '</div>';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<label for="inputStat3" class="col-sm-4 control-label">'.lang('addstats_txt_5',true).'</label>';
								echo '<div class="col-sm-8">';
									echo '<input type="number" class="form-control" id="inputStat3" min="1" step="1" max="'.$maxStats.'" name="add_vit" placeholder="0">';
								echo '</div>';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<label for="inputStat4" class="col-sm-4 control-label">'.lang('addstats_txt_6',true).'</label>';
								echo '<div class="col-sm-8">';
									echo '<input type="number" class="form-control" id="inputStat4" min="1" step="1" max="'.$maxStats.'" name="add_ene" placeholder="0">';
								echo '</div>';
							echo '</div>';
							
							if(in_array($characterData[_CLMN_CHR_CLASS_], $custom['character_cmd'])) {
								echo '<div class="form-group">';
									echo '<label for="inputStat5" class="col-sm-4 control-label">'.lang('addstats_txt_7',true).'</label>';
									echo '<div class="col-sm-8">';
										echo '<input type="number" class="form-control" id="inputStat5" min="1" step="1" max="'.$maxStats.'" name="add_com" placeholder="0">';
									echo '</div>';
								echo '</div>';
							}
							
							echo '<div class="form-group">';
								echo '<div class="col-sm-12 text-right">';
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
		if(mconfig('addstats_enable_zen_requirement')) echo '<p>'.langf('addstats_txt_9', array(number_format(mconfig('addstats_price_zen')))).'</p>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}