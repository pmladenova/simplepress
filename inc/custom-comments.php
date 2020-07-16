<?php
/**
 * SimplePress Custom Comments
 *
 * @package SimplePress
 */

// Init Setup
function simplepress_comments_init() {
    // Add Custom Notes Checkbox CSS
    add_action('wp_head', 'simplepress_custom_notes_checkbox_css', 9999);
    
    // Modify Comments Notes Before
	add_filter('comment_form_defaults', 'simplepress_modify_text_before_comment_form');
	
	// Add Comments Notes Before The Send Button
	add_filter('comment_form_default_fields', 'simplepress_add_text_comment_form');
    add_filter('comment_form_fields', 'simplepress_add_text_comment_form');
    
    // Comment Notes Customizer
    add_action('customize_register', 'simplepress_comment_notes_customizer');
    
    // Disable email, url, cookies checkbox in comment form
    add_filter('comment_form_default_fields', 'simplepress_remove_comment_form_fields');
    add_filter('comment_form_fields', 'simplepress_remove_comment_form_fields');
    
    // Remove Comment Notes Before & After // Not uncomment this line
    //add_filter('comment_form_defaults', 'simplepress_remove_comment_notes_defaults');
    
    // Add Checkbox To Avoid Spam
    add_filter('comment_form_default_fields', 'simplepress_add_input_checkme');
    add_filter('comment_form_fields', 'simplepress_add_input_checkme');
    
    // Verify checkbox
    add_filter('preprocess_comment', 'simplepress_verify_checkbox');
	
	// Remove nofollow
	add_filter('get_comment_author_link', 'simplepress_remove_nofollow');
	
	// Remove IP
	add_filter('pre_comment_user_ip', 'simplepress_remove_commentsip');
	
	// Remove Clickable in Comments Content
    remove_filter('comment_text', 'make_clickable', 9);
	
	// Block Referral URL exploit for Comments
	add_action('check_comment_flood', 'simplepress_verify_comment_referer');
	
	// URL Spam Check
	add_filter('pre_comment_approved', 'simplepress_url_spamcheck', 99, 2);
	
	// Spam Filter
	add_action('init', 'simplepress_drop_bad_comments');
	
	// Require a Minimum Comment Length
	add_filter('preprocess_comment', 'minimal_comment_length');
	
	// SimplePress Avatar
	add_filter('avatar_defaults', 'simplepress_custom_user_avatar');
	
	// Gravatar Alt Tag Fix
	add_filter('get_avatar', 'simplepress_gravatar_alt');
	
	// Default Local Avatar
	add_filter('get_avatar', 'simplepress_custom_avatar', 1, 5);
}
add_action('after_setup_theme', 'simplepress_comments_init');

/**
 * Add Custom Notes Checkbox CSS wp_head()
 */
function simplepress_custom_notes_checkbox_css() {
    if (is_single() || is_page()) {
    echo "<style id='simplepress-custom-checkbox-notes-css' type='text/css'>input[type=checkbox]{margin-right:7px;width:15px}.custom-comment-notes{margin:7px 0;color:#777}</style>";
	}
}

/**
 * Modify Comments Notes Before 
 */
function simplepress_modify_text_before_comment_form($args) {
    if ($custom_notes_above = get_theme_mod('commentnotesabove', 'We respect your privacy and therefore we made sure to disable the email entry field and your IP. -> Go to plugin settings, and replace me with custom notes.')) {
    $args['comment_notes_before'] = '<p class="custom-comment-notes">' . esc_html__($custom_notes_above) . '</p>';
    }
    return $args;
}

/**
 * Add Comments Notes Before The Send Button
 */
function simplepress_add_text_comment_form($args) {
	if ($custom_notes = get_theme_mod('commentnotes', 'Go to plugin settings, and replace me with custom notes.')) {
	$args['comment_notes'] = '<p class="custom-comment-notes">' . __($custom_notes) . '</p>';
	}
   return $args;
}

// Comment Notes Customizer
function simplepress_comment_notes_customizer($wp_customize) {
    $wp_customize->add_section(
        'comment_notes_section',
        array(
            'title'     => __('Add Notes in Comment Form'),
        )
    );
    $wp_customize->add_setting(
        'commentnotesabove',
        array(
            'default' => 'We respect your privacy and therefore we made sure to disable the email entry field and your IP. -> Replace me with custom notes.',
			'transport' => 'postMessage',
            'sanitize_callback' => '',
        )
    );
    $wp_customize->add_setting(
        'commentnotes',
        array(
            'default' => 'Replace me with custom notes',
			'transport' => 'postMessage',
            'sanitize_callback' => '',
        )
    );
    $wp_customize->add_control(
        'commentnotesabove',
        array(
            'label' => __('Modify comments notes before:'),
            'section' => 'comment_notes_section',
            'type' => 'textarea',
        )
    );
    $wp_customize->add_control(
        'commentnotes',
        array(
            'label' => __('Add your comments notes before the send button:'),
            'section' => 'comment_notes_section',
            'type' => 'textarea',
        )
    );
}

/**
 * Disable email, url, cookies checkbox in comment form
 */
 function simplepress_remove_comment_form_fields($fields) {
    if(isset($fields['email'])) unset($fields['email']);
	// if(isset($fields['url'])) unset($fields['url']);
	if(isset($fields['cookies'])) unset($fields['cookies']);
    return $fields;
}

// Remove User Comment IP
function simplepress_remove_commentsip( $comment_author_ip ) {
    return '';
}

/**
 * Remove Comment Notes Before & After
 */
function simplepress_remove_comment_notes_defaults($defaults) {
    if(isset($defaults['comment_notes_before'])) unset($defaults['comment_notes_before']);
	if(isset($defaults['comment_notes_after'])) unset($defaults['comment_notes_after']);
	return $defaults; 
}

/**
 *  Add Checkbox To Avoid Spam
 */
function simplepress_add_input_checkme($fields) {
    $fields['checkbox'] =
	'<p class="comment-form-checkbox"><label for="checkbox"><input type="checkbox" id="checkbox" name="checkbox" value="1" required>I am a real commenter</label></p>';
	return $fields;
}

// Verify checkbox
function simplepress_verify_checkbox($commentdata) {
    if (! isset($_POST['checkbox']) && ! is_user_logged_in())
    	wp_die('<strong>' . __('ERROR: ') . '</strong>' . __('Confirm that you are real.', 'comment-form-checkbox') . ' <br /><a href="javascript:history.back()">' . __('&laquo; Back') . '</a>');
    return $commentdata;
}

/**
 * Remove nofollow
 */
function simplepress_remove_nofollow($string) {
    $string = str_ireplace(' nofollow', '', $string);
    return $string;
}

/**
 * Block Referral URL exploit for Comments
 */
function simplepress_verify_comment_referer() {
    if (!wp_get_referer()) {
        wp_die( __('You cannot post comment at this time, may be you need to enable referrers in your browser.') );
    }
}

/**
 * URL Spam Check
 */
function simplepress_url_spamcheck($approved, $commentdata) {
    return (strlen($commentdata['comment_author_url']) > 30) ? 'spam' : $approved;
}

/**
 * Spam Filter
 */
function simplepress_in_comment_post_like($string, $array) {
    foreach($array as $ref) { if(strstr($string, $ref)) { return true; } }
    return false;
}
function simplepress_drop_bad_comments() {
    if (!empty($_POST['comment'])) {
        $post_comment_content = $_POST['comment'];
        $lower_case_comment = strtolower($_POST['comment']);

        // List of banned words in comments
        // Comments with these words will be auto-deleted
		$bad_comment_content = array( '[url=http', '[url=https', '[link=http', '[link=https', 'http', 'https', 'www', 'link', '@', 'ы', 'I have', 'I like', 'I think', '://', 'viagra', 'xanax', 'tramadol', 'amoxicillin', 'lorazepam', 'adderall', 'dexadrine', 'no prescription', 'oxycontin', 'without a prescription', 'sex pics', 'family incest', 'online casinos', 'online dating', 'cialis', 'best forex', 'russian girls', 'russian brides' );
        if (simplepress_in_comment_post_like($lower_case_comment, $bad_comment_content)) {
            wp_die( __('<strong>&osol;</strong> Your comment uses banned words <strong>&osol;</strong>') . ' <br /><a href="javascript:history.back()">' . __('&laquo; Back') . '</a>');
        }
    }
}

/** 
 * Require a Minimum Comment Length
 */
function minimal_comment_length($commentdata) {
    $minimalCommentLength = 130;
	if (strlen(trim($commentdata['comment_content'])) < $minimalCommentLength) {
		wp_die( __('Your comment is shorter than ' . $minimalCommentLength . ' characters.') . ' <br /><a href="javascript:history.back()">' . __('&laquo; Back') . '</a>');
        }
	return $commentdata;
}

/**
 * Custom User Avatar
 */
function simplepress_custom_user_avatar($avatar_defaults) {
    $useravatar = get_bloginfo('template_directory') . '/inc/img/simplepress-avatar.png';
    $avatar_defaults[$useravatar] = "SimplePress Avatar";
    
    // Remove default avatars
    unset ( $avatar_defaults['mystery'] );
    unset ( $avatar_defaults['blank'] );
    unset ( $avatar_defaults['gravatar_default'] );
    unset ( $avatar_defaults['identicon'] );
    unset ( $avatar_defaults['wavatar'] );
    unset ( $avatar_defaults['monsterid'] );
    unset ( $avatar_defaults['retro'] );
    
    return $avatar_defaults;
}

/**
 * Gravatar Alt Fix
 */
function simplepress_gravatar_alt($text) {
$alt = get_the_author_meta();
$text = str_replace('alt=\'\'', 'alt=\'Avatar '.$alt.'\' title=\'Commenter '.$alt.'\'',$text);
return $text;
}

/**
 * Default Local Avatar
 */
function simplepress_custom_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
    $user = false;
    if ( is_numeric( $id_or_email ) ) {
        $id = (int) $id_or_email;
        $user = get_user_by( 'id', $id );
    } elseif ( is_object( $id_or_email ) ) {
        if ( ! empty( $id_or_email->user_id ) ) {
            $id = (int) $id_or_email->user_id;
            $user = get_user_by( 'id', $id );
        }
    } else {
        $user = get_user_by( 'email', $id_or_email );	
    }
    if ( $user && is_object( $user ) ) {
        if ( $user->data->ID == '1' ) {
            $avatar = get_home_url() . '/wp-content/themes/simplepress/inc/img/simplepress-author-1.png';
			$alt = 'author';
            $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }
        if ( $user->data->ID == '2' ) {
            $avatar = get_home_url() . '/wp-content/themes/simplepress/inc/img/simplepress-author-2.png';
			$alt = 'author';
            $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }
        if ( $user->data->ID == '3' ) {
            $avatar = get_home_url() . '/wp-content/themes/simplepress/inc/img/simplepress-author-3.png';
			$alt = 'author';
            $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }
        if ( $user->data->ID == '4' ) {
            $avatar = get_home_url() . '/wp-content/themes/simplepress/inc/img/simplepress-author-4.png';
			$alt = 'author';
            $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }
        if ( $user->data->ID == '5' ) {
            $avatar = get_home_url() . '/wp-content/themes/simplepress/inc/img/simplepress-author-5.png';
			$alt = 'author';
            $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }
        if ( $user->data->ID == '6' ) {
            $avatar = get_home_url() . '/wp-content/themes/simplepress/inc/img/simplepress-author-6.png';
			$alt = 'author';
            $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }
        if ( $user->data->ID == '7' ) {
            $avatar = get_home_url() . '/wp-content/themes/simplepress/inc/img/simplepress-author-7.png';
			$alt = 'author';
            $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }
        if ( $user->data->ID == '8' ) {
            $avatar = get_home_url() . '/wp-content/themes/simplepress/inc/img/simplepress-author-8.png';
			$alt = 'author';
            $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }
    }
    return $avatar;
}

// Redirect After Comment
add_action( 'set_comment_cookies', function( $comment, $user ) {
    setcookie( 'simplepress_comment_wait_approval', '1' );
}, 10, 2 );

add_action( 'init', function() {
    if( $_COOKIE['simplepress_comment_wait_approval'] === '1' ) {
        setcookie( 'simplepress_comment_wait_approval', null, time() - 3600, '/' );
        add_action( 'comment_form_before', function() {
            echo "<p id='wait_approval' class='center'>Your comment has been sent successfully and wait approval.</p>";
        });
    }
});

add_filter( 'comment_post_redirect', function( $location, $comment ) {
    $location = get_permalink( $comment->comment_post_ID ) . '#wait_approval';
    return $location;
}, 10, 2 );