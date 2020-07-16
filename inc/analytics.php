<?php
/**
 * Google Analytics ID
 *
 * @package SimplePress
 */

/**
 * Add Alternative Analytics ID to wp_footer()
 */
function custom_alternative_analytics(){
    $galite_analytics = wp_kses_post(get_theme_mod('idanalytics'));
    if ($galite_analytics):?>
<script id="alternative-analytics">(function(e,t,n,i,s,a,c){e[n]=e[n]||function(){(e[n].q=e[n].q||[]).push(arguments)}
;a=t.createElement(i);c=t.getElementsByTagName(i)[0];a.async=true;a.src=s
;c.parentNode.insertBefore(a,c)
})(window,document,"galite","script","<?php bloginfo('wpurl'); ?>/wp-content/themes/simplepress/inc/js/ga-lite.min.js");galite('create','<?php echo $galite_analytics; ?>','auto');galite('send','pageview');galite('set','anonymizeIp',true);document.getElementById('alternative-analytics').innerHTML = "";</script>
<?php endif;
}
add_action('wp_footer', 'custom_alternative_analytics', 999);

// ID Analytics Customizer
function id_analytics_customizer($wp_customize) {
    $wp_customize->add_section(
        'id_analytics_section',
        array(
            'title'     => __('Alternative Analytics'),
        )
    );
    $wp_customize->add_setting(
        'idanalytics',
        array(
            'default' => '',
			'transport' => 'postMessage',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    $wp_customize->add_control(
        'idanalytics',
        array(
		    'description' => __('This is customized Google Analytics script in your server. It is GDPR compliant with "anonymizeIp".'),
            'label' => __('Add Your Google Analytics ID. Example: UA-XXXXXXXXX-X'),
            'section' => 'id_analytics_section',
            'type' => 'text'
        )
    );
}
add_action('customize_register', 'id_analytics_customizer');