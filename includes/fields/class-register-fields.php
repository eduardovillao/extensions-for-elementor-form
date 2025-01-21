<?php

namespace EEF\Includes\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Register_Fields {
	/**
	 * Actions
	 */
	private array $fields;

	public function __construct( array $fields) {
		$this->fields = $fields;
	}

	public function set_hooks() : void {
		\add_action( 'elementor_pro/forms/fields/register', array( $this, 'register' ), -10 );
	}

	/**
	 * Register form fields.
	 *
	 * @since 2.0
	 * @param ElementorPro\Modules\Forms\Registrars\Form_Fields_Registrar $form_fields_register
	 */
	public function register( $form_fields_register ) : void {
		if ( empty( $this->fields ) ) {
			return;
		}

		foreach ( $this->fields as $field ) {
			include_once EEF_PLUGIN_PATH . $field['relative_path'] ?? '';

			$class_name = 'EEF\Includes\Fields\\' . $field['class_name'];
			if ( class_exists( $class_name ) ) {
				$form_fields_register->register( new $class_name() );
			}
		}
	}
}
