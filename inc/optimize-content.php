<?php
/**
 * Get Optimized Content
 * 
 * @package SimplePress
 */

/**
 * Content Functions
 */

/**
 * WP Log Errors
 */
function simplepress_wordpress_errors(){
  return 'Error!';
}
add_filter( 'login_errors', 'simplepress_wordpress_errors' );

/**
 * Enabled Post Excerpt Meta Box Fields
 */
function simplepress_enable_custom_fields_per_default($hidden){
    foreach ($hidden as $i => $metabox){
        if ('postexcerpt' == $metabox){
            unset ($hidden[$i]);
        }
    }
    return $hidden;
}
add_filter('default_hidden_meta_boxes', 'simplepress_enable_custom_fields_per_default', 20, 1);

/**
 * Remove WP embed script
 */
function simplepress_stop_loading_wp_embed() {
	if (!is_admin()) {
		wp_deregister_script('wp-embed');
	}
}
add_action('init', 'simplepress_stop_loading_wp_embed');

/**
 * Remove type='text/javascript'
 */
function simplepress_clean_script_tag($input) {
$input = str_replace("type='text/javascript' ", '', $input);
return str_replace("'", '"', $input);
}
add_filter('script_loader_tag', 'simplepress_clean_script_tag');

/**
 * Async JavaScript with exclude jQuery.
 */
if (!is_admin()) {
    add_filter('script_loader_tag', function ($tag, $handle) {    
        if (strpos($tag, "jquery.js") || strpos($tag, "admin-bar.min.js")) {
            return $tag;
        }
        return str_replace(' src', ' async src', $tag);
    }, 10, 2 );
}

/**
 * Custom Scripting to Move JavaScript from the Head to the Footer
 */
function simplepress_remove_head_scripts() { 
   remove_action('wp_head', 'wp_print_scripts'); 
   remove_action('wp_head', 'wp_print_head_scripts', 9); 
   remove_action('wp_head', 'wp_enqueue_scripts', 1);

   add_action('wp_footer', 'wp_print_scripts', 5);
   add_action('wp_footer', 'wp_enqueue_scripts', 5);
   add_action('wp_footer', 'wp_print_head_scripts', 5); 
} 
add_action('wp_enqueue_scripts', 'simplepress_remove_head_scripts');

/** 
 * Remove Query strings from Static Resources 
 */
function simplepress_remove_script_version( $src ){ 
    if (strpos($src, 'simplepress-style-css') === false) { 
        $parts = explode( '?', $src ); return $parts[0]; 
    } else { return $src; } 
} 
add_filter( 'script_loader_src', 'simplepress_remove_script_version', 15, 1 ); 
add_filter( 'style_loader_src', 'simplepress_remove_script_version', 15, 1 );

/**
 * HTML minify
 */
add_action('get_header', 'simplepress_html_minify_start');
function simplepress_html_minify_start() {
    ob_start('simplepress_html_minyfy_finish');
}
function simplepress_html_minyfy_finish( $html ) {
  // $html = preg_replace('/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html);
  $html = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $html);
  $html = str_replace(array("\r\n", "\r", "\n", "\t"), '', $html);
  $html = str_replace(' />', '/>', $html);
  while ( stristr($html, '  '))
     $html = str_replace('  ', ' ', $html);
 return $html;
}