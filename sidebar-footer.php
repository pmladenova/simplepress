<?php
/**
 * The widget areas in the footer.
 *
 * @package SimplePress
 */
?>

<?php
	/* The footer widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if (   ! is_active_sidebar( 'sidebar-2' )
		&& ! is_active_sidebar( 'sidebar-3' )
		&& ! is_active_sidebar( 'sidebar-4' )
		&& ! is_active_sidebar( 'sidebar-5' )
	)
	return;
	// If we get this far, we have widgets. Let do this.
?>

<div id="extra" <?php simplepress_footer_sidebar_class(); ?>>
	<div class="clearfix container">
	<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
		<div id="widget-area-2" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-2' ); ?>
	    </div><!-- #widget-area-2 -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
		<div id="widget-area-3" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-3' ); ?>
	    </div><!-- #widget-area-3 -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-4' ) ) : ?>
		<div id="widget-area-4" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-4' ); ?>
	    </div><!-- #widget-area- 4-->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-5' ) ) : ?>
		<div id="widget-area-5" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-5' ); ?>
	    </div><!-- #widget-area-5 -->
	<?php endif; ?>
	</div>
</div><!-- #extra -->
