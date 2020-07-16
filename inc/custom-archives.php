<?php
/**
 * SimplePress Custom Archives
 *
 * @package SimplePress
 */

/**
 * Function to get archives list with limited months
 */
function simplepress_limit_archives() { 
$simplepress_archives = wp_get_archives(array(
    'type'=>'yearly', 
    'limit'=>'',
	'format'=>'custom',
	'before'=>'<span class="simplepress-archive">',
	'after'=>'</span>',
	'show_post_count'=>true,
    'echo'=>0
));   
return $simplepress_archives; 
}  
// Create a shortcode: [simplepress_custom_archives]
add_shortcode('simplepress_custom_archives', 'simplepress_limit_archives');  
// Enable shortcode execution in text widget
add_filter('widget_text', 'do_shortcode');