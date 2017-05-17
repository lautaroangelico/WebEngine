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
?>
<h1 class="page-header">Manage News</h1>
<?php
$News = new News();

if($News->isNewsDirWritable()) {

	# News detele
	if(check_value($_REQUEST['delete'])) {
		$deleteNews = $News->removeNews($_REQUEST['delete']);
		$News->cacheNews();
		$News->updateNewsCacheIndex();
		if($deleteNews) {
			message('success','News successfully deleted');
		} else {
			message('error','Invalid news ID');
		}
	}
	
	# News cache
	if(check_value($_REQUEST['cache']) && $_REQUEST['cache'] == 1) {
		$cacheNews = $News->cacheNews();
		$News->updateNewsCacheIndex();
		if($cacheNews) {
			message('success','News successfully cached');
		} else {
			message('error','Unknown error');
		}
	}
	
	$news_list = $News->retrieveNews();
	if(is_array($news_list)) {
		echo '<table class="table table-hover table-striped">';
			echo '<thead>';
				echo '<tr>';
					echo '<th>#</th>';
					echo '<th>TITLE</th>';
					echo '<th>AUTHOR</th>';
					echo '<th>DATE</th>';
					echo '<th>ALLOW COMMENTS</th>';
					echo '<th></th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			foreach($news_list as $thisNews) {
				$thisNews_allowcomments = ($thisNews['allow_comments'] == 1 ? '<span class="btn btn-success btn-circle"><i class="fa fa-check"></i></span>' : '<span class="btn btn-danger btn-circle"><i class="fa fa-times"></i></span>');
				echo '<tr>';
					echo '<td>'.$thisNews['news_id'].'</td>';
					echo '<td><a href="'.__BASE_URL__.'news/'.Encode_id($thisNews['news_id']).'/" target="_blank">'.$thisNews['news_title'].'</a></td>';
					echo '<td>'.$thisNews['news_author'].'</td>';
					echo '<td>'.date("Y-m-d H:i",$thisNews['news_date']).'</td>';
					echo '<td>'.$thisNews_allowcomments.'</td>';
					echo '<td>';
						echo '<a class="btn btn-default btn-sm" href="'.admincp_base("editnews&id=".$thisNews['news_id']).'"><i class="fa fa-edit"></i> edit</a> ';
						echo '<a class="btn btn-danger btn-sm" href="'.admincp_base("managenews&delete=".$thisNews['news_id']).'"><i class="fa fa-trash"></i> delete</a>';
					echo '</td>';
				echo '</tr>';
			}
			echo '</tbody>';
		echo '</table>';
	}
	
	echo '<a class="btn btn-success" href="'.admincp_base("managenews&cache=1").'">UPDATE NEWS CACHE</a>';

} else {
	message('error','The news cache folder is not writable.');
}

?>