<?php

namespace Eef\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register post after form submit.
 */
class Register_Post extends \ElementorPro\Modules\Forms\Classes\Action_Base {
	/**
	 * Get Name
	 *
	 * Return the action name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'register-post';
	}

	/**
	 * Get Label
	 *
	 * Returns the action label
	 *
	 * @return string
	 */
	public function get_label() {
		return 'Register Post/Custom Post';
	}

	/**
	 * Register Settings Section
	 *
	 * Registers the Action controls
	 *
	 * @param \Elementor\Widget_Base $widget
	 */
	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'eef-register-post-section',
			[
				'label' => __( 'Register Post/Custom Post', 'extensions-for-elementor-form' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

        /**
         * 1. Post status.
         * 2. Add or edit existent post.
         * 3. Post terms (taxonomy).
         * 4. Redirect to post after submit/created?.
         */

        // $widget->add_control(
		// 	'eef-register-post-note',
		// 	[
		// 		'label' => esc_html__( 'Important', 'extensions-for-elementor-form' ),
		// 		'type' => \Elementor\Controls_Manager::RAW_HTML,
		// 		'raw' => esc_html__( 'For security reasons this action will works only to logged in users.', 'extensions-for-elementor-form' ),
		// 		'content_classes' => 'your-class',
		// 	]
		// );

		$widget->add_control(
			'eef-register-post-post-type',
			[
				'label' => __( 'Post Type', 'extensions-for-elementor-form' ),
				'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'post',
				'options' => [
					'post'  => esc_html__( 'Post', 'extensions-for-elementor-form' ),
					'page' => esc_html__( 'Page', 'extensions-for-elementor-form' ),
				],
				'description' => __( 'Select the Post Type to receive the form data.', 'extensions-for-elementor-form' ),
			]
		);

		$widget->end_controls_section();
	}

	/**
	 * On Export
	 *
	 * Clears form settings on export
	 * @param array $element
	 */
	public function on_export( $element ) {
		// unset(
		// 	$element['settings']['whatsapp_to'],
		// 	$element['settings']['whatsapp_message']
		// );

		// return $element;
	}

	/**
	 * Runs the action after submit
	 *
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
	 */
	public function run( $record, $ajax_handler ) {


		// $whatsapp_to = $record->get_form_settings( 'whatsapp_to' );
		// $whatsapp_message = $record->get_form_settings( 'whatsapp_message' );

		// $whatsapp_message = str_replace('%break%', '%0D%0A', $whatsapp_message);

		// $whatsapp_to = 'https://wa.me/'.$whatsapp_to.'?text='.$whatsapp_message.'';
		// $whatsapp_to = $record->replace_setting_shortcodes( $whatsapp_to, true );

		// if ( ! empty( $whatsapp_to ) ) {
		// 	$ajax_handler->add_response_data( 'redirect_url', $whatsapp_to );
		// }
	}
}
