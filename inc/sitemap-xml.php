<?php
/**
 * Get Sitemap XML
 *
 * @package SimplePress
 */


/**
 * SiteMap XML
 */
   
function simplepress_create_sitemap() {
    $postsForSitemap = get_posts( array(
        'numberposts' => -1,
        'orderby'     => 'modified',
        'post_type'   => array( 'post', 'page' ),
        'order'       => 'DESC'
    ) );
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
    $sitemap .= "\n" . '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">' . "\n"; 
    $sitemap .= "\t" . '<url>' . "\n" .
        "\t\t" . '<loc>' . esc_url( home_url( '/' ) ) . '</loc>' .        
        "\n\t" . '</url>' . "\n";
    foreach( $postsForSitemap as $post ) {
        setup_postdata( $post );   
        $postdate = explode( " ", $post->post_modified );   
        $sitemap .= "\t" . '<url>' . "\n" .
            "\t\t" . '<loc>' . get_permalink( $post->ID ) . '</loc>' .            
            "\n\t" . '</url>' . "\n";
    }     
    $sitemap .= '</urlset>';     
    $fp = fopen( ABSPATH . "sitemap.xml", 'w' );
    fwrite( $fp, $sitemap );
    fclose( $fp );
}
add_action( 'save_post', 'simplepress_create_sitemap' );

// Simply call auto ping function 
$sitemapUrl = get_bloginfo('wpurl') . '/sitemap.xml';
// cUrl handler to ping the Sitemap submission URLs for Search Engines…
function myCurl($url){
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  return $httpCode;
}

// Google
$url = "https://www.google.com/webmasters/sitemaps/ping?sitemap=".$sitemapUrl;
// $data = file_get_contents("https://www.google.com/webmasters/tools/ping?sitemap={$sitemapUrl}");
// $status = ( strpos($data,"Sitemap Notification Received") !== false ) ? "OK" : "ERROR";
// echo "<p>Submitting Google Sitemap: {$status} for $url\n</p>";

// Bing
$url = "https://www.bing.com/ping?sitemap=".$sitemapUrl;
// $returnCode = myCurl($url);
// echo "<p>Bing / MSN Sitemaps has been pinged (return code: $returnCode).</p>";