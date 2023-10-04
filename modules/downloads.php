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

echo '<div class="page-title"><span>'.lang('module_titles_txt_8',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	$downloadCLIENTS = '';
	$downloadPATCHES = '';
	$downloadTOOLS = '';
	
	$downloadsCACHE = loadCache('downloads.cache');
	if(is_array($downloadsCACHE)) {
		foreach($downloadsCACHE as $tempDownloadsData) {
			switch($tempDownloadsData['download_type']) {
				case 1:
					$downloadCLIENTS[] = $tempDownloadsData;
				break;
				case 2:
					$downloadPATCHES[] = $tempDownloadsData;
				break;
				case 3:
					$downloadTOOLS[] = $tempDownloadsData;
				break;
			}
		}
	}
	
	if(mconfig('show_client_downloads')) {
		if(is_array($downloadCLIENTS)) {
			echo '<div class="panel panel-downloads">';
				echo '<div class="panel-body">';
					echo '<div class="panel-title">'.lang('downloads_txt_6',true).'</div>';
					echo '<table class="table">';
					foreach($downloadCLIENTS as $download) {
						echo '<tr>';
							echo '<td style="width: 60%">'.$download['download_title'].'<br /><span class="download-description">'.$download['download_description'].'</span></td>';
							echo '<td style="width: 20%"class="text-center">'.round($download['download_size'], 2).' '.lang('downloads_txt_4',true).'</td>';
							echo '<td style="width: 20%"class="text-center"><a href="'.$download['download_link'].'" class="btn btn-primary btn-xs" target="_blank">'.lang('downloads_txt_5',true).'</a></td>';
						echo '</tr>';
					}
					echo '</table>';
				echo '</div>';
			echo '</div>';
		}
	}
	
	if(mconfig('show_patch_downloads')) {
		if(is_array($downloadPATCHES)) {
			echo '<div class="panel panel-downloads">';
				echo '<div class="panel-body">';
					echo '<div class="panel-title">'.lang('downloads_txt_7',true).'</div>';
					echo '<table class="table">';
					foreach($downloadPATCHES as $download) {
						echo '<tr>';
							echo '<td style="width: 60%">'.$download['download_title'].'<br /><span class="download-description">'.$download['download_description'].'</span></td>';
							echo '<td style="width: 20%"class="text-center">'.round($download['download_size'], 2).' '.lang('downloads_txt_4',true).'</td>';
							echo '<td style="width: 20%"class="text-center"><a href="'.$download['download_link'].'" class="btn btn-primary btn-xs" target="_blank">'.lang('downloads_txt_5',true).'</a></td>';
						echo '</tr>';
					}
					echo '</table>';
				echo '</div>';
			echo '</div>';
		}
	}
	
	if(mconfig('show_tool_downloads')) {
		if(is_array($downloadTOOLS)) {
			echo '<div class="panel panel-downloads">';
				echo '<div class="panel-body">';
					echo '<div class="panel-title">'.lang('downloads_txt_8',true).'</div>';
					echo '<table class="table">';
					foreach($downloadTOOLS as $download) {
						echo '<tr>';
							echo '<td style="width: 60%">'.$download['download_title'].'<br /><span class="download-description">'.$download['download_description'].'</span></td>';
							echo '<td style="width: 20%"class="text-center">'.round($download['download_size'], 2).' '.lang('downloads_txt_4',true).'</td>';
							echo '<td style="width: 20%"class="text-center"><a href="'.$download['download_link'].'" class="btn btn-primary btn-xs" target="_blank">'.lang('downloads_txt_5',true).'</a></td>';
						echo '</tr>';
					}
					echo '</table>';
				echo '</div>';
			echo '</div>';
		}
	}
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}