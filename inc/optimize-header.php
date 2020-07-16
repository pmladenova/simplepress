<?php
/**
 * Get Optimize Header
 * 
 * @package SimplePress
 */

/**
 * Header clean up
 */
function simplepress_head_cleanup() {
    // Remove api
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

    // Remove Emoji
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	add_filter('emoji_svg_url', '__return_false');
	// Filter to remove TinyMCE emojis
	add_filter('tiny_mce_plugins', 'disable_emojicons_tinymce');

    // Remove RSD Links
    remove_action('wp_head', 'rsd_link');

    // Remove Shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

    // Remove WP version
    remove_action('wp_head', 'wp_generator');

    // Remove WLManifest Link
    remove_action('wp_head', 'wlwmanifest_link');

    // Removes prev and next article links
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

    // Disable XML-RPC
    add_filter('xmlrpc_enabled', '__return_false');

    // Disable Auto Update Theme
    add_filter('auto_update_theme', '__return_false');
	}
add_action('init', 'simplepress_head_cleanup');

/**
 * Disable Emoji tinymce
 * Ref: https://wordpress.stackexchange.com/questions/185577/disable-emojicons-introduced-with-wp-4-2
 */
function disable_emojicons_tinymce($plugins) {
  if (is_array($plugins)) {
    return array_diff($plugins, array('wpemoji'));
  } else {
    return array();
  }
}

/**
 * Disable self ping
 */
function simplepress_disable_self_ping(&$links) {
    foreach ($links as $l => $link)
        if (0 === strpos($link, get_option('home')))
            unset($links[$l]);
}
add_action('pre_ping', 'simplepress_disable_self_ping');

/**
 * Disable Dashicons on Front-end
 */
function simplepress_dequeue_dashicon() {
     if (current_user_can('update_core')) {
         return;
     }
     wp_deregister_style('dashicons');
}
add_action('wp_enqueue_scripts', 'simplepress_dequeue_dashicon');

/**
 * If-Modified-Since HTTP header
 * Set the Last-Modified header for visitors on the front-page 
 * based on when a post was last modified.
 * Ref: https://wordpress.stackexchange.com/questions/257111/setting-last-modified-http-header-on-static-home-page
 */
add_action('template_redirect', function() use (&$wp) {
    // Only front-page (Home Page)
    if (!is_front_page()) return;
 
    // Don't add it if there's e.g. 404 error (similar as the error check for feeds)
    if (!empty( $wp->query_vars['error'])) return;
 
    // Don't override the last-modified header if it's already set
    $headers = headers_list();
    if (!empty($headers['last-modified'])) return;
 
    // Get last modified post using the WP built-in function get_lastpostmodified
    $last_modified = mysql2date( 'D, d M Y H:i:s', get_lastpostmodified('GMT'), false);
 
    // Add last modified header
   if ($last_modified && ! headers_sent()) {
       header("Last-Modified: " . $last_modified . ' GMT');
    }
}, 1);

/**
 * Add Custom Home Page Meta Description
 */
function simplepress_homepage_meta_description() {
    if (is_home() || is_front_page()) {
	    $home_des = wp_kses_post(get_theme_mod('homedescr'));
	    $home_tags = wp_kses_post(get_theme_mod('hometags'));
        if ($home_des) {
	       echo '<meta name="description" content="' . $home_des . '" />' . "\n";
	    } else {
		   echo '<meta name="description" content="' . get_bloginfo('description') . '" />' . "\n"; 
		}
	if ($home_tags) {
        echo '<meta name="keywords" content="' . esc_attr($home_tags) . '" />' . "\n";
	    } else {
	    if ($meta_tag = meta_post_tag()) {
	       echo '<meta name="keywords" content="' . esc_attr($meta_tag) . '" />' . "\n";
		   }	   
	    } 
	}
}
add_action('wp_head', 'simplepress_homepage_meta_description', 1);

// Meta Description Customizer
function homedescr_customizer($wp_customize) {
    $wp_customize->add_section(
        'homedescr_section',
        array(
            'title'     => __('Home Meta Description&amp;Tags'),
        )
    );
    $wp_customize->add_setting(
        'homedescr',
        array(
            'default' => '',
			'transport' => 'postMessage',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    $wp_customize->add_setting(
        'hometags',
        array(
            'default' => '',
			'transport' => 'postMessage',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    $wp_customize->add_control(
        'homedescr',
        array(
            'label' => __('Add Home meta description (155 characters).'),
            'section' => 'homedescr_section',
            'type' => 'textarea',
        )
    );
    $wp_customize->add_control(
        'hometags',
        array(
		    'description' => __('web design, website design, website'),
            'label' => __('Add Home meta tags (3 keywords max with commas and spaces). Example:'),
            'section' => 'homedescr_section',
            'type' => 'textarea',
        )
    );
}
add_action('customize_register', 'homedescr_customizer');

/**
 * Get Single Page Meta Description and Tags
 */
function simplepress_single_page_meta_description() {
if (is_single() || is_page()) {
	global $post;
    $number_of_words = apply_filters('single_description_words', 21);
    $single_excerpt = wp_trim_words(strip_tags(strip_shortcodes($post->post_content)), $number_of_words, '...');
	if ($single_excerpt) {
	   echo '<meta name="description" content="' . esc_html($single_excerpt) . '" />' . "\n";
	   }
    if ($meta_tag = meta_post_tag()) {
       echo '<meta name="keywords" content="' . esc_attr($meta_tag) . '" />' . "\n";
	   } else {
	   if ($meta_section = meta_post_section()) {
	      echo '<meta name="keywords" content="' . esc_attr($meta_section) . '" />' . "\n";
		  }	   
	   }
	}
}
add_action('wp_head', 'simplepress_single_page_meta_description', 1);

// add meta tags
function meta_post_tag() {
  $tags = get_the_tags();
  $tagnames = array();
  if ($tags) {
    foreach ($tags as $tag) {
      $tagnames[] = $tag->name;
    }
    return implode(', ', $tagnames);
  }
  return false;
}

// if tags not defined - get categories as meta tags
function meta_post_section() {
  $categories = get_the_category();
  if (count($categories)>0) {
    if ($categories[0]->name !=  __('Uncategorized')) {
      return $categories[0]->name;
    } 
  }
  return false;
}

/**
 * Meta Robots Tag
 */
function simplepress_add_meta_robots_tag() {
    if ( is_tag() ) {
    echo '<meta name="robots" content="noindex, follow" />' . "\n";
    } elseif ( is_archive() ) {
    echo '<meta name="robots" content="noindex, follow" />' . "\n";
    } elseif ( is_search() ) {
    echo '<meta name="robots" content="noindex, follow" />' . "\n";
    } elseif ( is_paged() ) {
    echo '<meta name="robots" content="noindex, follow" />' . "\n";
    } else {
    echo '<meta name="robots" content="index, follow" />' . "\n";
    }
}
add_action('wp_head', 'simplepress_add_meta_robots_tag', 1);

/**
 * Add Google and Bing site verification
 */
function google_bing_site_verification() {
    if (is_home() || is_front_page()) {
	   $google_ver = wp_kses_post(get_theme_mod('googlever'));
	   $bing_ver = wp_kses_post(get_theme_mod('bingver'));
       if ($google_ver) {
          echo '<meta name="google-site-verification" content="' . $google_ver . '" />' . "\n";
	   }
       if ($bing_ver) {
	      echo '<meta name="msvalidate.01" content="' . $bing_ver . '" />' . "\n";
	   }
    }
}
add_action('wp_head', 'google_bing_site_verification', 1);

// Google and Bing Customizer
function google_bing_customizer($wp_customize) {
    $wp_customize->add_section(
        'google_bing_section',
        array(
            'title'     => __('Google&amp;Bing Site Verification'),
        )
    );
    $wp_customize->add_setting(
        'googlever',
        array(
            'default' => '',
			'transport' => 'postMessage',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    $wp_customize->add_setting(
        'bingver',
        array(
            'default' => '',
			'transport' => 'postMessage',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    $wp_customize->add_control(
        'googlever',
        array(
            'label' => __('Add Google verification code. Example: X_VHTWH8pd3xkW7CyUR01csDbpm-6GCfcvqw2ldArLU'),
            'section' => 'google_bing_section',
            'type' => 'text',
        )
    );
    $wp_customize->add_control(
        'bingver',
        array(
            'label' => __('Add Bing verification code. Example: 38055DEDAF46BD7A1EC51EB2B5A8A7FF'),
            'section' => 'google_bing_section',
            'type' => 'text'
        )
    );
}
add_action('customize_register', 'google_bing_customizer');