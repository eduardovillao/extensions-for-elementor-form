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
		return 'eef-register-post';
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
         * TODO:
         * 1. Add or edit existent post.
         * 2. Post terms (taxonomy).
         * 3. Redirect to post after submit/created?.
         * 4. Se post status === private add campo de senha.
         */
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
			]
		);

        $widget->add_control(
			'eef-register-post-post-status',
			[
				'label' => __( 'Post Status', 'extensions-for-elementor-form' ),
				'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'draft',
				'options' => [
					'draft'  => esc_html__( 'Draft', 'extensions-for-elementor-form' ),
					'publish' => esc_html__( 'Publish', 'extensions-for-elementor-form' ),
                    'pending' => esc_html__( 'Pending', 'extensions-for-elementor-form' ),
				],
			]
		);

        $widget->add_control(
			'eef-register-post-user-permission',
			[
				'label' => esc_html__( 'Run only to logged in users', 'extensions-for-elementor-form' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'extensions-for-elementor-form' ),
				'label_off' => esc_html__( 'No', 'extensions-for-elementor-form' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'description' => __( 'Warning: Save data from not logged in users can be a security risk', 'extensions-for-elementor-form' ),
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
		$form_fields = $record->get( 'fields' );
		$fields_settings = $record->get_form_settings( 'form_fields' );
		$formated_fields = array();
		foreach ( $fields_settings as $key => $field ) {
			$formated_fields[ $key ] = $form_fields[ $field['custom_id'] ];
			$formated_fields[ $key ]['field-to-register'] = $field['eef-register-post-field'];
			$formated_fields[ $key ]['custom-field-to-register'] = $field['eef-register-post-custom-field'];
		}

		print_r( $formated_fields );
		return;

		$post_data = array(
			'post_title' => '123',
			'post_content' => '456',
			'post_status' => 'draft'
		);

		$post_id = wp_insert_post( $post_data );
		if ( is_wp_error( $post_id ) ) {
			return 'WP Error';
		}
		//sanitize_post();

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
