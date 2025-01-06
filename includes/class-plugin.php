<?php

namespace EEF\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin main class
 */
final class Plugin {
	/**
	 * Instance
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 */
	public static function instance() : self {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Disable class cloning and throw an error on object clone.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, \esc_html__( 'Something went wrong.', 'extensions-for-elementor-form' ), EEF_VERSION );
	}

	/**
	 * Disable unserializing of the class.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, \esc_html__( 'Something went wrong.', 'extensions-for-elementor-form' ), EEF_VERSION );
	}

	/**
	 * Construct class
	 */
	private function __construct() {
		\do_action( 'extensions_for_elementor_form_load' );
		\register_activation_hook( EEF_PLUGIN_MAIN_FILE, array( $this, 'activation' ) );
		\register_deactivation_hook( EEF_PLUGIN_MAIN_FILE, array( $this, 'deactivation' ) );
		\add_action( 'elementor/init', array( $this, 'init' ), 5 );
		\add_action( 'wp_loaded', array( $this, 'check_elementor_pro_loaded' ) );
	}

	/**
	 * Init plugin
	 */
	public function init() : void {
		\do_action( 'extensions_for_elementor_form_init' );

		$this->load_required_files();

		$custom_success_message = new Custom_Success_Message();
		$custom_success_message->set_hooks();
	}

	/**
	 * Load required files
	 */
	public function load_required_files() : void {
		include_once EEF_PLUGIN_PATH . '/includes/init-custom-actions.php';
		include_once EEF_PLUGIN_PATH . '/includes/class-custom-success-message.php';
	}

	/**
	 * Enqueu admin styles/scripts
	 */
	public function enqueue_admin_scripts() : void {}

	/**
	 * Enqueue front end styles/scripts
	 */
	public function enqueue_frondend_scripts() : void {}

	/**
	 * Check Elementor Pro loaded
	 */
	public function check_elementor_pro_loaded() : void {
		if ( ! \did_action('elementor_pro/init') ) {
			\add_action( 'admin_notices', array( $this, 'notice_elementor_pro_inactive' ) );
		}
	}

	/**
	 * Admin notice Elementor Pro disabled
	 */
	function notice_elementor_pro_inactive() {
		$message = sprintf(
			\esc_html__( '%1$s requires %2$s to be installed and activated.', 'extensions-for-elementor-form' ),
			'<strong>Extensions for Elementor Form</strong>',
			'<strong>Elementor Pro</strong>'
		);

		$html_message = sprintf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );
		echo \wp_kses_post( $html_message );
	}

	/**
	 * Activation hook
	 */
	public function activation() : void {
		\flush_rewrite_rules();
	}

	/**
	 * Deactivation hook
	 */
	public function deactivation() : void {
		\flush_rewrite_rules();
	}
}
