<?php
/**
 * Plugin Name: Extensions for Elementor Form
 * Plugin URI: https://eduardovillao.me/extensions-for-elementor-form
 * Description: This plugin empowers your Elementor Forms with advanced functionality that simplifies workflows, improves usability, and integrates seamlessly with tools like WhatsApp.
 * Author: EduardoVillao.me
 * Author URI: https://eduardovillao.me/
 * Text Domain: extensions-for-elementor-form
 * Version: 2.3
 * Requires at least: 5.5
 * Requires PHP: 7.4
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EEF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EEF_PLUGN_URL', plugin_dir_url( __FILE__ ) );
define( 'EEF_VERSION', '2.3' );
define( 'EEF_PHP_MINIMUM_VERSION', '7.4' );
define( 'EEF_WP_MINIMUM_VERSION', '5.5' );
define( 'EEF_PLUGIN_MAIN_FILE', __FILE__ );

/**
 * Check PHP and WP version before include plugin class
 *
 * @since 2.0
 */
if ( ! version_compare( PHP_VERSION, EEF_PHP_MINIMUM_VERSION, '>=' ) ) {
	\add_action( 'admin_notices', 'eef_admin_notice_php_version_fail' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), EEF_WP_MINIMUM_VERSION, '>=' ) ) {
	\add_action( 'admin_notices', 'eef_admin_notice_wp_version_fail' );
} else {
	include_once EEF_PLUGIN_PATH . '/includes/class-plugin.php';
	\add_action( 'plugins_loaded', array( 'EEF\Includes\Plugin', 'instance' ), 10 );
}

/**
 * Admin notice PHP version fail
 *
 * @since 2.0
 * @return string
 */
function eef_admin_notice_php_version_fail() {
	$message = sprintf(
		\esc_html__( '%1$s requires PHP version %2$s or greater.', 'extensions-for-elementor-form' ),
		'<strong>Extensions for Elementor Form</strong>',
		EEF_PHP_MINIMUM_VERSION
	);

	$html_message = sprintf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );
	echo \wp_kses_post( $html_message );
}

/**
 * Admin notice WP version fail
 *
 * @since 2.0
 * @return string
 */
function eef_admin_notice_wp_version_fail() {
	$message = sprintf(
		\esc_html__( '%1$s requires WordPress version %2$s or greater.', 'extensions-for-elementor-form' ),
		'<strong>Extensions for Elementor Form</strong>',
		EEF_WP_MINIMUM_VERSION
	);

	$html_message = sprintf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );
	echo \wp_kses_post( $html_message );
}
