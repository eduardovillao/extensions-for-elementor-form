<?php
/**
 * Plugin Name: Extensions for Elementor Form
 * Plugin URI: https://coolplugins.net/
 * Description: This plugin empowers your Elementor Forms with advanced functionality that simplifies workflows, improves usability, and integrates seamlessly with tools like WhatsApp.
 * Author: Cool Plugins
 * Author URI: https://coolplugins.net/
 * Text Domain: extensions-for-elementor-form
 * Version: 2.3
 * Requires at least: 5.5
 * Requires PHP: 7.4
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

define('EEF_VERSION','2.3');
define('PHP_MINIMUM_VERSION','7.4');
define('WP_MINIMUM_VERSION','5.5');
define( 'EEF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EEF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'EEF_PLUGIN_MAIN_FILE', __FILE__ );


register_activation_hook( __FILE__, array( 'EEF_Extensions_For_Elementor_Form', 'eef_activate' ) );
register_deactivation_hook( __FILE__, array( 'EEF_Extensions_For_Elementor_Form', 'eef_deactivate' ) );

if ( ! function_exists( 'is_plugin_active' ) ) {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

class EEF_Extensions_For_Elementor_Form {

	/**
     * Plugin instance.
    */
    public static $instance = null;

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ($this->check_requirements() ) {
			$this->initialize_plugin();
			add_action( 'activated_plugin', array( $this, 'EEF_plugin_redirection' ) );
		}
	}

	/**
     * Singleton instance.
     *
     * @return self
     */
    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }
	/**
	 * Add hooks for plugin initialization.
	 */
	public function initialize_plugin() {
		// Include main plugin class.
		require_once EEF_PLUGIN_PATH . '/includes/class-plugin.php';
		CFKEF_Loader::get_instance();
		
		if ( is_admin() ) {
			require_once EEF_PLUGIN_PATH . 'admin/feedback/admin-feedback-form.php';
		}
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'EEF_plugin_dashboard_link' ) );

	}

	public function EEF_plugin_redirection($plugin){
		if ( ! is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {
			return false;
		}
		if (  is_plugin_active( 'cool-formkit-for-elementor-forms/cool-formkit-for-elementor-forms.php' ) ) {
			return false;
		}
		if ( $plugin == plugin_basename( __FILE__ ) ) {
			exit( wp_redirect( admin_url( 'admin.php?page=cool-formkit' ) ) );
		}
	}
	/**
	 * Check PHP and WordPress version compatibility.
	 *
	 * @return bool
	 */
	public function check_requirements() {
		if ( ! version_compare( PHP_VERSION, PHP_MINIMUM_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_php_version_fail' ] );
			return false;
		}

		if ( ! version_compare( get_bloginfo( 'version' ), WP_MINIMUM_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_wp_version_fail' ] );
			return false;
		}

		if ( is_plugin_active( 'cool-formkit-for-elementor-forms/cool-formkit-for-elementor-forms.php' ) ) {
			return false;
		}

		if ( ! is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {
			add_action('admin_notices', array($this, 'admin_notice_missing_main_plugin'));
			return false;
		}


		return true;
	}

	public function EEF_plugin_dashboard_link($links){
		$settings_link = '<a href="' . admin_url( 'admin.php?page=cool-formkit' ) . '">Settings</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}
	/**
	 * Show notice to enable elementor pro
	 */
	public function admin_notice_missing_main_plugin() {
		$message = sprintf(
			// translators: %1$s replace with Conditional Fields for Elementor Form & %2$s replace with Elementor Pro.
			esc_html__(
				'%1$s requires %2$s to be installed and activated.',
				'extensions-for-elementor-form'
			),
			esc_html__( 'Extensions for Elementor Form', 'extensions-for-elementor-form' ),
			esc_html__( 'Elementor Pro', 'extensions-for-elementor-form' ),
			); 
			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', esc_html( $message ) );
			deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	/**
	 * Display admin notice for PHP version failure.
	 */
	public function admin_notice_php_version_fail() {
		$message = sprintf(
			esc_html__( '%1$s requires PHP version %2$s or greater.', 'extensions-for-elementor-form' ),
			'<strong>Extensions for Elementor Form</strong>',
			PHP_MINIMUM_VERSION
		);

		echo wp_kses_post( sprintf( '<div class="notice notice-error"><p>%1$s</p></div>', $message ) );
	}

	/**
	 * Display admin notice for WordPress version failure.
	 */
	public function admin_notice_wp_version_fail() {
		$message = sprintf(
			esc_html__( '%1$s requires WordPress version %2$s or greater.', 'extensions-for-elementor-form' ),
			'<strong>Extensions for Elementor Form</strong>',
			WP_MINIMUM_VERSION
		);

		echo wp_kses_post( sprintf( '<div class="notice notice-error"><p>%1$s</p></div>', $message ) );
	}

	public static function eef_activate(){
		update_option( 'eef-v', EEF_VERSION );
		update_option( 'eef-type', 'FREE' );
		update_option( 'eef-installDate', gmdate( 'Y-m-d h:i:s' ) );
	}

	public static function eef_deactivate(){
	}
}

// Initialize the plugin.
EEF_Extensions_For_Elementor_Form::instance();