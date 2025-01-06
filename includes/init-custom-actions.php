<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use EEF\Includes\Actions\Whatsapp_Redirect;
use EEF\Includes\Actions\Register_Post;
use EEF\Includes\Actions\Register_Post_Fields_Controls;

/**
 * Register custom field to form repeater
 */
include_once EEF_PLUGIN_PATH . '/includes/actions/class-register-post-fields-controls.php';
$register_post_fields_controls = new Register_Post_Fields_Controls();
$register_post_fields_controls->set_hooks();


/**
 * Add new form action after form submission.
 *
 * @since 2.0
 * @param ElementorPro\Modules\Forms\Registrars\Form_Actions_Registrar $form_actions_registrar
 * @return void
 */
function eef_register_custom_action( $form_actions_registrar ) {
	include_once EEF_PLUGIN_PATH . '/includes/actions/class-whatsapp-redirect.php';
	include_once EEF_PLUGIN_PATH . '/includes/actions/class-register-post.php';

	$form_actions_registrar->register( new Whatsapp_Redirect() );
	$form_actions_registrar->register( new Register_Post() );
}

add_action( 'elementor_pro/forms/actions/register', 'eef_register_custom_action', -10 );

/**
 * Register frontend assets
 *
 * @since 2.0
 */
function eef_register_plugin_assets() {
	wp_enqueue_script( 'eef-frontend-script', EEF_PLUGN_URL . 'assets/js/frontend-scripts.min.js', array( 'jquery' ), EEF_VERSION );
	wp_enqueue_style( 'eef-frontend-style',  EEF_PLUGN_URL . 'assets/css/style.min.css', array(), EEF_VERSION );
}

add_action( 'wp_enqueue_scripts', 'eef_register_plugin_assets' );

/**
 * Register custom scritps on Elementor editor
 *
 * @since 2.0
 */
function eef_register_editor_scripts() {
	wp_register_script( 'eef-editor-scripts', EEF_PLUGN_URL . 'assets/js/editor-scripts.min.js', array(), EEF_VERSION );
	wp_enqueue_script( 'eef-editor-scripts' );
}

add_action( 'elementor/editor/after_enqueue_scripts', 'eef_register_editor_scripts' );
