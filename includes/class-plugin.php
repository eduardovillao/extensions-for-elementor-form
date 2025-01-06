<?php

namespace EEF\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use EEF\Includes\Actions\Register_Actions;
use EEF\Includes\Custom_Success_Message;

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

		$actions = array(
			'whatsapp_redirect' => array(
				'relative_path' => '/includes/actions/class-whatsapp-redirect.php',
				'class_name' => 'Whatsapp_Redirect',
			),
			'register_post' => array(
				'relative_path' => '/includes/actions/class-register-post.php',
				'class_name' => 'Register_Post',
			),
		);
		$regiser_actions = new Register_Actions( $actions );
		$regiser_actions->set_hooks();

		$custom_success_message = new Custom_Success_Message();
		$custom_success_message->set_hooks();

		\add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'register_editor_scripts') );
		\add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frondend_scripts' ) );
	}

	/**
	 * Load required files
	 */
	public function load_required_files() : void {
		include_once EEF_PLUGIN_PATH . '/includes/actions/class-register-actions.php';
		include_once EEF_PLUGIN_PATH . '/includes/class-custom-success-message.php';
	}

	/**
	 * Enqueue front end styles/scripts
	 */
	public function enqueue_frondend_scripts() : void {
		wp_enqueue_script( 'eef-frontend-script', EEF_PLUGN_URL . 'assets/js/frontend-scripts.min.js', array( 'jquery' ), EEF_VERSION );
		wp_enqueue_style( 'eef-frontend-style',  EEF_PLUGN_URL . 'assets/css/style.min.css', array(), EEF_VERSION );
	}

	/**
	 * Register custom scritps on Elementor editor
	 *
	 * @since 2.0
	 */
	function register_editor_scripts() : void {
		wp_register_script( 'eef-editor-scripts', EEF_PLUGN_URL . 'assets/js/editor-scripts.min.js', array(), EEF_VERSION );
		wp_enqueue_script( 'eef-editor-scripts' );
	}

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
