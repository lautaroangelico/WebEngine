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
<h1 class="page-header">New Cron Job</h1>
<?php

$cron_times = array(
	1 => 60*5,
	2 => 60*10,
	3 => 60*15,
	4 => 60*30,
	5 => 60*60,
	6 => 3600*2,
	7 => 3600*4,
	8 => 3600*8,
	9 => 3600*10,
	10 => 3600*12,
	11 => 86400,
	12 => 86400*3,
	13 => 86400*7,
	14 => 604800*2,
	15 => 604800*3,
	16 => 604800*4
);

if(check_value($_POST['add_cron'])) {
	addCron($cron_times);
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
					echo '<option value="1">Every 5 minutes</option>';
					echo '<option value="2">Every 10 minutes</option>';
					echo '<option value="3">Every 15 minutes</option>';
					echo '<option value="4">Every 30 minutes</option>';
					echo '<option value="5">Every 60 minutes</option>';
					echo '<option value="6">Every 2 hours</option>';
					echo '<option value="7">Every 4 hours</option>';
					echo '<option value="8">Every 8 hours</option>';
					echo '<option value="9">Every 10 hours</option>';
					echo '<option value="10">Every 12 hours</option>';
					echo '<option value="11">Every 24 hours</option>';
					echo '<option value="12">Every 3 days</option>';
					echo '<option value="13">Every 7 days</option>';
					echo '<option value="14">Every 2 weeks</option>';
					echo '<option value="15">Every 3 weeks</option>';
					echo '<option value="16">Every 4 weeks</option>';
				echo '</select>';
			echo '</div>';
			
			echo '<button type="submit" name="add_cron" value="Add" class="btn btn-primary">Save Cron Job</button>';
		echo '</form>';
	echo '</div>';
echo '</div>';