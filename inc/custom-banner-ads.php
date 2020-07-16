<?php
/**
 * SimplePress Custom Banner Ads
 *
 * @package SimplePress
 */


/**
 * Add Custom Banner Ads Before/After Post Content
 */
function simplepress_custom_ads_before_after_post($content){
	if (is_single()) {
	    if (function_exists('customcode_customizer') && ($custom_ads_before = get_theme_mod('customadsbefore'))) {
        $custom_ads_before = get_theme_mod('customadsbefore');
		$beforecontent = '<div class="custom-code lazydefer">' . $custom_ads_before . '</div>';
		}
		if (function_exists('customcode_customizer') && ($custom_ads_after = get_theme_mod('customadsafter'))) {
		$custom_ads_after = get_theme_mod('customadsafter');
		$aftercontent = '<div class="custom-code lazydefer">' . $custom_ads_after . '</div>';
		}
    $fullcontent = $beforecontent . $content . $aftercontent;
    } else {
        $fullcontent = $content;
    }
    return $fullcontent;
}
add_filter('the_content', 'simplepress_custom_ads_before_after_post', 1);

// Custom Ads Customizer
function customcode_customizer($wp_customize) {
    $wp_customize->add_section(
        'customcode_section',
        array(
            'title'     => __('Custom Banner Ads'),
        )
    );
    $wp_customize->add_setting(
        'customadsbefore',
        array(
            'default' => esc_html__('Banner code or text ad'),
			'transport' => 'postMessage',
            'sanitize_callback' => '',
        )
    );
    $wp_customize->add_setting(
        'customadsafter',
        array(
            'default' => esc_html__('Banner code or text ad'),
			'transport' => 'postMessage',
            'sanitize_callback' => '',
        )
    );
    $wp_customize->add_control(
        'customadsbefore',
        array(
            'label' => __('Add your banner/ads code or text before the post content:'),
            'section' => 'customcode_section',
            'type' => 'textarea',
        )
    );
    $wp_customize->add_control(
        'customadsafter',
        array(
            'label' => __('Add your banner/ads code or text after the post content:'),
            'section' => 'customcode_section',
            'type' => 'textarea'
        )
    );
}
add_action('customize_register', 'customcode_customizer');