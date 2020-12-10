<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.2
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

try {
	
	// Module status
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	// News object
	$News = new News();
	$cachedNews = loadCache('news.cache');
	if(!is_array($cachedNews)) throw new Exception(lang('error_61'));
	
	// Set news language
	if(config('language_switch_active',true)) {
		if(check_value($_SESSION['language_display'])) {
			$News->setLanguage($_SESSION['language_display']);
		}
	}
	
	// Single news
	$requestedNewsId = $_GET['subpage'];
	if(check_value($requestedNewsId) && $News->newsIdExists($requestedNewsId)) {
		$showSingleNews = true;
		$newsID = $requestedNewsId;
	}
	
	// News list
	$i = 0;
	foreach($cachedNews as $newsArticle) {
		if($showSingleNews) if($newsArticle['news_id'] != $newsID) continue;
		$News->setId($newsArticle['news_id']);
		
		if($i > mconfig('news_list_limit')) continue;
		
		$news_id = $newsArticle['news_id'];
		$news_title = base64_decode($newsArticle['news_title']);
		$news_author = $newsArticle['news_author'];
		$news_date = $newsArticle['news_date'];
		$news_url = __BASE_URL__.'news/'.$news_id.'/';
		
		// translated news title
		if(config('language_switch_active',true)) {
			if(check_value($_SESSION['language_display']) && is_array($newsArticle['translations'])) {
				if(array_key_exists($_SESSION['language_display'], $newsArticle['translations'])) {
					$news_title = base64_decode($newsArticle['translations'][$_SESSION['language_display']]);
				}
			}
		}
		
		if(mconfig('news_short')) {
			if($showSingleNews) {
				$loadNewsCache = $News->LoadCachedNews();
			} else {
				$loadNewsCache = $News->LoadCachedNews(true);
				$loadNewsCache .= '<a href="'.$news_url.'" class="news-readmore">' . lang('news_txt_3') . '</a>';
			}
		} else {
			$loadNewsCache = $News->LoadCachedNews();
		}
		
		echo '<div class="panel panel-news">';
			echo '<div class="panel-heading">';
				echo '<h3 class="panel-title"><a href="'.$news_url.'">'.$news_title.'</a></h3>';
			echo '</div>';
			if(mconfig('news_expanded') > $i) {
				echo '<div class="panel-body">';
					echo $loadNewsCache;
				echo '</div>';
				echo '<div class="panel-footer">';
					echo '<div class="col-xs-6 nopadding">';
					echo '</div>';
					echo '<div class="col-xs-6 nopadding text-right">';
						echo date("l, F jS Y",$news_date);
					echo '</div>';
				echo '</div>';
			}
		echo '</div>';
		
		$i++;
	}

} catch(Exception $ex) {
	message('warning', $ex->getMessage());
}