<?php
/**
 * Plugin Name: Extensions for Elementor Form
 * Plugin URI: https://eduardovillao.me/extensions-for-elementor-form
 * Description: Extensions for Elementor Form create many actions and controls to Elementor Form. This plugin require the Elementor Pro (Form Widget).
 * Author: EduardoVillao.me
 * Author URI: https://eduardovillao.me/
 * Version: 1.3.6
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once dirname(__FILE__) . '/init-whatsapp-action.php';
require_once dirname(__FILE__) . '/includes/class-show-content-after-submit.php';

function ele_extensions_add_scripts () {

    wp_enqueue_script( 'custom-js', plugin_dir_url( __FILE__ ) . 'assets/script.js', array( 'jquery' ), '1.3.6' );
	wp_enqueue_style( 'custom-style',  plugin_dir_url( __FILE__ ) . 'assets/style.css', array(), '1.3.6' );
}

add_action( 'wp_enqueue_scripts', 'ele_extensions_add_scripts' );

function ele_extensions_check_elementorpro_active () {

	if ( ! is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {

	  	echo "<div class='error'><p><strong>Extensions for Elementor Form</strong> requires <strong> Elementor Pro plugin</strong> </p></div>";
		}
	}

add_action('admin_notices', 'ele_extensions_check_elementorpro_active');