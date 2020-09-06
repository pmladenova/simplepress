<?php
/**
 * SimplePress functions and definitions
 *
 * @package SimplePress
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'simplepress_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function simplepress_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on SimplePress, use a find and replace
		 * to change 'simplepress' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'simplepress', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		/**
		 * Title Separator '|' 
		 */
		 function simplepress_title_separator($sep) {
		     return ' | ';
		 }
		 add_filter('document_title_separator', 'simplepress_title_separator', 10, 1);

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'thumb-small', 50, 50, true );
		add_image_size( 'thumb-medium', 300, 135, true );
		add_image_size( 'thumb-featured', 640, 250, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
			    'top' => esc_html__( 'Top Menu', 'simplepress' ),
				'main' => esc_html__( 'Main Menu', 'simplepress' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 150,
				'width'       => 300,
				'flex-width'  => false,
				'flex-height' => false,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'simplepress_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function simplepress_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'simplepress_content_width', 650 );
}
add_action( 'after_setup_theme', 'simplepress_content_width', 0 );

// Filter the output of logo to fix Googles Error about itemprop logo
function sp_custom_logo() {
    if ($custom_logo_id = get_theme_mod( 'custom_logo' )) {
    $html = sprintf( '<a href="%1$s" class="custom-logo-link" rel="home">%2$s</a>',
            esc_url( home_url( '/' ) ),
            wp_get_attachment_image( $custom_logo_id, 'full', false, array(
                'class'    => 'custom-logo',
            ) )
        );
    }
    return $html;   
}
add_filter( 'get_custom_logo', 'sp_custom_logo' );


/**
 * Sets the post excerpt length to 33 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function simplepress_excerpt_length( $length ) {
	return 33;
}
add_filter( 'excerpt_length', 'simplepress_excerpt_length' );

/**
 * Prevent Page Scroll When Clicking the More Link
 */
function remove_more_link_scroll( $link ) {
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
}
add_filter( 'the_content_more_link', 'remove_more_link_scroll' );

// Replaces the excerpt "Read More" text by a link
function new_excerpt_more( $more ) {
    global $post;
	return '<a class="more-link" href="'. get_permalink($post->ID) . '">Read the full article</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

/**
 * Register widget area
 */
function simplepress_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sub Footer 1', 'simplepress' ),
		'id'            => 'sidebar-2',
		'description'   => esc_html__( 'Add widgets here.', 'simplepress' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Sub Footer 2', 'simplepress' ),
		'id'            => 'sidebar-3',
		'description'   => esc_html__( 'Add widgets here.', 'simplepress' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Sub Footer 3', 'simplepress' ),
		'id'            => 'sidebar-4',
		'description'   => esc_html__( 'Add widgets here.', 'simplepress' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Sub Footer 4', 'simplepress' ),
		'id'            => 'sidebar-5',
		'description'   => esc_html__( 'Add widgets here.', 'simplepress' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'simplepress_widgets_init' );

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function simplepress_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-2' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-5' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'site-extra extra-one';
			break;
		case '2':
			$class = 'site-extra extra-two';
			break;
		case '3':
			$class = 'site-extra extra-three';
			break;
		case '4':
			$class = 'site-extra extra-four';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}

/**
 * Enqueue scripts and styles.
 */
function simplepress_scripts() {
	wp_enqueue_style( 'simplepress-style', get_stylesheet_uri() );

	wp_enqueue_script( 'simplepress-navigation', get_template_directory_uri() . '/js/navigation.js', array(), true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

   if ( is_page( 'contact' ) ) {
		wp_enqueue_script( 'simplepress-contact', get_template_directory_uri() . '/js/verif.js', array(), true );
   }
}
add_action( 'wp_enqueue_scripts', 'simplepress_scripts' );

/**
 * Load html5shiv
 */
function simplepress_html5shiv() {
    echo '<!--[if lt IE 9]>' . "\n";
    echo '<script src="' . esc_url( get_template_directory_uri() . '/js/html5shiv.js' ) . '"></script>' . "\n";
    echo '<![endif]-->' . "\n";
}
add_action( 'wp_head', 'simplepress_html5shiv' );

/**
 * Footer credits
 */
function simplepress_footer_credits() {
	?>
	<div class="site-info">
		&copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?><?php esc_html_e('. All Rights Reserved.', 'simplepress'); ?>
	</div><!-- .site-info -->

	<div class="site-credit">
	    <a href="<?php echo esc_url( __( 'https://www.classicpress.net/', 'simplepress' ) ); ?>">
			<?php
			/* translators: %s: CMS name, i.e. ClassicPress. */
			printf( esc_html__( 'Proudly powered by %s', 'simplepress' ), 'ClassicPress' );
			?>
		</a>
			<span class="sep"> &bull; </span>
		<a href="https://www.inter-reklama.com/">SimplePress</a><?php esc_html_e(' by Petya Mladenova', 'simplepress'); ?>
	</div><!-- .site-credit -->
	<?php
}
add_action( 'simplepress_footer', 'simplepress_footer_credits' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Get Optimized Header.
 */
require get_template_directory() . '/inc/optimize-header.php';

/**
 * Get Optimized Content.
 */
require get_template_directory() . '/inc/optimize-content.php';

/**
 * User Profile.
 */
require get_template_directory() . '/inc/user-profile.php';

/**
 * Get Lazy Load Preload.
 */
require get_template_directory() . '/inc/lazy-load.php';

/**
 * Get Sitemap.xml.
 */
require get_template_directory() . '/inc/sitemap-xml.php';

/**
 * Get Sitemap.html.
 */
require get_template_directory() . '/inc/sitemap-html.php';

/**
 * Get OpenGraph.
 */
require get_template_directory() . '/inc/og.php';

/**
 * Get Schema.
 */
require get_template_directory() . '/inc/schema.php';

/**
 * Get SEO friendly search.
 */
require get_template_directory() . '/inc/seo-friendly-search.php';

/**
 * Get custom social buttons for this theme.
 */
require get_template_directory() . '/inc/social-share.php';

/**
 * Get custom header banner.
 */
require get_template_directory() . '/inc/header-banner.php';

/**
 * Get custom banner ads.
 */
require get_template_directory() . '/inc/custom-banner-ads.php';

/**
 * Google Analytics.
 */
require get_template_directory() . '/inc/analytics.php';

/**
 * Custom Archives.
 */
require get_template_directory() . '/inc/custom-archives.php';

/**
 * Get Download Function.
 */
require get_template_directory() . '/inc/download.php';

/**
 * Custom Comments Customizer.
 */
function spcomments_customizer($wp_customize) {
    $wp_customize->add_section(
        'spcomments_section',
        array(
            'title'     => __('Custom Comments Activation'),
        )
    );
    $wp_customize->add_setting(
        'spcomments',
        array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    $wp_customize->add_control(
        'spcomments',
        array(
		    'description' => __('(You must uncheck the "Comment author must fill out name and email" in the "Discussion Settings" [go to settings > discussion]).'),
            'label' => __('Select the checkbox to enable Custom Comments. This option includes: Add comments notes, disable email comment, disable URL comment, remove clickable in comment content, hide comment checkbox "Cookies Consent", add checkbox to avoid spam.'),
            'section' => 'spcomments_section',
            'type' => 'checkbox'
        )
    );
}
add_action('customize_register', 'spcomments_customizer');

// Get the checked Custom Comments.
if ($checked_value = wp_kses_post(get_theme_mod('spcomments'))) {
    require get_template_directory() . '/inc/custom-comments.php';
}

/**
 * Get GDPR Cookie Box.
 */
require get_template_directory() . '/inc/cookies.php';
