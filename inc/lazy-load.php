<?php
/**
 * Get Lazy Load Preload
 * Version: 1.0.1
 *
 * @package SimplePress
 */


/**
 * Elazy Tag
 */
if ( ! class_exists( 'Lazy_Load_Preload' ) ) :
class Lazy_Load_Preload {
    const version = '1.0.1';
	protected static $enabled = true;

	static function init() {
		if ( is_admin() )
			return;

		if ( ! apply_filters( 'lazyload_is_enabled', true ) ) {
			self::$enabled = false;
			return;
		}

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_scripts' ), 1, true );
		add_action( 'wp_head', array( __CLASS__, 'setup_filters' ), 999 ); 
	}

	static function setup_filters() {
		add_filter( 'the_content', array( __CLASS__, 'add_image_placeholders' ), 99 );
		add_filter( 'post_thumbnail_html', array( __CLASS__, 'add_image_placeholders' ), 11 );
		add_filter( 'get_avatar', array( __CLASS__, 'add_image_placeholders' ), 11 );
		echo "<style id='simplepress-lazydefer-css' type='text/css'>.lazydefer{filter:alpha(opacity=40);opacity:.4}.defered{filter:alpha(opacity=100);opacity:1;-webkit-transition:opacity 1.1s ease-in-out;-moz-transition:opacity 1.1s ease-in-out;-o-transition:opacity 1.1s ease-in-out;transition:opacity 1.1s ease-in-out}</style>";
		echo "<noscript><style type='text/css'>.lazydefer{display:none!important}</style></noscript>";
	}

	static function add_scripts() {
		wp_enqueue_script( 'image-preload', get_template_directory_uri() . '/inc/js/preload.js', true );
	}

	static function add_image_placeholders( $content ) {
		if ( ! self::is_enabled() )
			return $content;

		// Don't lazyload for feeds, previews
		if( is_feed() || is_preview() )
			return $content;

		// Don't lazy-load if the content has already been run through previously
		if ( false !== strpos( $content, 'data-lazy-src' ) )
			return $content;
		// This is a pretty simple regex, but it works
		$content = preg_replace_callback( '#<(img)([^>]+?)(>(.*?)</\\1>|[\/]?>)#si', array( __CLASS__, 'process_image' ), $content );
		return $content;
	}

	static function process_image( $matches ) {
		// In case you want to change the placeholder image
		$placeholder_image = apply_filters( 'lazyload_images_placeholder_image', get_template_directory_uri() . '/inc/img/loaded.gif', $matches );

		$old_attributes_str = $matches[2];
		$old_attributes = wp_kses_hair( $old_attributes_str, wp_allowed_protocols() );

		if ( empty( $old_attributes['src'] ) ) {
			return $matches[0];
		}

		$image_src = $old_attributes['src']['value'];
		$image_srcset = $old_attributes['srcset']['value'];

		// Remove src and data-lazy-src since we manually add them
		$new_attributes = $old_attributes;
		$css_class = $new_attributes['class'] ? $new_attributes['class']['value'].' lazydefer' : 'lazydefer';
		
		// Retain the srcset, and sizes if present
        $lazy_srcset_attribute = ( ! empty( $old_attributes['srcset'] ) ) ? 'data-lazy-srcset="' . $old_attributes['srcset']['value'] . '"' : '';
        $lazy_sizes_attribute = ( ! empty( $old_attributes['sizes'] ) ) ? 'data-lazy-sizes="' . $old_attributes['sizes']['value'] . '"' : '';
		
		unset( $new_attributes['src'], $new_attributes['data-lazy-src'], $new_attributes['srcset'], $new_attributes['data-lazy-srcset'], $new_attributes['sizes'], $new_attributes['data-lazy-sizes'], $new_attributes['class'] );

		$new_attributes_str = self::build_attributes_string( $new_attributes );
        $placeholder_url = strpos($placeholder_image, 'data:') == 0 ? $placeholder_image : esc_url( $placeholder_image );
		
		return sprintf( '<img src="%1$s" class="%2$s" data-lazy-src="%3$s" %4$s %5$s %6$s><noscript>%7$s</noscript>', $placeholder_url, $css_class, esc_url( $image_src ), $lazy_srcset_attribute, $lazy_sizes_attribute, $new_attributes_str, $matches[0] );
	}

	private static function build_attributes_string( $attributes ) {
		$string = array();
		foreach ( $attributes as $name => $attribute ) {
			$value = $attribute['value'];
			if ( '' === $value ) {
				$string[] = sprintf( '%s', $name );
			} else {
				$string[] = sprintf( '%s="%s"', $name, esc_attr( $value ) );
			}
		}
		return implode( ' ', $string );
	}

	static function is_enabled() {
		return self::$enabled;
	}

	static function get_url( $path = '' ) {
	    //plugins_url( ltrim( $path, '/' ), __FILE__ );
		return get_template_directory_uri( ltrim( $path, '/' ), __FILE__ );
	}
}

// Disable srcset on frontend for (X)HTML5 validation
add_filter('max_srcset_image_width', create_function('', 'return 1;'));

function lazyload_images_add_placeholders( $content ) {
	return Lazy_Load_Preload::add_image_placeholders( $content );
}

add_action( 'init', array( 'Lazy_Load_Preload', 'init' ) );
endif; ?>