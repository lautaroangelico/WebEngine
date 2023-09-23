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

echo '<div class="page-title"><span>'.lang('module_titles_txt_7',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	$vote = new Vote();
	
	if(isset($_POST['submit'])) {
		try {
			$vote->setUserid($_SESSION['userid']);
			$vote->setIp($_SERVER['REMOTE_ADDR']);
			$vote->setVotesiteId($_POST['voting_site_id']);
			$vote->vote();
		} catch (Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<table class="table general-table-ui">';
		echo '<tr>';
			echo '<td>'.lang('vfc_txt_1',true).'</td>';
			echo '<td>'.lang('vfc_txt_2',true).'</td>';
			echo '<td></td>';
		echo '</tr>';

		$vote_sites = $vote->retrieveVotesites();
		if(is_array($vote_sites)) {
			foreach($vote_sites as $thisVotesite) {
				echo '<form action="" method="post">';
					echo '<input type="hidden" name="voting_site_id" value="'.$thisVotesite['votesite_id'].'"/>';
					echo '<tr>';
						echo '<td>'.$thisVotesite['votesite_title'].'</td>';
						echo '<td>'.$thisVotesite['votesite_reward'].'</td>';
						echo '<td><button name="submit" value="submit" class="btn btn-primary">'.lang('vfc_txt_3',true).'</button></td>';
					echo '</tr>';
				echo '</form>';
			}
		}
	echo '</table>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}