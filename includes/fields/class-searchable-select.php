<?php

namespace EEF\Includes\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor Form Field - Credit Card Number
 *
 * Add a new "Credit Card Number" field to Elementor form widget.
 *
 * @since 1.0.0
 */
class Searchable_Select extends \ElementorPro\Modules\Forms\Fields\Field_Base {
	/**
	 * Dependent scripts.
	 */
	public $depended_scripts = [ 'eef-searchable-select-script' ];

	/**
	 * Dependent styles.
	 */
	public $depended_styles = [ 'eef-searchable-select-style' ];

	/**
	 * Get field type.
	 *
	 * Retrieve credit card number field unique ID.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Field type.
	 */
	public function get_type(): string {
		return 'searchable-select';
	}

	/**
	 * Get field name.
	 *
	 * Retrieve credit card number field label.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Field name.
	 */
	public function get_name(): string {
		return esc_html__( 'Searchable Select', 'extensions-for-elementor-form' );
	}

	/**
	 * Render field output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param mixed $item
	 * @param mixed $item_index
	 * @param mixed $form
	 * @return void
	 */
	public function render( $item, $item_index, $form ): void {
		$form_id = $form->get_id();

		$form->add_render_attribute(
			'input' . $item_index,
			[
				'class' => 'elementor-field-textual searchable-select',
				'for' => $form_id . $item_index,
				'type' => 'text',
				'readonly' => 'readonly',
			]
		);

		echo '<input ' . $form->get_render_attribute_string( 'input' . $item_index ) . '>';
		echo '<div class="searchable-select-container">
			<input type="text" class="elementor-field elementor-field-textual searchable-select" placeholder="Search...">
			<ul class="custom-select-options">
				<li data-value="Option 1">Option 1</li>
				<li data-value="Option 2">Option 2</li>
				<li data-value="Option 3">Option 3</li>
				<li data-value="Option 4">Option 4</li>
				<li data-value="Option 5">Option 5</li>
			</ul>
		</div>';
	}

	/**
	 * Field validation.
	 *
	 * Validate credit card number field value to ensure it complies to certain rules.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param \ElementorPro\Modules\Forms\Classes\Field_Base   $field
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
	 * @return void
	 */
	public function validation( $field, $record, $ajax_handler ): void {}

	/**
	 * Update form widget controls.
	 *
	 * Add input fields to allow the user to customize the credit card number field.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param \Elementor\Widget_Base $widget The form widget instance.
	 * @return void
	 */
	public function update_controls( $widget ): void {}
}
