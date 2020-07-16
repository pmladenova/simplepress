<?php
/**
 * Get Download Function
 * Source:http://remicorson.com/easy-download-media-counter-free/
 * 
 * @package SimplePress
 */
if ( ! defined('ABSPATH') ) {
	die('No direct access allowed');
}

// Globals
global $dwn_base_dir;
$edmc_base_dir = dirname(__FILE__);

global $dwn_prefix;
$dwn_prefix = 'dwn_';

// Add Counter to medias
function dwn_create_field($form_fields, $post) {
	$form_fields['dwn-download-count'] = array(
		'label' => __('Downloads #', 'dwn'),
		'input' => 'text', 
		'value' => get_post_meta($post->ID, '_dwn-download-count', true),
		'helps' => __('Downloads count.'),
	);
   return $form_fields;
}
add_filter("attachment_fields_to_edit", "dwn_create_field", null, 2);

// Save Download Counter 
function dwn_save_field($post, $attachment) {
	if( isset($attachment['dwn-download-count']) ){
		update_post_meta($post['ID'], '_dwn-download-count', $attachment['dwn-download-count']);
	}
	return $post;
}
add_filter('attachment_fields_to_save', 'dwn_save_field', null, 2);

// Shortcodes
// [dwn id="xxx"]Download Media Now![/dwn] 
function dwn_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		"id" => ''
	), $atts));
	return '<a href="'.get_bloginfo('url').'?dwn='.$id.'">'.$content.'</a>';
}
add_shortcode("dwn", "dwn_shortcode"); 

// [dwn_show id="xxx"]
function dwn_shortcode_show($atts) {
	extract(shortcode_atts(array(
		"id" 		=> ''
	), $atts));
	$mime_type = get_post_mime_type($id);
	return __('Downloads', 'dwn').': '.get_post_meta($id, '_dwn-download-count', true).' ('.$mime_type.')';
}
add_shortcode("dwn_show", "dwn_shortcode_show"); 

// Populate Counter
if( !is_admin() ) {
	if( isset($_GET['dwn']) AND is_numeric($_GET['dwn']) ) {
		// Update count
		$count = get_post_meta($_GET['dwn'], '_dwn-download-count', true);
		update_post_meta($_GET['dwn'], '_dwn-download-count', $count+1);
		
		// Call the full URL to the file
		$file = wp_get_attachment_url( $_GET['dwn'] );

		// Get just the file name
		$file_name = basename($file);
		
		if(isset($file)){
		    //Getting the path to work with filesize()
		    $wp_upload_dir      = wp_upload_dir(); 
			$current_upload_dir = $wp_upload_dir['path']; 
			$filepath           = $current_upload_dir.'/'.$file_name;

			// Checking MIME type and setting accordingly
		    switch(strtolower(substr(strrchr($file_name,'.'),1))) {
			    case 'pdf': $mime = 'application/pdf'; break;
			    case 'zip': $mime = 'application/zip'; break;
			    case 'jpeg': $mime = 'image/jpg'; break;
			    case 'jpg': $mime = 'image/jpg'; break;
			    case 'png': $mime = 'image/jpg'; break;
			    default: $mime = 'application/force-download';
			  }
			  // Send headers
			  header('Pragma: public');   // required
			  header('Expires: 0');    // no cache
			  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			  header('Cache-Control: private',false);
			  header('Content-Type: '.$mime);
			  header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
			  header('Content-Transfer-Encoding: binary');
			  //header('Content-Length: '.filesize($filepath));  // provide file size
			  header('Connection: close');
			  readfile($file);    // push it out
			  exit();
		}	
	}
}

// Media Library Add Count Column
function dwn_add_count_column($posts_columns) {
	// Add a new column
	$posts_columns['downloads'] = _x('Downloads', 'downloads_column');
	return $posts_columns;
}
add_filter('manage_media_columns', 'dwn_add_count_column');

// Media Library Populate Count Column 
function dwn_populate_count_column($column_name, $id) {
	$downloads = get_post_meta($id, '_dwn-download-count', true);
	switch($column_name) {
		case 'downloads':
			if ($downloads > 0) {
				echo $downloads;
			} else {
				_e('Not downloaded yet');
			}
			break;
		default:
			break;
	}
}
add_action('manage_media_custom_column', 'dwn_populate_count_column', 10, 2);