<?php
/**
 * Get Schema JSON
 * 
 * @package SimplePress
 */

/**
 * Load Schema on Header for site name
 */
function simplepress_json_ld_name() {
  // Only on home and front page
  if ( is_home() || is_front_page() ) {
  // Open script
  $html = '<script type="application/ld+json">';
    $html .= '{';
      $html .= '"@context": "http://schema.org",';
      $html .= '"@type": "WebSite",';
      $html .= '"name": "' . get_bloginfo('name') . '",';
      $html .= '"alternateName": "' . get_bloginfo('description') . '",';
      $html .= '"url": "' . home_url('/') . '",';
	  // Load Schema for search
      $html .= '"potentialAction": {';
        $html .= '"@type": "SearchAction",';
        $html .= '"target": "' . home_url() . '/?s={search_term_string}",';
        $html .= '"query-input": "required name=search_term_string"';
      $html .= '}';
    $html .= '}';
  // Close script
  $html .= '</script>';
  echo $html;
  }
}
add_action('wp_head', 'simplepress_json_ld_name');

/**
 * Load Schema on Header for articles
 */
function simplepress_json_ld_article() {
  // Only on single pages and pages
  if ( is_single() || is_page() ) {
    // We need access to the post
    global $post;
    setup_postdata($post);
    // Variables
    if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$logo = wp_get_attachment_image_src($custom_logo_id, 'full');
	$logo = esc_url( $logo[0] );
	} else {
       if ( function_exists( 'simplepress_custom_logo' ) && ( $logo = get_theme_mod( 'spcustomlogo' ) ) ) {
	   $logo = get_theme_mod( 'spcustomlogo' );
	   } else {
	     if(empty($logo)) {
	     $logo = get_home_url() . '/wp-content/themes/simplepress/img/logo.png';
	     }
	  }
	}
    $excerpt = get_the_excerpt();
	$number_of_words = apply_filters('og_description_words', 55);
    $excerpt = wp_trim_words(strip_tags(strip_shortcodes($post->post_content)), $number_of_words, '...');
    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
    // Open script
    $html = '<script type="application/ld+json">';
      $html .= '{';
        $html .= '"@context": "http://schema.org",';
        $html .= '"@type": "NewsArticle",';
        $html .= '"mainEntityOfPage": {';
          $html .= '"@type":"WebPage",';
          $html .= '"@id": "' . get_the_permalink() . '"';
        $html .= '},';
        $html .= '"headline": "' . get_the_title() . '",';
        if ( $image ) {
          $html .= '"image": {';
            $html .= '"@type": "ImageObject",';
            $html .= '"url": "' . $image[0] . '",';
            $html .= '"height": ' . $image[1] . ',';
            $html .= '"width": ' . $image[2];
          $html .= '},';
        }
        $html .= '"datePublished": "' . get_the_date('c') . '",';
        $html .= '"dateModified": "' . get_the_modified_date('c') . '",';
        $html .= '"author": {';
          $html .= '"@type": "Person",';
          $html .= '"name": "' . get_the_author() . '"';
        $html .= '},';
        $html .= '"publisher": {';
          $html .= '"@type": "Organization",';
          $html .= '"name": "' . get_bloginfo('name') . '",';
          $html .= '"logo": {';
          $html .= '"@type": "ImageObject",';
          $html .= '"url": "' . $logo . '"';
          $html .= '}';
        $html .= '}';
        if ( $excerpt ) $html .= ', "description": "' . esc_attr($excerpt) . '"';
      $html .= '}';
    // Close script
    $html .= '</script>';
    echo $html;
  }
}
add_action('wp_head', 'simplepress_json_ld_article');

// JSON Custom Logo Customizer
function simplepress_custom_logo($wp_customize) {
    $wp_customize->add_section(
        'sp_custom_logo_section',
        array(
            'title'     => __('JSON Schema Default Logo'),
        )
    );
    $wp_customize->add_setting(
        'spcustomlogo',
        array(
            'default' => '',
			'transport' => 'postMessage',
            'sanitize_callback' => '',
        )
    );
    $wp_customize->add_control(
        'spcustomlogo',
        array(
		    'description' => __('Example: https://yoursite.com/images/logo.png'),
            'label' => __('Add Default Logo URL for JSON Schema validation when the custom logo is not set yet'),
            'section' => 'sp_custom_logo_section',
            'type' => 'text',
        )
    );
}
add_action('customize_register', 'simplepress_custom_logo');