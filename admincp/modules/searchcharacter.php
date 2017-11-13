<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.0.9.8
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2017 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */
?>
<h1 class="page-header">Search Character</h1>
<form class="form-inline" role="form" method="post">
	<div class="form-group">
		<input type="text" class="form-control" id="input_1" name="search_request" placeholder="Character name"/>
	</div>
	<button type="submit" class="btn btn-primary" name="search_character" value="ok">Search</button>
</form>
<br />
<?php
	if(check_value($_POST['search_character']) && check_value($_POST['search_request'])) {
		try {
			if(!Validator::AlphaNumeric($_POST['search_request'])) throw new Exception("The name entered must contain alpha-numeric characters only.");
			if(!Validator::Length($_POST['search_request'], 11, 2)) throw new Exception("The name can be 3 to 10 characters long.");
			$searchdb = $dB;
			
			$searchRequest = '%'.$_POST['search_request'].'%';
			$searchResults = $searchdb->query_fetch("SELECT TOP 10 "._CLMN_CHR_NAME_.", "._CLMN_CHR_ACCID_." FROM "._TBL_CHR_." WHERE Name LIKE ?", array($searchRequest));
			if(!$searchResults) throw new Exception("No results found.");
			
			if(is_array($searchResults)) {
				echo '<div class="row">';
				echo '<div class="col-md-6">';
				echo '<table class="table table-striped table-condensed table-hover">';
					echo '<thead>';
						echo '<tr>';
							echo '<th colspan="2">Search Results for <span style="color:red;"><i>'.$_POST['search_request'].'</i></span></th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
				foreach($searchResults as $character) {
					echo '<tr>';
						echo '<td>'.$character[_CLMN_CHR_NAME_].'</td>';
						echo '<td style="text-align:right;">';
							echo '<a href="'.admincp_base("accountinfo&id=".$common->retrieveUserID($character[_CLMN_CHR_ACCID_])).'" class="btn btn-xs btn-default">Account Information</a> ';
							echo '<a href="'.admincp_base("editcharacter&name=".$character[_CLMN_CHR_NAME_]).'" class="btn btn-xs btn-warning">Edit Character</a>';
						echo '</td>';
					echo '</tr>';
				}
					echo '</tbody>';
				echo '</table>';
				echo '</div>';
				echo '<div class="col-md-6"></div>';
				echo '</div>';
			}
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
?>