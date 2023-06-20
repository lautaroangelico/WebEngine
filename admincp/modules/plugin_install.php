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
?>
<h1 class="page-header">Import Plugin</h1>
<?php
if(!config('plugins_system_enable',true)) {
	message('warning', '<strong>WARNING</strong><br />The plugin system is not currently enabled. To enable it please change your <a href="'.admincp_base('website_settings').'">website settings</a>.');
}

if(check_value($_POST['submit'])) {

	if($_FILES["file"]["error"] > 0) {
		message('error', 'There has been an error uploading the file.');
	} else {
		$Plugin = new Plugins();
		$Plugin->importPlugin($_FILES);
	}
}

?>
<div class="card">
<div class="card-body">
<form action="" method="post" enctype="multipart/form-data">

	<div class="input-group mb-3">
		<input type="file" class="form-control" name="file" id="file">
		<label class="input-group-text" for="inputGroupFile02">Upload</label>
	</div>
	<div class="d-grid gap-2">
		<input type="submit" name="submit" class="btn btn-primary span2" value="Install"/>
	</div>
</form>
<p class="text-center mt-3">Make sure you upload all the plugin files before importing it.</p>
</div>
</div>