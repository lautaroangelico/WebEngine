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

echo '<h1 class="page-header">Edit News Translation</h1>';

try {
	
	$News = new News();
	loadModuleConfigs('news');

	// Check if News cache folder is writable
	if(!$News->isNewsDirWritable()) throw new Exception('The news cache folder is not writable.');
		
	// Edit news translation process
	if(isset($_POST['news_submit'])) {
		try {
			$News->setId($_POST['news_id']);
			$News->setLanguage($_POST['news_language']);
			$News->setTitle($_POST['news_title']);
			$News->setContent($_POST['news_content']);
			$News->updateNewsTransation();
			redirect(1, 'admincp/?module=managenews');
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	$News->setId($_GET['id']);
	$News->setLanguage($_GET['language']);
	$newsData = $News->loadNewsTranslationData();
	if(!is_array($newsData)) throw new Exception('Could not load news data.');
	
	echo '<form role="form" method="post">';
		echo '<input type="hidden" name="news_id" value="'.$newsData['news_id'].'" />';
		echo '<input type="hidden" name="news_language" value="'.$newsData['news_language'].'" />';
		echo '<div class="form-group">';
			echo '<label for="input_1">Language:</label>';
			echo '<select class="form-control" name="news_language" id="input_1" disabled="disabled">';
				echo '<option>'.$newsData['news_language'].'</option>';
			echo '</select>';
		echo '</div>';
		echo '<div class="form-group">';
			echo '<label for="input_2">Title:</label>';
			echo '<input type="text" class="form-control" id="input_2" name="news_title" value="'.(isset($_POST['news_title']) ? $_POST['news_title'] : base64_decode($newsData['news_title'])).'" />';
		echo '</div>';
		echo '<div class="form-group">';
			echo '<label for="news_content"></label>';
			echo '<textarea name="news_content" id="news_content">'.(isset($_POST['news_content']) ? $_POST['news_content'] : base64_decode($newsData['news_content'])).'</textarea>';
		echo '</div>';
		echo '<button type="submit" class="btn btn-large btn-block btn-warning" name="news_submit" value="ok">Update News Translation</button>';
	echo '</form>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}
?>

<script>
const useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
const isSmallScreen = window.matchMedia('(max-width: 1023.5px)').matches;
	
document.addEventListener("DOMContentLoaded", function(){
	tinymce.init({
		selector: '#news_content',
		plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion',
		menubar: 'file edit view insert format tools table help',
		toolbar: "undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl",
		promotion: false,
		license_key: 'gpl',
		toolbar_mode: 'sliding',
		contextmenu: 'link image table',
		skin: useDarkMode ? 'oxide-dark' : 'oxide',
		content_css: useDarkMode ? 'dark' : 'default',
	});
});
</script>