<?php
/**
 * Plugin Name: Extensions for Elementor Form
 * Plugin URI: https://eduardovillao.me/extensions-for-elementor-form
 * Description: Extensions for Elementor Form create many actions and controls to Elementor Form. This plugin require the Elementor Pro (Form Widget).
 * Author: EduardoVillao.me
 * Author URI: https://eduardovillao.me/
 * Text Domain: extensions-for-elementor-form
 * Version: 2.0.1
 * Requires at least: 5.5
 * Requires PHP: 7.0
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use ElementorPro\Plugin;
use ElementorPro\Modules\ThemeBuilder\Module;

define( 'EEF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EEF_PLUGN_URL', plugin_dir_url( __FILE__ ) );
define( 'EEF_VERSION', '2.0.1' );
define( 'EEF_PHP_MINIMUM_VERSION', '7.0' );
define( 'EEF_WP_MINIMUM_VERSION', '5.5' );

/**
 * Check PHP and WP version before include plugin class
 *
 * @since 2.0
 */
if ( ! version_compare( PHP_VERSION, EEF_PHP_MINIMUM_VERSION, '>=' ) ) {
	add_action( 'admin_notices', 'eef_admin_notice_php_version_fail' );

} elseif ( ! version_compare( get_bloginfo( 'version' ), EEF_WP_MINIMUM_VERSION, '>=' ) ) {
	add_action( 'admin_notices', 'eef_admin_notice_wp_version_fail' );

} else {
    add_action( 'plugins_loaded', 'eef_init_plugin', 10 );
}

/**
 * Init plugin (temp. code)
 *
 * @since 2.0
 */
function eef_init_plugin() {
	if ( ! eef_plugin_is_active( 'elementor-pro/elementor-pro.php' ) ) {
		add_action( 'admin_notices', 'eef_notice_elementor_pro_inactive' );
		return;
	}

	include_once EEF_PLUGIN_PATH . '/init-custom-actions.php';
	include_once EEF_PLUGIN_PATH . '/includes/class-show-content-after-submit.php';
}

/**
 * Admin notice PHP version fail
 *
 * @since 2.0
 * @return string
 */
function eef_admin_notice_php_version_fail() {
	$message = sprintf(
		esc_html__( '%1$s requires PHP version %2$s or greater.', 'extensions-for-elementor-form' ),
		'<strong>Extensions for Elementor Form</strong>',
		EEF_PHP_MINIMUM_VERSION
	);

	$html_message = sprintf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );
	echo wp_kses_post( $html_message );
}

/**
 * Admin notice WP version fail
 *
 * @since 2.0
 * @return string
 */
function eef_admin_notice_wp_version_fail() {
	$message = sprintf(
		esc_html__( '%1$s requires WordPress version %2$s or greater.', 'extensions-for-elementor-form' ),
		'<strong>Extensions for Elementor Form</strong>',
		EEF_WP_MINIMUM_VERSION
	);

	$html_message = sprintf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );
	echo wp_kses_post( $html_message );
}

/**
 * Admin notice Elementor Pro disabled
 *
 * @since 2.0
 * @return string
 */
function eef_notice_elementor_pro_inactive() {
	$message = sprintf(
		esc_html__( '%1$s requires %2$s to be installed and activated.', 'extensions-for-elementor-form' ),
		'<strong>Extensions for Elementor Form</strong>',
		'<strong>Elementor Pro</strong>'
	);

	$html_message = sprintf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );
	echo wp_kses_post( $html_message );
}

/**
 * Check if plugin is active
 *
 * @since 2.0
 */
function eef_plugin_is_active( $plugin_name ) {
	return function_exists( 'is_plugin_active' ) ? is_plugin_active( $plugin_name ) : in_array( $plugin_name, (array) get_option( 'active_plugins', array() ), true );
}
