<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package SimplePress
 */

?>

	<?php
		/* A sidebar in the footer? Yep. You can can customize
		 * your footer with up to four columns of widgets.
		 */
		get_sidebar( 'footer' );
	?>

	<footer id="colophon" class="site-footer">
	    <div class="clearfix container">
		    <div class="site-info-copy">
		        <?php do_action('simplepress_footer'); ?>
		    </div><!-- .site-info -->
		</div><!-- #clearfix -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>