<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2019 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */
?>
<h1 class="page-header">Publish News</h1>
<?php
$News = new News();
loadModuleConfigs('news');

// Check if News cache folder is writable
if($News->isNewsDirWritable()) {
	
	// Add news process::
	if(check_value($_POST['news_submit'])) {
		$News->addNews($_POST['news_title'],$_POST['news_content'],$_POST['news_author'],0);
		$News->cacheNews();
		$News->updateNewsCacheIndex();
		redirect(1, 'admincp/?module=managenews');
	}
	
?>
	<form role="form" method="post">
		<div class="form-group">
			<label for="input_1">Title:</label>
			<input type="text" class="form-control" id="input_1" name="news_title" />
		</div>
		<div class="form-group">
			<label for="news_content"></label>
			<textarea name="news_content" id="news_content"></textarea>
		</div>
		<div class="form-group">
			<label for="input_2">Author:</label>
			<input type="text" class="form-control" id="input_2" name="news_author" value="Administrator"/>
		</div>
		<button type="submit" class="btn btn-large btn-block btn-success" name="news_submit" value="ok">Publish</button>
	</form>

	<script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
	<script type="text/javascript">//<![CDATA[
		CKEDITOR.replace('news_content', {
			language: 'en',
			uiColor: '#f1f1f1'
		});
	//]]></script>
<?php	
} else {
	message('error','The news cache folder is not writable.');
}
?>