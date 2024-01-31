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
$pqrc_countries = array(
        'none',
        __('Afghanistan','ppost_to_qrcode'),
        __('Bangladesh','ppost_to_qrcode'),
        __('India','ppost_to_qrcode'),
        __('Japan','ppost_to_qrcode')
    );
function pqrc_init(){
	global $pqrc_countries;
	$pqrc_countries = apply_filters('edit_country',   $pqrc_countries);
}
add_action('init','pqrc_init');

function wordcount_load_textdomain() {
	load_plugin_textdomain( 'ppost_to_qrcode', false, dirname( __FILE__ ) . '/languages' );
}

function pqrc_display_qr_code( $content ) {
	$current_post_id    = get_the_ID();
	$current_post_title = get_the_title( $current_post_id );
	$current_post_type  = get_post_type( $current_post_id );
	$exculted_post_type = apply_filters( 'pqrc_excluded_post_types', array() );
	if ( in_array( $current_post_type, $exculted_post_type ) ) {
			return $content;
	}
	$height 		  = get_option('pqrc_height');
	$width  		  = get_option('pqrc_width');
	$height           = $height ? $height : 185;
	$width            = $width ? $width : 185;
	$diamantion       = apply_filters( 'pqrc_qrcode_dimension', "{$width}*{$height}" );
	$image_attribute  = apply_filters( 'pqrc_image_attribute', 'null' );
	$current_post_url = urlencode( get_the_permalink( $current_post_id ) );
	$image_src        = sprintf( 'https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s', $diamantion, $current_post_url );
	$content         .= sprintf( '<div %s class="qrcode"><img src="%s" alt="%s" /></div>', $image_attribute, $image_src, $current_post_title );
	return $content;
}

add_filter( 'the_content', 'pqrc_display_qr_code' );
function pqrc_settings_init() {
	add_settings_section('pqrc_section', __('Post To Qr code','ppost-to-qrcode'),'pqrc_section_callback','general');
	add_settings_field( 'pqrc_height', __( 'QR Code Hight', 'ppost_to_qrcode' ), 'pqrc_display', 'general','pqrc_section' ,array('pqrc_height'));
	add_settings_field( 'pqrc_width', __( 'QR Code Width', 'ppost_to_qrcode' ), 'pqrc_display', 'general','pqrc_section', array('pqrc_width') );
	add_settings_field( 'pqrc_extra', __( 'QR Code extra', 'ppost_to_qrcode' ), 'pqrc_display', 'general','pqrc_section', array('pqrc_extra') );
	add_settings_field( 'pqrc_toggle', __( 'Toggle', 'ppost_to_qrcode'), 'pqrc_display_toggle_field','general','pqrc_section');
	add_settings_field( 'pqrc_select', __( 'Dropdown', 'ppost_to_qrcode' ), 'pqrc_display_select', 'general','pqrc_section' );
	add_settings_field( 'pqrc_cheakbox', __( 'Cheakbox', 'ppost_to_qrcode' ), 'pqrc_display_cheakbox', 'general','pqrc_section' );
	register_setting( 'general', 'pqrc_height', array( 'sanitize_callback' => 'esc_attr' ) );
	register_setting( 'general', 'pqrc_width', array( 'sanitize_callback' => 'esc_attr' ) );
	register_setting( 'general', 'pqrc_extra', array( 'sanitize_callback' => 'esc_attr' ) );
	register_setting( 'general', 'pqrc_select', array( 'sanitize_callback' => 'esc_attr' ) );
	register_setting( 'general', 'pqrc_cheakbox' );
	register_setting( 'general', 'pqrc_toggle' );
}

function pqrc_display_toggle_field() {
   
    echo '<div id="toggle1"></div>';

}
function pqrc_display_cheakbox() {
    $option = get_option('pqrc_cheakbox');
	global $pqrc_countries;

    foreach ($pqrc_countries as $country) {
        $selected = '';
        if (is_array($option)&&in_array($country, $option)) {
            $selected = 'checked';
        }
        printf('<input type="checkbox" name="pqrc_checkbox[]" value="%s" %s />%s <br/>', $country, $selected, $country);
    }
}

function pqrc_display_select() {
    $option = get_option('pqrc_select');
    global $pqrc_countries;
	
    echo '<select id="pqrc_select" name="pqrc_select">';

    foreach ($pqrc_countries as $country) {
        $selected = ($option === $country) ? 'selected="selected"' : '';
        printf('<option value="%s" %s>%s</option>', $country, $selected, $country);
    }

    echo '</select>';
}

function pqrc_section_callback() {
    echo "<p>" . __('Setting For post to qr plugin', 'ppost_to_qrcode') . "</p>";
}

function pqrc_display($args) {
    $height = get_option( $args[0]);
    printf( "<input type='text' id='%s' name='%s' value='%s'/>", $args[0], $args[0], $height );
}

add_action( 'admin_init', 'pqrc_settings_init' );

function pqrc_assets($screen){
	if ('options-general.php' == $screen) {
        wp_enqueue_script('jquery');
        wp_enqueue_style('pqrc-minitoggle-css', plugin_dir_url(__FILE__) . 'assets/css/minitoggle.css');
        wp_enqueue_script('pqrc-minitoggle-js', plugin_dir_url(__FILE__) . 'assets/js/minitoggle.js', array('jquery'), '1.0', true);
        wp_enqueue_script('pqrc-main-js', plugin_dir_url(__FILE__) . 'assets/js/pqrc-main.js', array('jquery', 'pqrc-minitoggle-js'), time(), true);
    }

}

add_action('admin_enqueue_scripts', 'pqrc_assets');