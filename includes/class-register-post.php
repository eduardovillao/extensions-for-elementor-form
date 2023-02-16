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
	 * Get public post types
	 */
	private function get_registered_post_types() {
		$registered_post_types = get_post_types( array( 'public' => true ), 'objects' );
		$post_type_options =  array();
		foreach ( $registered_post_types as $post_type ) {
			$post_type_options[ $post_type->name ] = $post_type->label; 
		}

		return $post_type_options;
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
				'options' => $this->get_registered_post_types(),
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
				'label' => esc_html__( 'Run only to logged in users?', 'extensions-for-elementor-form' ),
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
		unset(
			$element['settings']['eef-register-post-post-type'],
			$element['settings']['eef-register-post-post-status'],
			$element['settings']['eef-register-post-user-permission'],
		);

		return $element;
	}

	/**
	 * Runs the action after submit
	 *
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
	 */
	public function run( $record, $ajax_handler ) {
		$new_post_data = array(
			'post_type' => $record->get_form_settings( 'eef-register-post-post-type' ),
			'post_status' => $record->get_form_settings( 'eef-register-post-post-status' ),
			'post_title' => $record->get_form_settings( 'form_name' ),
			'post_content' => $record->get_form_settings( 'form_name' ),
		);

		$form_fields = $record->get( 'fields' );
		$fields_settings = $record->get_form_settings( 'form_fields' );
		$formated_fields = array();
		foreach ( $fields_settings as $key => $field ) {
			$formated_fields[ $key ] = $form_fields[ $field['custom_id'] ];
			$formated_fields[ $key ]['field-to-register'] = $field['eef-register-post-field'];
			$formated_fields[ $key ]['custom-field-to-register'] = $field['eef-register-post-custom-field'];
		}

		$custom_fields_to_register = array();
		foreach ( $formated_fields as $field ) {
			if ( $field['field-to-register'] !== 'custom_field' ) {
				$new_post_data[ $field['field-to-register'] ] = $field['value'];
			} else {
				$custom_fields_to_register[ $field['custom-field-to-register'] ] = $field['value'];
			}
		}
		
		$is_restrict_to_loggedin_users = $record->get_form_settings( 'eef-register-post-user-permission' );
		if ( $is_restrict_to_loggedin_users !== 'yes' ) {
			$post_id = wp_insert_post( $new_post_data, true );
			if ( ! is_wp_error( $post_id ) ) {
				foreach ( $custom_fields_to_register as $meta_key => $meta_value ) {
					add_post_meta( $post_id, $meta_key, $meta_value );
				}	
			}
			return;
		}

		if ( is_user_logged_in() ) {
			$post_id = wp_insert_post( $new_post_data, true );
			if ( ! is_wp_error( $post_id ) ) {
				foreach ( $custom_fields_to_register as $meta_key => $meta_value ) {
					add_post_meta( $post_id, $meta_key, $meta_value );
				}	
			}
		}
	}
}
