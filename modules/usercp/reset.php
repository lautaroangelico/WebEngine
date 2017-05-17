<?php
/**
 * WebEngine
 * http://muengine.net/
 * 
 * @version 1.0.9
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2017 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

if(!isLoggedIn()) redirect(1,'login');

echo '<div class="page-title"><span>'.lang('module_titles_txt_12',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	$Character = new Character();
	$AccountCharacters = $Character->AccountCharacter($_SESSION['username']);
	if(!is_array($AccountCharacters)) throw new Exception(lang('error_46',true));
	
	if(check_value($_POST['submit'])) {
		$Character->CharacterReset($_SESSION['username'],$_POST['character'],$_SESSION['userid']);
	}
	
	echo '<table class="table general-table-ui">';
		echo '<tr>';
			echo '<td></td>';
			echo '<td>'.lang('resetcharacter_txt_1',true).'</td>';
			echo '<td>'.lang('resetcharacter_txt_2',true).'</td>';
			echo '<td>'.lang('resetcharacter_txt_3',true).'</td>';
			echo '<td>'.lang('resetcharacter_txt_4',true).'</td>';
			echo '<td></td>';
		echo '</tr>';
		
		foreach($AccountCharacters as $thisCharacter) {
			$characterData = $Character->CharacterData($thisCharacter);
			$characterIMG = $Character->GenerateCharacterClassAvatar($characterData[_CLMN_CHR_CLASS_]);
			
			echo '<form action="" method="post">';
				echo '<input type="hidden" name="character" value="'.$characterData[_CLMN_CHR_NAME_].'"/>';
				echo '<tr>';
					echo '<td>'.$characterIMG.'</td>';
					echo '<td>'.$characterData[_CLMN_CHR_NAME_].'</td>';
					echo '<td>'.$characterData[_CLMN_CHR_LVL_].'</td>';
					echo '<td>'.number_format($characterData[_CLMN_CHR_ZEN_]).'</td>';
					echo '<td>'.number_format($characterData[_CLMN_CHR_RSTS_]).'</td>';
					echo '<td><button name="submit" value="submit" class="btn btn-primary">'.lang('resetcharacter_txt_5',true).'</button></td>';
				echo '</tr>';
			echo '</form>';
		}
	echo '</table>';
	
	echo '<div class="module-requirements text-center">';
		echo '<p>'.langf('resetcharacter_txt_6', array(mconfig('resets_required_level'))).'</p>';
		if(mconfig('resets_enable_zen_requirement')) echo '<p>'.langf('resetcharacter_txt_7', array(number_format(mconfig('resets_price_zen')))).'</p>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}