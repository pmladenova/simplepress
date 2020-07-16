<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package SimplePress
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'simplepress' ); ?></a>
	<nav id="top-navigation" class="top-navigation">
		<div class="clearfix container">
			<?php wp_nav_menu( array( 'container_class' => 'sp-menu', 'theme_location' => 'top' ) ); ?>
			<div class="search">
			    <form role="search" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			        <input type="text" value=" " name="s" id="s" />
			        <input type="submit" id="searchsubmit" value=" " />
			    </form>
			</div>
		</div>
	</nav><!-- #top-navigation -->

	<header id="masthead" class="site-header">
		<div class="site-branding clearfix container">
        <?php
            if( get_custom_logo() ) {
               ?>
               <div id="logo"><?php echo the_custom_logo(); ?></div>
               <?php
            } elseif ( is_front_page() && is_home() ) {
                ?>
                <div class="text-branding">
                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                <?php
                $simplepress_description = get_bloginfo( 'description', 'display' );
            } else {
                ?>
                <div class="text-branding">
                    <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                <?php
                $simplepress_description = get_bloginfo( 'description', 'display' );
            }
            if ( ( isset($simplepress_description) && $simplepress_description) || is_customize_preview() ) {
                ?>
                    <p class="site-description"><?php echo $simplepress_description; /* WPCS: xss ok. */ ?></p>
                </div>
                <?php
            }
        ?>
		<?php 
		    if( $header_banner = wp_kses_post(get_theme_mod('header_banner' ))) { 
		        ?> 
		        <div id="header-banner"><?php echo $header_banner; ?></div><!-- #header-banner -->
		        <?php 
		    } 
		?>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation">
		    <div class="clearfix container">
			    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><i class="icon-menu"></i><?php esc_html_e( 'MENU CATEGORIES', 'simplepress' ); ?></button>
		    	<?php wp_nav_menu( array( 'theme_location' => 'main', 'menu_id' => 'primary-menu' ) ); ?>
		    </div>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->
