<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.1.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2019 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

try {
	
	# news module active?
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	$webengineNews = new News();
	$cachedNews = LoadCacheData('news.cache');
	if(!is_array($cachedNews)) throw new Exception('There are no news to display.');
	
	# single news
	$requestedNewsId = $_GET['subpage'];
	if(check_value($requestedNewsId) && $webengineNews->newsIdExists(Decode_id($requestedNewsId))) {
		$showSingleNews = true;
		$newsID = Decode_id($requestedNewsId);
	}
	
	# news list
	$i = 0;
	foreach(array_slice($cachedNews, 1) as $newsArticle) {
		
		if($showSingleNews) if($newsArticle[0] != $newsID) continue;
		
		if($i > mconfig('news_list_limit')) continue;
		
		$news_id = $newsArticle[0];
		$news_title = $newsArticle[1];
		$news_author = $newsArticle[2];
		$news_date = $newsArticle[3];
		$news_comments = $newsArticle[4];
		$news_url = __BASE_URL__.'news/'.Encode_id($news_id).'/';
		$loadNewsCache = $webengineNews->LoadCachedNews($news_id);
		
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
						if(mconfig('news_enable_like_button')) echo '<div class="fb-like" data-href="'.$news_url.'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>';
					echo '</div>';
					echo '<div class="col-xs-6 nopadding text-right">';
						echo 'Published by ' . $news_author . ', ';
						echo date("F j, Y",$news_date);
					echo '</div>';
				echo '</div>';
			}
		echo '</div>';
		
		# facebook comments
		if($showSingleNews && $news_comments && mconfig('news_enable_comment_system')) {
			echo '<div class="fb-comments" data-href="'.$news_url.'" data-width="630" data-numposts="5" data-colorscheme="dark"></div>';
		}
		
		$i++;
	}

} catch(Exception $ex) {
	message('warning', $ex->getMessage());
}