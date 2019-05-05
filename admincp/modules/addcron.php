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
<h1 class="page-header">New Cron Job</h1>
<?php

$cron_times = commonCronTimes();

if(check_value($_POST['add_cron'])) {
	addCron();
}

echo '<div class="row">';
	echo '<div class="col-md-4">';
		echo '<form method="post">';
			echo '<div class="form-group">';
				echo '<label for="input_1">Name:</label>';
				echo '<input type="text" class="form-control" id="input_1" name="cron_name" />';
			echo '</div>';
			
			echo '<div class="form-group">';
				echo '<label for="input_2">Description:</label>';
				echo '<input type="text" class="form-control" id="input_2" name="cron_description" />';
			echo '</div>';

			echo '<div class="form-group">';
				echo '<label for="input_3">File:</label>';
				echo '<select class="form-control" id="input_3" name="cron_file">';
					echo listCronFiles();
				echo '</select>';
			echo '</div>';
			
			echo '<div class="form-group">';
				echo '<label for="input_4">Run time:</label>';
				echo '<select class="form-control" id="input_4" name="cron_time">';
					if(is_array($cron_times)) {
						foreach($cron_times as $seconds => $description) {
							echo '<option value="'.$seconds.'">'.$description.'</option>';
						}
					} else {
						echo '<option value="300">5 Minutes</option>';
					}
				echo '</select>';
			echo '</div>';
			
			echo '<button type="submit" name="add_cron" value="Add" class="btn btn-primary">Save Cron Job</button>';
		echo '</form>';
	echo '</div>';
echo '</div>';