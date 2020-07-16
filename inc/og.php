<?php
/**
 * Get Open Graph Protocol
 * 
 * @package SimplePress
 */

/**
 * OG Dostype
 */
function simplepress_doctype_opengraph($output) {
    return $output . ' prefix="og: http://ogp.me/ns#"';
}
add_filter('language_attributes', 'simplepress_doctype_opengraph');

/**
 * OG Site
 */
function simplepress_add_og_site() {
  if (is_home() || is_front_page()) {
    $home_des = wp_kses_post(get_theme_mod('homedescr'));
	  if (empty($home_image) && (function_exists('has_site_icon') && has_site_icon())) {
		  $home_image = get_site_icon_url();
	  } else {
      // or get your wp custom image (icon) 512x512 px
	  $home_image = get_home_url() . '/wp-content/themes/simplepress/img/og-site.jpg';
    }
    echo '<meta property="og:type" content="website" />' . "\n";
    echo '<meta property="og:title" content="' . get_bloginfo('name') . '" />' . "\n";
    if ($home_des) {
        echo '<meta property="og:description" content="' . $home_des . '" />' . "\n";
    } else {
        echo '<meta property="og:description" content="' . get_bloginfo('description') . '" />' . "\n";
    }
    echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '" />' . "\n";
    echo '<meta property="og:image" content="' . $home_image . '" />' . "\n";
    echo '<meta property="og:locale" content="' . get_locale() . '" />' . "\n";
  }
}
add_action('wp_head', 'simplepress_add_og_site', 1);

/**
 * OG Articles
 */
function simplepress_add_og_articles() {
  if (is_single() || is_page()) {
    global $post;
    if(get_the_post_thumbnail($post->ID, 'thumbnail')) {
      $thumbnail_id = get_post_thumbnail_id($post->ID, 'full');
      $thumbnail_object = get_post($thumbnail_id);
      $image = $thumbnail_object->guid;
	} else {
	     $image = '';
	     $output = preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	     if(isset($matches[1][0]) ){
	         $image = $matches[1][0];
	     } else {
	       if(empty($image)) {
	       $image = get_home_url() . '/wp-content/themes/simplepress/img/og-articles.jpg';
	     }
	   }
	}
    $number_of_words = apply_filters('og_description_words', 55);
    $excerpt = wp_trim_words(strip_tags(strip_shortcodes($post->post_content)), $number_of_words, '...');
    // $author_name = get_the_author();
	the_post();
    $author_name = get_the_author();
    rewind_posts();
    echo '<meta property="og:title" content="' . get_the_title() . '" />' . "\n";
    echo '<meta property="og:type" content="article" />' . "\n";
    echo '<meta property="og:locale" content="' . get_locale() . '" />' . "\n";
    echo '<meta property="og:image" content="' . $image . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_html($excerpt) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '" />' . "\n";
    echo '<meta property="article:author" content="' . $author_name . '" />' . "\n";
    if ($og_section = og_post_section()) {
        echo '<meta property="article:section" content="' . esc_attr($og_section) . '" />' . "\n";
    }
    echo '<meta property="article:published_time" content="' . get_post_time('c') . '" />' . "\n";
    echo '<meta property="article:modified_time" content="' . get_the_modified_time('c') . '" />' . "\n";
    if ($og_tag = og_post_tag()) {
        echo '<meta property="article:tag" content="' . esc_attr($og_tag) . '" />' . "\n";
    }
  }
}
add_action('wp_head', 'simplepress_add_og_articles', 1);

// add og sections
function og_post_section() {
  $categories = get_the_category();
  if (count($categories)>0) {
    if ($categories[0]->name !=  __('Uncategorized')) {
      return $categories[0]->name;
    } 
  }
  return false;
}

// add og tags
function og_post_tag() {
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

// Functions Prevent Head
if (defined('JETPACK__VERSION')) { // JetPack
global $jetpack;
remove_action('wp_head', 'jetpack_og_tags'); 
}

if (defined('WPSEO_VERSION')) { // Yoast SEO
global $wpseo_front;
remove_action('wp_head', array($wpseo_front, 'head') ,1);
}

if (defined('AIOSEOP_VERSION')) { // All-In-One SEO
global $aiosp;
remove_action('wp_head', array($aiosp, 'wp_head'));
}