<?php
/**
 * Get GDPR Cookie Box
 * 
 * @package SimplePress
 */

/**
 * GDPR Cookie Box
 */

// GDPR Cookie Box Script
function simplepress_cookies_script(){ 
  // GDPR Cookie Box Settings
  $gdpr_notes = wp_kses_post(get_theme_mod('gdprnotes'));
  $gdpr_link = wp_kses_post(get_theme_mod('gdprlink'));
  if ($gdpr_link):?>
<script id="gdpr">
/* Cookies Vs4 - Original work Copyright (c) 2018 Petya Mladenova */!function(){var e=function(e,t,o){this.text=e,this.acceptText=t,this.denyText=o,this.element=null,this.init()};e.prototype={init:function(){this.create(),this.load(),this.actions()},load:function(){null===localStorage.getItem("cookie-box-accepted")&&this._show()},actions:function(){var e=document.querySelector("#cookie-box-accept"),t=document.querySelector("#cookie-box-deny"),o=this;e.addEventListener("click",function(e){e.preventDefault(),localStorage.setItem("cookie-box-accepted","yes"),o._hide()},!1),t.addEventListener("click",function(e){e.preventDefault(),localStorage.setItem("cookie-box-deny","no"),o._hide()},!1)},create:function(){var e=document.createElement("div");this.element=e;var t="<div id='cookie-box-wrap'><div id='cookie-box-text'>"+this.text+"</div>";e.id="cookie-box",t+="<div id='cookie-box-btns'><button type='button' id='cookie-box-accept'>"+this.acceptText+"</button>",t+="<button type='button' onclick=eraseCookieFromAllPaths() id='cookie-box-deny' class='mytooltip'>"+this.denyText+"<span class='mytooltext'><?php echo $gdpr_notes; ?></span></button></div>",t+="</div>",e.innerHTML=t,document.body.appendChild(e)},_show:function(){var e=this;e.element.style.display="block",setTimeout(function(){e.element.className="visible"},500)},_hide:function(){var e=this;e.element.className="",setTimeout(function(){e.element.style.display="none"},500)}},document.addEventListener("DOMContentLoaded",function(){new e("This website uses analytics without tracking &laquo;cookies&raquo;. Respect Do Not Track/AnonymizeIP. Get informed from <a href='<?php echo $gdpr_link; ?>' rel='noopener' target='_blank'>the privacy statement</a> page.<br /> <span id='open'><svg height='95' width='35'><text x='0' y='0' fill='#ff4500' transform='rotate(90 1,10)'>Cookies</text>Your browser does not support inline SVG.</svg></span> Do you love cookies?","Yes","No")})}();var sheet=function(){var e=document.createElement("style");return e.appendChild(document.createTextNode("#cookie-box{position:fixed;bottom:0;left:0;width:99%;max-width:290px;min-height:3em;background:#f1f1f1;border:dotted 1px #555;color:#555;z-index:10000000000;display:none;transition:all .5s ease-in;transform:scaleY(0)}#cookie-box.visible{transform:scaleY(1)}#cookie-box{margin-left:-293px}#cookie-box:hover{margin-left:1px}#cookie-box-wrap{margin:0 auto;max-width:285px;text-align:center}#cookie-box-text{font-size:18px;padding:17px 2px 7px;margin-bottom:5px;font-style:italic}#cookie-box-text a{color:#ff4500;text-decoration:underline;text-decoration-style:dotted}#cookie-box-btns{margin:15px auto}#cookie-box-btns button{border-radius:1px;border:none;padding:13px 23px;font-size:15px;line-height:17px;color:#fff;text-transform:uppercase;cursor:pointer;margin-right:7px;transition:all .5s ease-in}#cookie-box-btns button:hover{background-color:gray}button#cookie-box-accept{background:olivedrab}button#cookie-box-deny{background:#ff4500}#open{float:right;margin-top:-65px;margin-right:-40px;font-size:27px;cursor:all-scroll;background:#f1f1f1;border:dotted 1px #555;border-left:none}.mytooltip{position:relative}.mytooltip .mytooltext{visibility:hidden;transition:all .5s ease-in;max-width:280px;background-color:gray;font-family:'Kelson',Arial;font-size:19px;line-height:22px;font-style:italic;color:#fff;text-align:center;text-transform:none;border-radius:1px;padding:19px 0;position:fixed;z-index:1;bottom:65px;left:5px}.mytooltip:hover .mytooltext{visibility:visible}@media screen and (max-width:375px){#cookie-box{display:none!important}}")),document.head.appendChild(e),e.sheet}();function eraseCookieFromAllPaths(e){var o=location.pathname.split("/"),t=" path=";document.cookie=e+"=; expires=Thu, 01-Jan-1970 00:00:01 GMT;";for(var a=0;a<o.length;a++)t+=("/"!=t.substr(-1)?"/":"")+o[a],document.cookie=e+"=; expires=Thu, 01-Jan-1970 00:00:01 GMT;"+t+";"/*,alert("The following cookies will be deleted: "+document.cookie)*/,location.reload(true)};document.getElementById('gdpr').innerHTML = "";
</script>
<?php endif;
}
add_action('wp_footer', 'simplepress_cookies_script', 999);

// GDPR Cookie Box Customizer
function simplepress_gdpr_cookie_customizer($wp_customize) {
    $wp_customize->add_section(
        'gdprnotes_section',
        array(
            'title' => __('GDPR Cookie Box'),
            'priority' => 10,
        )
    );
    $wp_customize->add_setting(
        'gdprnotes',
        array(
            'default' => 'By clicking this button, you waive nonexistent cookies. Your satisfaction from staying on this site is valuable.',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    $wp_customize->add_setting(
        'gdprlink',
        array(
            'default' => '/privacy-policy/',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    $wp_customize->add_control(
        'gdprnotes',
        array(
            'label' => __('Add your custom text (25 words)'),
			'description' => __('Replace the default text below:'),
            'section' => 'gdprnotes_section',
			'settings' => 'gdprnotes',
            'type' => 'textarea',
        )
    );
    $wp_customize->add_control(
        'gdprlink',
        array(
            'label' => __('Add your privacy policy page URL for "the privacy statement" link'),
			'description' => __('Replace the default link below:'),
            'section' => 'gdprnotes_section',
			'settings' => 'gdprlink',
            'type' => 'text',
        )
    );
}
add_action('customize_register', 'simplepress_gdpr_cookie_customizer');