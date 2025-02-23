<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.6
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2025 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */
?>
<h1 class="page-header">Edit News</h1>
<?php
$News = new News();
loadModuleConfigs('news');

// Check if News cache folder is writable
if($News->isNewsDirWritable()) {
	
	// Edit news process::
	if(isset($_POST['news_submit'])) {
		$News->editNews($_REQUEST['id'],$_POST['news_title'],$_POST['news_type'],$_POST['news_content'],$_POST['news_author'],0,$_POST['news_date']);
		$News->cacheNews();
		$News->updateNewsCacheIndex();
		redirect(1, 'admincp/?module=managenews');
	}
	
	// Load News
	$editNews = $News->loadNewsData($_REQUEST['id']);
	if($editNews) {
?>
		<form role="form" method="post">
			<div class="form-group">
				<label for="input_1">Title:</label>
				<input type="text" class="form-control" id="input_1" name="news_title" value="<?php echo $editNews['news_title']; ?>"/>
			</div>
			<div class="form-group">
				<label for="newstype">Type:</label>
				<select class="form-control" id="newstype" name="news_type">
					<option value="newsnotice" <?php echo ($editNews['news_type'] == 'newsnotice') ? 'selected="selected"' : ''; ?>>Notice</option>
					<option value="annonotice" <?php echo ($editNews['news_type'] == 'annonotice') ? 'selected="selected"' : ''; ?>>Announcement</option>
					<option value="updatenotice" <?php echo ($editNews['news_type'] == 'updatenotice') ? 'selected="selected"' : ''; ?>>Update</option>
				</select>
			</div>
			<div class="form-group">
				<label for="news_content"></label>
				<textarea name="news_content" id="news_content"><?php echo $editNews['news_content']; ?></textarea>
			</div>
			<div class="form-group">
				<label for="input_2">Author:</label>
				<input type="text" class="form-control" id="input_2" name="news_author" value="<?php echo $editNews['news_author']; ?>"/>
			</div>
			<div class="form-group">
				<label for="input_4">News Date:</label>
				<input type="text" class="form-control" id="input_4" name="news_date" value="<?php echo date("Y-m-d H:i", $editNews['news_date']); ?>"/>
			</div>
			<button type="submit" class="btn btn-large btn-block btn-success" name="news_submit" value="ok">Update News</button>
		</form>
		
		<script src="js/plugins/ckeditor/ckeditor.js"></script>
		<script type="text/javascript">//<![CDATA[
			CKEDITOR.replace('news_content');
		//]]></script>
<?php	
	} else {
		message('error','Could not load news data.');
	}
} else {
	message('error','The news cache folder is not writable.');
}

?>