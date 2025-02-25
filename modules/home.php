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
<div class="row">
	<div class="col-xs-8 home-news-block">
		<div class="row home-news-block-header">
			<div class="col-xs-8">
				<h2><?php echo lang('news_txt_4'); ?></h2>
			</div>
			<div class="col-xs-4 text-right">
				<a href="<?php echo __BASE_URL__ . 'news/'; ?>"><?php echo lang('news_txt_5'); ?></a>
			</div>
		</div>
		<div class="row home-news-block-body">
			<div class="col-xs-12">
				<?php
					$News = new News();
					$newsList = $News->retrieveNews();
					if(is_array($newsList)) {
						foreach($newsList as $key => $newsArticle) {
							
							if($key >= 7) break;
							
							$News->setId($newsArticle['news_id']);
							
							if(config('language_switch_active',true)) {
								if(isset($_SESSION['language_display'])) {
									$News->setLanguage($_SESSION['language_display']);
									$newsTranslationData = $News->getNewsTranlationDataById();
									if(is_array($newsTranslationData)) {
										$newsArticle['news_title'] = $newsTranslationData['news_title'];
										$newsArticle['news_content'] = $newsTranslationData['news_content'];
									}
								}
							}
							
							$news_url = __BASE_URL__.'news/'.$newsArticle['news_id'].'/';
							
							echo '<div class="row home-news-block-article">';
								echo '<div class="col-xs-3">';
									echo '<span class="home-news-block-article-type">'.lang('news_txt_6').'</span>';
								echo '</div>';
								echo '<div class="col-xs-6 home-news-block-article-title-container">';
									echo '<span class="home-news-block-article-title"><a href="'.$news_url.'">'.$newsArticle['news_title'].'</a></span>';
								echo '</div>';
								echo '<div class="col-xs-3 text-right">';
									echo '<span class="home-news-block-article-date">'.date("Y/m/d", $newsArticle['news_date']).'</span>';
								echo '</div>';
							echo '</div>';
							
						}
					}
				?>
			</div>
		</div>
	</div>
	<div class="col-xs-4">
		<?php
		if(!isLoggedIn()) {
			echo '<div class="panel panel-sidebar">';
				echo '<div class="panel-heading">';
					echo '<h3 class="panel-title">'.lang('module_titles_txt_2').' <a href="'.__BASE_URL__.'forgotpassword" class="btn btn-primary btn-xs pull-right">'.lang('login_txt_4').'</a></h3>';
				echo '</div>';
				echo '<div class="panel-body">';
					echo '<form action="'.__BASE_URL__.'login" method="post">';
						echo '<div class="form-group">';
							echo '<input type="text" class="form-control" id="loginBox1" name="webengineLogin_user" required>';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<input type="password" class="form-control" id="loginBox2" name="webengineLogin_pwd" required>';
						echo '</div>';
						echo '<button type="submit" name="webengineLogin_submit" value="submit" class="btn btn-primary">'.lang('login_txt_3').'</button>';
					echo '</form>';
				echo '</div>';
			echo '</div>';
			
			echo '<div class="sidebar-banner"><a href="'.__BASE_URL__.'register"><img src="'.__PATH_TEMPLATE_IMG__.'sidebar_banner_join.jpg"/></a></div>';
		} else {
			echo '<div class="panel panel-sidebar panel-usercp">';
				echo '<div class="panel-heading">';
					echo '<h3 class="panel-title">'.lang('usercp_menu_title').' <a href="'.__BASE_URL__.'logout" class="btn btn-primary btn-xs pull-right">'.lang('login_txt_6').'</a></h3>';
				echo '</div>';
				echo '<div class="panel-body">';
						templateBuildUsercp();
				echo '</div>';
			echo '</div>';
		}
		?>
	</div>
</div>

<div class="row" style="margin-top: 20px;">
	<div class="col-xs-4">
		<?php
		// Top Level
		$levelRankingData = LoadCacheData('rankings_level.cache');
		$topLevelLimit = 10;
		if(is_array($levelRankingData)) {
			$topLevel = array_slice($levelRankingData, 0, $topLevelLimit+1);
			echo '<div class="panel panel-sidebar">';
				echo '<div class="panel-heading">';
					echo '<h3 class="panel-title">'.lang('rankings_txt_1').'<a href="'.__BASE_URL__.'rankings/level" class="btn btn-primary btn-xs pull-right" style="text-align:center;width:22px;">+</a></h3>';
				echo '</div>';
				echo '<div class="panel-body" style="min-height:400px;">';
					echo '<table class="table table-condensed">';
						echo '<thead>';
							echo '<tr>';
								echo '<th class="text-center">'.lang('rankings_txt_10').'</th>'; // Character
								echo '<th class="text-center">'.lang('rankings_txt_11').'</th>'; // Class
								echo '<th class="text-center">'.lang('rankings_txt_12').'</th>'; // Level
							echo '</tr>';
						echo '</thead>';
						echo '<tbody>';
						foreach($topLevel as $key => $row) {
							if($key == 0) continue;
							echo '<tr>';
								echo '<td class="text-center">'.playerProfile($row[0]).'</td>';
								echo '<td class="text-center">'.getPlayerClass($row[1]).'</td>';
								echo '<td class="text-center">'.number_format($row[2]).'</td>';
							echo '</tr>';
						}
					echo '</tbody>';
					echo '</table>';
				echo '</div>';
			echo '</div>';
		}
		?>
	</div>
	<div class="col-xs-4">
		<?php
		// Top Guilds
		$guildRankingData = LoadCacheData('rankings_guilds.cache');
		$topGuildLimit = 10;
		if(is_array($guildRankingData)) {
			$rankingsConfig = loadConfigurations('rankings');
			$topGuild = array_slice($guildRankingData, 0, $topGuildLimit+1);
			echo '<div class="panel panel-sidebar">';
				echo '<div class="panel-heading">';
					echo '<h3 class="panel-title">'.lang('rankings_txt_4').'<a href="'.__BASE_URL__.'rankings/guilds" class="btn btn-primary btn-xs pull-right" style="text-align:center;width:22px;">+</a></h3>';
				echo '</div>';
				echo '<div class="panel-body" style="min-height:400px;">';
					echo '<table class="table table-condensed">';
						echo '<thead>';
							echo '<tr>';
								echo '<th class="text-center">'.lang('rankings_txt_17').'</th>'; // Guild Name
								echo '<th class="text-center">'.lang('rankings_txt_28').'</th>'; // Logo
								echo '<th class="text-center">'.lang('rankings_txt_19').'</th>'; // Score
							echo '</tr>';
						echo '</thead>';
						echo '<tbody>';
						foreach($topGuild as $key => $row) {
							if($key == 0) continue;
							$multiplier = $rankingsConfig['guild_score_formula'] == 1 ? 1 : $rankingsConfig['guild_score_multiplier'];
							echo '<tr>';
								echo '<td class="text-center">'.guildProfile($row[0]).'</td>';
								echo '<td class="text-center">'.returnGuildLogo($row[3], 20).'</td>';
								echo '<td class="text-center">'.number_format(floor($row[2]*$multiplier)).'</td>';
							echo '</tr>';
						}
					echo '</tbody>';
					echo '</table>';
				echo '</div>';
			echo '</div>';
		}
		?>
	</div>
	<div class="col-xs-4">
		<?php
		// Event Timers
		echo '<div class="panel panel-sidebar panel-sidebar-events">';
			echo '<div class="panel-heading">';
				echo '<h3 class="panel-title">'.lang('event_schedule').'</h3>';
			echo '</div>';
			echo '<div class="panel-body" style="min-height:400px;">';
				echo '<table class="table table-condensed">';
					echo '<tr>';
						echo '<td><span id="bloodcastle_name"></span><br /><span class="smalltext">'.lang('event_schedule_start').'</span></td>';
						echo '<td class="text-right"><span id="bloodcastle_next"></span><br /><span class="smalltext" id="bloodcastle"></span></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td><span id="devilsquare_name"></span><br /><span class="smalltext">'.lang('event_schedule_start').'</span></td>';
						echo '<td class="text-right"><span id="devilsquare_next"></span><br /><span class="smalltext" id="devilsquare"></span></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td><span id="chaoscastle_name"></span><br /><span class="smalltext">'.lang('event_schedule_start').'</span></td>';
						echo '<td class="text-right"><span id="chaoscastle_next"></span><br /><span class="smalltext" id="chaoscastle"></span></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td><span id="dragoninvasion_name"></span><br /><span class="smalltext">'.lang('event_schedule_start').'</span></td>';
						echo '<td class="text-right"><span id="dragoninvasion_next"></span><br /><span class="smalltext" id="dragoninvasion"></span></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td><span id="goldeninvasion_name"></span><br /><span class="smalltext">'.lang('event_schedule_start').'</span></td>';
						echo '<td class="text-right"><span id="goldeninvasion_next"></span><br /><span class="smalltext" id="goldeninvasion"></span></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td><span id="castlesiege_name"></span><br /><span class="smalltext">'.lang('event_schedule_start').'</span></td>';
						echo '<td class="text-right"><span id="castlesiege_next"></span><br /><span class="smalltext" id="castlesiege"></span></td>';
					echo '</tr>';
				echo '</table>';
			echo '</div>';
		echo '</div>';
		?>
	</div>
</div>