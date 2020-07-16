<?php
/**
 * Get SEO Friendly Search Form
 * 
 * @package SimplePress
 */

/**
 * SEO Friendly Function
 */
function simplepress_change_search_url() {
    if (is_search() && ! empty($_GET['s'])) {
        wp_redirect( home_url("/search/") . urlencode(get_query_var('s')));
        exit();
    }   
}
add_action('template_redirect', 'simplepress_change_search_url');