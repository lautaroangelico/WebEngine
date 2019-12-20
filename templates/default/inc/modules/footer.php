<div class="footer-container">
	<div class="col-xs-8">
		<p>&copy; <?php echo date("Y"); ?> <?php config('server_name'); ?></p>
		<p>This site is in no way associated with or endorsed by Webzen Inc.</p>
		
		<br />
		<p>MU Online is a free-to-play medieval fantasy MMORPG. The game features fast-paced combat, quests, dungeons, PvP, castle sieges, and more. Players can choose from the nine classes of Dark Knight, Dark Wizard, Fairy Elf, Magic Gladiator, Dark Lord, Summoner, Rage Fighter, Grow Lancer and Rune Wizard, and participate in a variety of official combat-centric events and prize challenges.</p>
		<br />
		
		<?php $handler->webenginePowered(); ?>
	</div>
	<div class="col-xs-4">
		<div class="col-xs-6 text-center">
			<span class="footer-time-title"><?php echo lang('server_time'); ?></span><br />
			<span class="footer-time"><time id="tServerTime"></time></span><br />
			<span class="footer-date" id="tServerDate"></span>
		</div>
		<div class="col-xs-6 text-center">
			<span class="footer-time-title"><?php echo lang('user_time'); ?></span><br />
			<span class="footer-time"><time id="tLocalTime"></time></span><br />
			<span class="footer-date" id="tLocalDate"></span>
		</div>
		<?php if(config('language_switch_active',true)) { ?>
		<div class="col-xs-12 text-center">
			<span style="color:#fff;"><?php echo lang('switch_lang'); ?></span><br />
			<a href="<?php echo __BASE_URL__ . 'language/switch/to/en'; ?>" data-toggle="tooltip" data-placement="top" title="English"><img src="<?php echo getCountryFlag('US'); ?>" /></a>
			<a href="<?php echo __BASE_URL__ . 'language/switch/to/es'; ?>" data-toggle="tooltip" data-placement="top" title="Español"><img src="<?php echo getCountryFlag('ES'); ?>" /></a>
			<a href="<?php echo __BASE_URL__ . 'language/switch/to/ph'; ?>" data-toggle="tooltip" data-placement="top" title="Filipino"><img src="<?php echo getCountryFlag('PH'); ?>" /></a>
			<a href="<?php echo __BASE_URL__ . 'language/switch/to/pt'; ?>" data-toggle="tooltip" data-placement="top" title="Português"><img src="<?php echo getCountryFlag('BR'); ?>" /></a>
			<a href="<?php echo __BASE_URL__ . 'language/switch/to/ro'; ?>" data-toggle="tooltip" data-placement="top" title="Romanian"><img src="<?php echo getCountryFlag('RO'); ?>" /></a>
			<a href="<?php echo __BASE_URL__ . 'language/switch/to/cn'; ?>" data-toggle="tooltip" data-placement="top" title="Simplified Chinese"><img src="<?php echo getCountryFlag('CN'); ?>" /></a>
		</div>
		<?php } ?>
	</div>
</div>