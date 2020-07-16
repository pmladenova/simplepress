<?php
/**
 * Get Custom Social Buttons
 *
 * @package SimplePress
 */


/**
 * Social share buttons
 */
function simplepress_custom_social_sharing_buttons($content) {
	global $post;
	if( is_single() ){
	
		// Get current page URL 
		$customURL = urlencode(get_permalink());
 
		// Get current page title
		$customTitle = str_replace( ' ', '%20', get_the_title());
		
		// Get Post Thumbnail for pinterest
		$customThumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
 
		// Construct sharing URL
		$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$customURL;
		$twitterURL = 'https://twitter.com/intent/tweet?text='.$customTitle.'&amp;url='.$customURL;
		$linkedInURL = 'https://www.linkedin.com/shareArticle?mini=true&amp;url='.$customURL.'&amp;title='.$customTitle;
 
		// Based on popular demand added Pinterest too
		$pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$customURL.'&amp;media='.$customThumbnail[0].'&amp;description='.$customTitle;
		
		// Via Email
		$emailURL = 'mailto:?subject='.$customTitle.'&amp;body='.$customURL;
		// Print Post
		$printURL = 'javascript:window.print()';
		
		// SVG Icons spfonts
		$facebookIcon = '<i class="icon-facebook"></i>';
		$twitterIcon = '<i class="icon-twitter"></i>';
		$linkedinIcon = '<i class="icon-linkedin"></i>';
		$pinterestIcon = '<i class="icon-pinterest"></i>';
		$emailIcon = '<i class="icon-email"></i>';
		$printIcon = '<i class="icon-print"></i>';
 
		// Add sharing button at the end of article content
		$content .= '<!-- Custom social sharing -->';
		$content .= '<div class="clear"></div>';
		$content .= '<div class="custom-social">';
		$content .= '<a class="custom-link custom-facebook" href="'.$facebookURL.'" rel="noopener" target="_blank">'.$facebookIcon.'</a>';
		$content .= '<a class="custom-link custom-twitter" href="'.$twitterURL.'" rel="noopener" target="_blank">'.$twitterIcon.'</a>';
		$content .= '<a class="custom-link custom-linkedin" href="'.$linkedInURL.'" rel="noopener" target="_blank">'.$linkedinIcon.'</a>';
		$content .= '<a class="custom-link custom-pinterest" href="'.$pinterestURL.'" rel="noopener" target="_blank">'.$pinterestIcon.'</a>';
		$content .= '<a class="custom-link custom-email" href="'.$emailURL.'">'.$emailIcon.'</a>';
		$content .= '<a class="custom-link custom-print" href="'.$printURL.'">'.$printIcon.'</a>';
		$content .= '</div>';
		
		return $content;
	}else{
		// if not a article then don't include sharing button
		return $content;
	}
};
add_filter( 'the_content', 'simplepress_custom_social_sharing_buttons', 2);

/**
 * Add Social Share CSS wp_head()
 */
function simplepress_social_share_css() {
    if( is_single() ) {
    echo "<style id='simplepress-social-share-css' type='text/css'>.custom-link{margin:1px 9px 9px 0;padding:9px;line-height:0;border:1px dotted #181818!important;cursor:pointer;-moz-transition:all .3s ease-in;-webkit-transition:all .3s ease-in;-o-transition:all .3s ease-in;transition:all .3s ease-in;background-clip:padding-box;display:inline-block}.custom-twitter:active,.custom-twitter:hover{background:#a3e1f8}.custom-facebook:active,.custom-facebook:hover{background:#c0c9dd}.custom-pinterest:active,.custom-pinterest:hover{background:#e9afb6}.custom-linkedin:active,.custom-linkedin:hover{background:#add2e0}.custom-email:active,.custom-email:hover{background:#d4d1a5}.custom-print:active,.custom-print:hover{background:#e4e4e4}.custom-social{margin:30px 0}</style>";
	}
}
add_action( 'wp_head', 'simplepress_social_share_css', 999 );