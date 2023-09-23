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

if(!isLoggedIn()) redirect(1,'login');

echo '<div class="page-title"><span>'.lang('module_titles_txt_19',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	$Character = new Character();
	$AccountCharacters = $Character->AccountCharacter($_SESSION['username']);
	if(!is_array($AccountCharacters)) throw new Exception(lang('error_46',true));
	
	if(isset($_POST['submit'])) {
		try {
			$Character->setUserid($_SESSION['userid']);
			$Character->setUsername($_SESSION['username']);
			$Character->setCharacter($_POST['character']);
			$Character->CharacterClearSkillTree();
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<table class="table general-table-ui">';
		echo '<tr>';
			echo '<td></td>';
			echo '<td>'.lang('clearst_txt_1',true).'</td>';
			echo '<td>'.lang('clearst_txt_2',true).'</td>';
			echo '<td>'.lang('clearst_txt_5',true).'</td>';
			echo '<td>'.lang('clearst_txt_3',true).'</td>';
			echo '<td></td>';
		echo '</tr>';
		
		foreach($AccountCharacters as $thisCharacter) {
			$characterData = $Character->CharacterData($thisCharacter);
			$characterIMG = $Character->GenerateCharacterClassAvatar($characterData[_CLMN_CHR_CLASS_]);
			$characterMLVLData = $Character->getMasterLevelInfo($thisCharacter);
			
			echo '<form action="" method="post">';
				echo '<input type="hidden" name="character" value="'.$characterData[_CLMN_CHR_NAME_].'"/>';
				echo '<tr>';
					echo '<td>'.$characterIMG.'</td>';
					echo '<td>'.$characterData[_CLMN_CHR_NAME_].'</td>';
					echo '<td>'.number_format($characterMLVLData[_CLMN_ML_LVL_]).'</td>';
					echo '<td>'.number_format($characterMLVLData[_CLMN_ML_POINT_]).'</td>';
					echo '<td>'.number_format($characterData[_CLMN_CHR_ZEN_]).'</td>';
					echo '<td><button name="submit" value="submit" class="btn btn-primary">'.lang('clearst_txt_4',true).'</button></td>';
				echo '</tr>';
			echo '</form>';
		}
	echo '</table>';
	
	echo '<div class="module-requirements text-center">';
		if(mconfig('required_level') > 0) echo '<p>'.langf('clearst_txt_8', array(number_format(mconfig('required_level')))).'</p>';
		if(mconfig('required_master_level') > 0) echo '<p>'.langf('clearst_txt_6', array(number_format(mconfig('required_master_level')))).'</p>';
		if(mconfig('zen_cost') > 0) echo '<p>'.langf('clearst_txt_7', array(number_format(mconfig('zen_cost')))).'</p>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}