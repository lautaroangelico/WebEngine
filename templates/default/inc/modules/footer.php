   
<div class="footer-container">
<!-- Remove the container if you want to extend the Footer to full width. -->
<div class="container-fluid" style="padding:unset;">
  <!-- Footer -->
  <footer class="text-center">
    <!-- Grid container -->
    <div class="container">
      <!-- Section: Links -->
      <section>
        <!-- Grid row-->
        <div class="row text-center d-flex justify-content-center pt-5">
          <!-- Grid column -->
          <div class="col-md-2">
            <h6 class="text-uppercase font-weight-bold">
				<a href="<?php echo __BASE_URL__; ?>tos/" class="text-<?php echo $GLOBALS['ColorTemplate'];?>"><?php echo lang('footer_terms'); ?></a>
            </h6>
          </div>
          <!-- Grid column -->

          <!-- Grid column -->
          <div class="col-md-2">
            <h6 class="text-uppercase font-weight-bold">
				<a href="<?php echo __BASE_URL__; ?>privacy/" class="text-<?php echo $GLOBALS['ColorTemplate'];?>"><?php echo lang('footer_privacy'); ?></a>
            </h6>
          </div>
          <!-- Grid column -->

          <!-- Grid column -->
          <div class="col-md-2">
            <h6 class="text-uppercase font-weight-bold">
				<a href="<?php echo __BASE_URL__; ?>refunds/" class="text-<?php echo $GLOBALS['ColorTemplate'];?>"><?php echo lang('footer_refund'); ?></a>
            </h6>
          </div>
          <!-- Grid column -->

          <!-- Grid column -->
          <div class="col-md-2">
            <h6 class="text-uppercase font-weight-bold">
				<a href="<?php echo __BASE_URL__; ?>info/" class="text-<?php echo $GLOBALS['ColorTemplate'];?>"><?php echo lang('footer_info'); ?></a>
            </h6>
          </div>
          <!-- Grid column -->

          <!-- Grid column -->
          <div class="col-md-2">
            <h6 class="text-uppercase font-weight-bold">
				<a href="<?php echo __BASE_URL__; ?>contact/" class="text-<?php echo $GLOBALS['ColorTemplate'];?>"><?php echo lang('footer_contact'); ?></a>
            </h6>
          </div>
          <!-- Grid column -->
        </div>
        <!-- Grid row-->
      </section>
      <!-- Section: Links -->

      <hr class="my-5" />

	  <!-- Section: Social -->
      <section class="text-center mb-5">
        <a href="<?php config('social_link_facebook'); ?>" class="text-<?php echo $GLOBALS['ColorTemplate'];?> me-4 fs-1">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="<?php config('social_link_instagram'); ?>" class="text-<?php echo $GLOBALS['ColorTemplate'];?> me-4 fs-1">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="<?php config('social_link_discord'); ?>" class="text-<?php echo $GLOBALS['ColorTemplate'];?> me-4 fs-1">
          <i class="fab fa-discord"></i>
        </a>
      </section>
      <!-- Section: Social -->

      <!-- Section: Text -->
      <section class="mb-3">
        <div class="row d-flex justify-content-center">
          <div class="col-lg-8">
            <h6>
				<?php echo langf('footer_copyright', array(config('server_name', true), date("Y"))); ?><br />
				<?php echo lang('footer_webzen_copyright'); ?>
            </h6>
          </div>
        </div>
      </section>
      <!-- Section: Text -->

      
    </div>
    <!-- Grid container -->

    <!-- Copyright -->
    <div
         class="text-center p-3"
         style="background-color: rgba(0, 0, 0, 0.2)"
         >
		<?php $handler->webenginePowered(); ?>
    </div>
    <!-- Copyright -->
  </footer>
  <!-- Footer -->
</div>
<!-- End of .container -->
</div>