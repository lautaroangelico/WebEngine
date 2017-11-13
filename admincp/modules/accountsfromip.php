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
<h1 class="page-header">Find accounts from IP</h1>
<form class="form-inline" role="form" method="post">
	<div class="form-group">
		<input type="text" class="form-control" id="input_1" name="ip_address" placeholder="Ip Address"/>
	</div>
	<button type="submit" class="btn btn-primary" name="search_ip" value="ok">Search</button>
</form>
<br />
<?php
if(check_value($_POST['ip_address'])) {
	try {
		if(!Validator::Ip($_POST['ip_address'])) throw new Exception("You have entered an invalid IP address.");
		
		echo '<h4>Search results for <span style="color:red;font-weight:bold;"><i>'.$_POST['ip_address'].'</i></span>:</h4>';
		echo '<div class="row">';
			echo '<div class="col-md-6">';
				echo '<div class="panel panel-primary">';
				echo '<div class="panel-heading">MEMB_STAT</div>';
				echo '<div class="panel-body">';
					
					$searchdb = (config('SQL_USE_2_DB', true) == true ? $dB2 : $dB);
					$membStatData = $searchdb->query_fetch("SELECT "._CLMN_MS_MEMBID_." FROM "._TBL_MS_." WHERE "._CLMN_MS_IP_." = ? GROUP BY "._CLMN_MS_MEMBID_."", array($_POST['ip_address']));
					if(is_array($membStatData)) {
						echo '<table class="table table-no-border table-hover">';
							foreach($membStatData as $membStatUser) {
								echo '<tr>';
									echo '<td>'.$membStatUser[_CLMN_MS_MEMBID_].'</td>';
									echo '<td style="text-align:right;"><a href="'.admincp_base("accountinfo&id=".$common->retrieveUserID($membStatUser[_CLMN_MS_MEMBID_])).'" class="btn btn-xs btn-default">Account Information</a></td>';
								echo '</tr>';
							}
							echo '</table>';
					} else {
						message('warning', 'No accounts found linked to this Ip.', ' ');
					}
				echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}
?>