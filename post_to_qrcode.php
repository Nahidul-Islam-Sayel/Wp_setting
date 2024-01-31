<?php
/**
 * Plugin Name: QR Code
 * Description: A tutorial plugin for weDevs Academy
 * Plugin URI: https://tareq.co
 * Author: Nahidul Islam Sayel
 * Author URI: https://NahidulIslamSayel.com
 * Version: 1.0
 * License: GPL2 or later
 * Text Domain: ppost_to_qrcode
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

function philosopy_button($attributes) {
	$default= array(
		'type'=> 'primary',
		'url'=> 'www.google.com',
		'title'=> 'primary buttton',
	);
	$button_attributes = shortcode_atts( $default, $attributes );
    return sprintf(
        '<a class="btn btn--%s full-width" href="%s">%s</a>',
        $button_attributes['type'],
        $button_attributes['url'], 
        $button_attributes['title']
    );
}

add_shortcode('button', 'philosopy_button');



////nested shortcode
function philosopy_button2($attributes, $content = null) {
    $default = array(
        'type'  => 'primary',
        'url'   => 'www.google.com',
        'title' => 'primary button',
    );

    $button_attributes = shortcode_atts($default, $attributes);

    return sprintf(
        '<a class="btn btn--%s full-width" href="%s">%s</a>',
        $button_attributes['type'],
        esc_url($button_attributes['url']),
        do_shortcode($content)
    );
}

add_shortcode('button2', 'philosopy_button2');



function uppercase($attributes,$content=''){
	return strtoupper(do_shortcode($content));
}
add_shortcode('uc','uppercase');


//////Google map

function fmap($attributes) {
    $default = array(
        'place' => 'Dhaka Museum',
        'width' => '800',
        'height' => '500'
    );

    $params = shortcode_atts($default, $attributes);

    $map = <<<EOD
    <div>
        <div>
            <iframe width="{$params['width']}" height="{$params['height']}"
                    src="https://maps.google.com/maps?q={$params['place']}&t=&z=13&ie=UTF8&iwloc=&output=embed"
                    frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
            </iframe>
        </div>
    </div>
EOD;

    return $map;
}

add_shortcode('map', 'fmap');

