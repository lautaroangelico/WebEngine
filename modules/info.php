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

// Module Title
echo '<div class="page-title"><span>'.lang('module_titles_txt_17').'</span></div>';

?>

<!-- SERVER STATISTICS -->
<table class="table table-condensed table-hover table-striped table-bordered">
	<thead>
		<tr>
			<th colspan="2">General Information</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="width:50%;">Server Version</td>
			<td style="width:50%;"><?php echo config('server_info_season'); ?></td>
		</tr>
		<tr>
			<td style="width:50%;">Experience</td>
			<td style="width:50%;"><?php echo config('server_info_exp'); ?></td>
		</tr>
		<tr>
			<td style="width:50%;">Master Experience</td>
			<td style="width:50%;"><?php echo config('server_info_masterexp'); ?></td>
		</tr>
		<tr>
			<td style="width:50%;">Drop</td>
			<td style="width:50%;"><?php echo config('server_info_drop'); ?></td>
		</tr>
	</tbody>
</table>

<br />

<!-- CHAOS MACHINE RATES -->
<h2>Chaos Machine</h2>
<table class="table table-condensed table-hover table-striped table-bordered">
	<tbody>
		<tr>
			<td width="30%" rowspan="2" style="vertical-align: middle;">Combination</td>
			<td width="60%" colspan="2" class="text-center">
				Maximum Success Rate
			</td>
		</tr>
		<tr>
			<td width="30%" class="text-center">
				Normal
			</td>
			<td width="30%" class="text-center">
				Gold
			</td>
		</tr>
		<tr>
			<td scope="row">Item Luck</td>
			<td class="text-center">
				x%
			</td>
			<td class="text-center">
				x%
			</td>
		</tr>
		<tr>
			<td scope="row">Items +10, +11, +12</td>
			<td class="text-center">
				x% + Luck
			</td>
			<td class="text-center">
				x% + Luck
			</td>
		</tr>
		<tr>
			<td scope="row">Items +13, +14, +15</td>
			<td class="text-center">
				x% + Luck
			</td>
			<td class="text-center">
				x% + Luck
			</td>
		</tr>
		<tr>
			<td scope="row">Wings Level 1</td>
			<td class="text-center">
				x% + Luck
			</td>
			<td class="text-center">
				x% + Luck
			</td>
		</tr>
		<tr>
			<td scope="row">Wings Level 2</td>
			<td class="text-center">
				x%
			</td>
			<td class="text-center">
				x%
			</td>
		</tr>
		<tr>
			<td scope="row">Wings Level 3</td>
			<td class="text-center">
				x%
			</td>
			<td class="text-center">
				x%
			</td>
		</tr>
		<tr>
			<td scope="row">Wings Level 4</td>
			<td class="text-center">
				x%
			</td>
			<td class="text-center">
				x%
			</td>
		</tr>
		<tr>
			<td scope="row">Cape of Lord Mix</td>
			<td class="text-center">
				x%
			</td>
			<td class="text-center">
				x%
			</td>
		</tr>
		<tr>
			<td scope="row">Socket Weapon Mix</td>
			<td class="text-center">
				x%
			</td>
			<td class="text-center">
				x%
			</td>
		</tr>
		<tr>
			<td scope="row">Fragment of Horn Mix</td>
			<td class="text-center">
				x%
			</td>
			<td class="text-center">
				x%
			</td>
		</tr>
		<tr>
			<td scope="row">Broken Horn Mix</td>
			<td class="text-center">
				x%
			</td>
			<td class="text-center">
				x%
			</td>
		</tr>
		<tr>
			<td scope="row">Horn of Fenrir Mix</td>
			<td class="text-center">
				x%
			</td>
			<td class="text-center">
				x%
			</td>
		</tr>
		<tr>
			<td scope="row">Feather of Condor</td>
			<td class="text-center">
				x%
			</td>
			<td class="text-center">
				x%
			</td>
		</tr>
		<tr>
			<td scope="row">Ancient Hero's Soul</td>
			<td class="text-center">
				x%
			</td>
			<td class="text-center">
				x%
			</td>
		</tr>
	</tbody>
</table>

<br />

<!-- PARTY EXPERIENCE BONUS -->
<h2>Party Bonus Experience</h2>
<table class="table table-condensed table-hover table-striped table-bordered">
	<tbody>
		<tr>
			<td width="30%" rowspan="2" style="vertical-align: middle;">Members</td>
			<td width="70%" colspan="5" class="text-center">
				Experience Rate
			</td>
		</tr>
		<tr>
			<td width="35%" class="text-center">
				Same Character Classes
			</td>
			<td width="35%" class="text-center">
				Different Classes
			</td>
		</tr>
		<tr>
			<td scope="row">2 Players</td>
			<td>
				EXP% + x%
			</td>
			<td>
				EXP% + x%
			</td>
		</tr>
		<tr>
			<td scope="row">3 Players</td>
			<td>
				EXP% + x%
			</td>
			<td>
				EXP% + x%
			</td>
		</tr>
		<tr>
			<td scope="row">4 Players</td>
			<td>
				EXP% + x%
			</td>
			<td>
				EXP% + x%
			</td>
		</tr>
		<tr>
			<td scope="row">5 Players</td>
			<td>
				EXP% + x%
			</td>
			<td>
				EXP% + x%
			</td>
		</tr>
	</tbody>
</table>

<br />

<!-- COMMANDS -->
<h2>Commands</h2>
<table class="table table-condensed table-hover table-striped table-bordered">
	<tbody>
		<tr>
			<td>/reset</td>
			<td>Reset your character.</td>
		</tr>
		<tr>
			<td>/whisper [on/off]</td>
			<td>Enable / disable whisper.</td>
		</tr>
		<tr>
			<td>/clearpk</td>
			<td>Clear killer status</td>
		</tr>
		<tr>
			<td>/post [message]</td>
			<td>Sends a message to the whole server.</td>
		</tr>
		<tr>
			<td>/str [points]</td>
			<td>Adds points to Strength.</td>
		</tr>
		<tr>
			<td>/addagi [points]</td>
			<td>Adds points to Agility.</td>
		</tr>
		<tr>
			<td>/addvit [points]</td>
			<td>Adds points to Life.</td>
		</tr>
		<tr>
			<td>/addene [points]</td>
			<td>Adds points to Energy.</td>
		</tr>
		<tr>
			<td>/addcmd [points]</td>
			<td>Adds points to Command.</td>
		</tr>
		<tr>
			<td>/requests [on/off]</td>
			<td>Enable / disable requests in-game.</td>
		</tr>
	</tbody>
</table>

<br />

<!-- VIDEO -->
<h2>Video</h2>
<iframe width="636" height="357" src="https://www.youtube.com/embed/H5QQDvgU-hE?controls=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>