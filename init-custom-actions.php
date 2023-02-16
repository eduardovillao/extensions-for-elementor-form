<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Eef\Includes\Register_Create_Post_Fields;
use Eef\Includes\Whatsapp_Action_After_Submit;
use Eef\Includes\Register_Post;

/**
 * Add new form action after form submission.
 *
 * @since 2.0
 * @param ElementorPro\Modules\Forms\Registrars\Form_Actions_Registrar $form_actions_registrar
 * @return void
 */
function eef_register_custom_action( $form_actions_registrar ) {
	include_once EEF_PLUGIN_PATH . '/includes/class-whatsapp-action.php';
	include_once EEF_PLUGIN_PATH . '/includes/class-register-post.php';

	$form_actions_registrar->register( new Whatsapp_Action_After_Submit() );
	$form_actions_registrar->register( new Register_Post() );
}

add_action( 'elementor_pro/forms/actions/register', 'eef_register_custom_action' );

/**
 * Register custom field to form repeater
 * 
 * @since 2.0
 */
include_once EEF_PLUGIN_PATH . '/includes/class-register-create-post-fields.php';
new Register_Create_Post_Fields();

/**
 * Register frontend assets
 * 
 * @since 2.0
 */
function eef_register_plugin_assets() {
	wp_enqueue_script( 'eef-frontend-script', EEF_PLUGN_URL . 'assets/frontend-scripts.js', array( 'jquery' ), EEF_VERSION );
	wp_enqueue_style( 'eef-frontend-style',  EEF_PLUGN_URL . 'assets/style.css', array(), EEF_VERSION );
}

add_action( 'wp_enqueue_scripts', 'eef_register_plugin_assets' );

/**
 * Register custom scritps on Elementor editor
 * 
 * @since 2.0
 */
function eef_register_editor_scripts() {
	wp_register_script( 'eef-editor-scripts', EEF_PLUGN_URL . 'assets/editor-scripts.js', array(), EEF_VERSION );
	wp_enqueue_script( 'eef-editor-scripts' );
}

add_action( 'elementor/editor/after_enqueue_scripts', 'eef_register_editor_scripts' );