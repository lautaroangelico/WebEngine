<div class="footer-container">
	<div class="col-xs-8">
		<p>&copy; <?php echo date("Y"); ?> <?php config('server_name'); ?></p>
		<p>This site is in no way associated with or endorsed by Webzen Inc.</p>
		
		<br />
		<p>MU Online is a free-to-play medieval fantasy MMORPG from Webzen. The game features fast-paced combat, quests, dungeons, PvP, castle sieges, and more. Players can choose from the eight classes of Dark Knight, Dark Wizard, Fairy Elf, Magic Gladiator, Dark Lord, Summoner, Rage Fighter and Grow Lancer, and participate in a variety of official combat-centric events and prize challenges.</p>
		<br />
		
		<?php $handler->webenginePowered(); ?>
	</div>
	<div class="col-xs-4">
		<div class="col-xs-6 text-center">
			<span style="font-weight:bold;"><?php lang('server_time', false); ?></span><br />
			<span class="footer-time"><time id="tServerTime"></time></span>
		</div>
		<div class="col-xs-6 text-center">
			<span style="font-weight:bold;"><?php lang('user_time', false); ?></span><br />
			<span class="footer-time"><time id="tLocalTime"></time></span>
		</div>
	</div>
</div>