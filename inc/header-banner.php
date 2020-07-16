<?php
/**
 * SimplePress Header Banner
 *
 * @package SimplePress
 */

/**
 * Custom Header Banner Customizer
 */
function header_banner_customizer($wp_customize) {
    $wp_customize->add_section(
        'header_banner_section',
        array(
            'title'     => __('Header Banner'),
        )
    );
    $wp_customize->add_setting(
        'header_banner',
        array(
            'default' => esc_html__('Banner code'),
			'transport' => 'postMessage',
            'sanitize_callback' => '',
        )
    );
    $wp_customize->add_control(
        'header_banner',
        array(
            'label' => __('Add your banner code:'),
            'section' => 'header_banner_section',
            'type' => 'textarea',
        )
    );
}
add_action('customize_register', 'header_banner_customizer');