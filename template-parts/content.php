<?php
/**
 * Template part for displaying posts
 *
 * @package SimplePress
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				simplepress_posted_on();
				simplepress_updated_on();
				simplepress_comments_on();
				if (function_exists('the_views')): ?>
				    <i class="icon-eye"></i>
				    <?php the_views(); ?>
				<?php endif;
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'simplepress' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'simplepress' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php simplepress_entry_footer(); ?>
	</footer><!-- .entry-footer -->
	<?php if ( get_theme_mod( 'author_bio' ) != 1 ) : ?>
		<div class="clearfix author-info">
			<div class="author-photo"><?php echo get_avatar( get_the_author_meta( 'ID' ) , 75 ); ?></div>
			<div class="author-content">
				<h3><?php the_author(); ?></h3>
				<p><?php the_author_meta( 'description' ); ?></p>
				<div class="author-links">
					<a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="me"><i class="icon-vcard"></i></a>

					<?php if ( get_the_author_meta( 'twitter' ) ) : ?>
					<a href="https://twitter.com/<?php echo get_the_author_meta( 'twitter' ); ?>"><i class="icon-twitter"></i></a>
					<?php endif; ?>

					<?php if ( get_the_author_meta( 'facebook' ) ) : ?>
					<a href="https://facebook.com/<?php echo get_the_author_meta( 'facebook' ); ?>"><i class="icon-facebook"></i></a>
					<?php endif; ?>

					<?php if ( get_the_author_meta( 'linkedin' ) ) : ?>
					<a href="https://linkedin.com/in/<?php echo get_the_author_meta( 'linkedin' ); ?>"><i class="icon-linkedin"></i></a>
					<?php endif; ?>
				</div>
			</div>
		</div><!-- .author-bio -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
