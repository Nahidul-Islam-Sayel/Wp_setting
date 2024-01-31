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


