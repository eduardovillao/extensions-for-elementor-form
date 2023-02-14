<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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

	$form_actions_registrar->register( new Eef\Includes\Whatsapp_Action_After_Submit() );
	$form_actions_registrar->register( new \Eef\Includes\Register_Post() );
}

add_action( 'elementor_pro/forms/actions/register', 'eef_register_custom_action' );