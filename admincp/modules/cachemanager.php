<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.1
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

echo '<h1 class="page-header">Cache Manager</h1>';

try {
	
	$cacheManager = new CacheManager();
	$cacheFileList = $cacheManager->getCacheFileListAndData();
	if(!is_array($cacheFileList)) throw new Exception('No cache files found.');
	
	if(check_value($_GET['action'])) {
		try {
			switch($_GET['action']) {
				case 'clear':
					$cacheManager->setFile($_GET['file']);
					$cacheManager->clearCacheData();
					redirect(3, admincp_base('cachemanager'));
					break;
				case 'deleteguildcache':
					$cacheManager->deleteGuildCache();
					redirect(3, admincp_base('cachemanager'));
					break;
				case 'deleteplayercache':
					$cacheManager->deletePlayerCache();
					redirect(3, admincp_base('cachemanager'));
					break;
				default:
					throw new Exception('The requested action is not valid.');
			}
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<table class="table table-hover">';
		echo '<thead>';
			echo '<tr>';
				echo '<th>Cache File</th>';
				echo '<th>Size</th>';
				echo '<th>Last Modification</th>';
				echo '<th>Writable</th>';
				echo '<th>Actions</th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach($cacheFileList as $row) {
			echo '<tr>';
				echo '<td>'.$row['file'].'</td>';
				echo '<td>'.readableFileSize($row['size'], 2).'</td>';
				echo '<td>'.$row['edit'].'</td>';
				echo '<td>'.($row['write'] == 1 ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">Not Writable</span>').'</td>';
				echo '<td><a href="'.admincp_base('cachemanager&action=clear&file=' . urlencode($row['file'])).'" class="btn btn-xs btn-danger">Clear Data</a></td>';
			echo '</tr>';
		}
		echo '</tbody>';
	echo '</table>';
	
	echo '<div class="row">';
		
		// GUILD PROFILES CACHE
		echo '<div class="col-xs-12 col-md-6 col-lg-6">';
			$guildProfilesCache = $cacheManager->getCacheFileListAndData('guild');
			$guildCacheTotalSize = 0;
			if(is_array($guildProfilesCache)) {
				$guildProfilesCacheCount = count($guildProfilesCache);
				foreach($guildProfilesCache as $guildCache) {
					$guildCacheTotalSize += $guildCache['size'];
				}
			} else {
				$guildProfilesCacheCount = 0;
			}
			echo '<h3>Guild Profiles Cache:</h3>';
			echo '<table class="table table-hover">';
				echo '<tbody>';
					echo '<tr>';
						echo '<th>Cache Files:</th>';
						echo '<td>'.number_format($guildProfilesCacheCount).'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<th>Total Files Size:</th>';
						echo '<td>'.readableFileSize($guildCacheTotalSize, 2).'</td>';
					echo '</tr>';
					if($guildProfilesCacheCount > 0) {
						echo '<tr>';
							echo '<th></th>';
							echo '<td><a href="'.admincp_base('cachemanager&action=deleteguildcache').'" class="btn btn-xs btn-danger">Delete Guild Profiles Cache</a></td>';
						echo '</tr>';
					}
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
		
		// PLAYER PROFILES CACHE
		echo '<div class="col-xs-12 col-md-6 col-lg-6">';
			$playerProfilesCache = $cacheManager->getCacheFileListAndData('player');
			$playerCacheTotalSize = 0;
			if(is_array($playerProfilesCache)) {
				$playerProfilesCacheCount = count($playerProfilesCache);
				foreach($playerProfilesCache as $playerCache) {
					$playerCacheTotalSize += $playerCache['size'];
				}
			} else {
				$playerProfilesCacheCount = 0;
			}
			echo '<h3>Player Profiles Cache:</h3>';
			echo '<table class="table table-hover">';
				echo '<tbody>';
					echo '<tr>';
						echo '<th>Cache Files:</th>';
						echo '<td>'.number_format($playerProfilesCacheCount).'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<th>Total Files Size:</th>';
						echo '<td>'.readableFileSize($playerCacheTotalSize, 2).'</td>';
					echo '</tr>';
					if($playerProfilesCacheCount > 0) {
						echo '<tr>';
							echo '<th></th>';
							echo '<td><a href="'.admincp_base('cachemanager&action=deleteplayercache').'" class="btn btn-xs btn-danger">Delete Player Profiles Cache</a></td>';
						echo '</tr>';
					}
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
		
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}