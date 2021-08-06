<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'elementor_pro/init', function() {
	// Here its safe to include our action class file
	require( dirname(__FILE__).'/includes/class-whatsapp-action.php' );

	// Instantiate the action class
	$whats_action = new Whatsapp_Action_After_Submit;

	// Register the action with form widget
	\ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $whats_action->get_name(), $whats_action );
});