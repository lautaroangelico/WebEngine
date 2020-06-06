<div class="footer-container">
	<div class="row">
		<div class="col-xs-12">
			<a href="<?php echo __BASE_URL__; ?>tos/"><?php echo lang('footer_terms'); ?></a>
			<span style="padding:0px 5px;">|</span>
			<a href="<?php echo __BASE_URL__; ?>privacy/"><?php echo lang('footer_privacy'); ?></a>
			<span style="padding:0px 5px;">|</span>
			<a href="<?php echo __BASE_URL__; ?>refunds/"><?php echo lang('footer_refund'); ?></a>
			<span style="padding:0px 5px;">|</span>
			<a href="<?php echo __BASE_URL__; ?>info/"><?php echo lang('footer_info'); ?></a>
			<span style="padding:0px 5px;">|</span>
			<a href="<?php echo __BASE_URL__; ?>contact/"><?php echo lang('footer_contact'); ?></a>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-xs-8">
			<p>
				<?php echo langf('footer_copyright', array(config('server_name', true), date("Y"))); ?><br />
				<?php echo lang('footer_webzen_copyright'); ?>
			</p>
			<br />
			
			<?php $handler->webenginePowered(); ?>
		</div>
		<div class="col-xs-4">
			<div class="col-xs-4 text-center">
				<a href="<?php config('social_link_facebook'); ?>" target="_blank" class="footer-social-link">
					<img src="<?php echo __PATH_TEMPLATE_IMG__; ?>social/facebook.svg" width="50px" height="auto" />
				</a>
			</div>
			<div class="col-xs-4 text-center">
				<a href="<?php config('social_link_instagram'); ?>" target="_blank" class="footer-social-link">
					<img src="<?php echo __PATH_TEMPLATE_IMG__; ?>social/instagram.svg" width="50px" height="auto" />
				</a>
			</div>
			<div class="col-xs-4 text-center">
				<a href="<?php config('social_link_discord'); ?>" target="_blank" class="footer-social-link">
					<img src="<?php echo __PATH_TEMPLATE_IMG__; ?>social/discord.svg" width="50px" height="auto" />
				</a>
			</div>
		</div>
	</div>
</div>