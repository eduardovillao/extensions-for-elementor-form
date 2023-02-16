<?php

namespace Eef\Includes;

use \Elementor\Plugin as ElementorPlugin;
use \Elementor\Controls_Manager as ElementorControls;
use \Elementor\Repeater as ElementorRepeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom field inside a form repeater (advanced tab) if action is enabled.
 * 
 * @since 2.0
 */
class Register_Create_Post_Fields {
    /**
     * Contructor...
     */
	public function __construct() {
		add_action( 'elementor/element/form/section_form_fields/before_section_end', [ $this, 'add_control_fields' ], 100, 2 );
	}

	/**
	 * Add create post fields
	 * 
	 * @since 2.0
	 * @param $element
	 * @param $args
	 */
	public function add_control_fields( $element, $args ) {
		$elementor = ElementorPlugin::instance();
		$control_data = $elementor->controls_manager->get_control_from_stack( $element->get_name(), 'form_fields' );

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$new_control = [
			'label' => __( 'Field to Register', 'extensions-for-elementor-form' ),
			'type' => ElementorControls::SELECT,
			'tab' => 'content',
			'tabs_wrapper' => 'form_fields_tabs',
			'inner_tab' => 'form_fields_advanced_tab',
			'classes' => 'elementor-hidden-control',
            'description' => __( 'Use this input to define what post field will receive this data when post is registered', 'extensions-for-elementor-form' ),
            'default' => 'select',
            'options' => [
                'select' => __( 'Select', 'extensions-for-elementor-form' ),
                'post_title' => __( 'Post Title', 'extensions-for-elementor-form' ),
                'post_content' => __( 'Post Content', 'extensions-for-elementor-form' ),
				'post_excerpt' => __( 'Post Excerpt', 'extensions-for-elementor-form' ),
				'post_author' => __( 'Post Author', 'extensions-for-elementor-form' ),
                'custom_field' => __( 'Custom Field', 'extensions-for-elementor-form' ),
            ],
		];

        $new_control_2 = [
			'label' => __( 'Custom Field Name', 'extensions-for-elementor-form' ),
			'type' => ElementorControls::TEXT,
            'placeholder' => __( 'custom_field_name', 'extensions-for-elementor-form' ),
			'tab' => 'content',
			'tabs_wrapper' => 'form_fields_tabs',
			'inner_tab' => 'form_fields_advanced_tab',
            'description' => __( 'Add the Custom Field name here. You can use default fields or custom created with ACF or similars', 'extensions-for-elementor-form' ),
			'condition' => [
					'eef-register-post-field' => 'custom_field',
			],
		];
		
		$mask_control = new ElementorRepeater();
		$mask_control->add_control( 'eef-register-post-field', $new_control );
		$mask_control->add_control( 'eef-register-post-custom-field', $new_control_2 );
		$pattern_field = $mask_control->get_controls();

		/**
		 * Register control in form advanced tab.
		 * 
		 * @since 2.0
		 */
		$this->register_control_in_form_advanced_tab( $element, $control_data, $pattern_field );
	}

	/**
	 * Register control in form advanced tab
	 *
	 * @param object $element
	 * @param array $control_data
	 * @param array $pattern_field
	 * @return void
	 * 
	 * @since 2.0
	 */
	public function register_control_in_form_advanced_tab( $element, $control_data, $pattern_field ) {
		foreach( $pattern_field as $key => $control ) {
			if( $key !== '_id' ) {
				$new_order = [];
				foreach ( $control_data['fields'] as $field_key => $field ) {
					if ( 'field_value' === $field['name'] ) {
						$new_order[$key] = $control;
					}
					$new_order[ $field_key ] = $field;
				}

				$control_data['fields'] = $new_order;
			}
		}

		return $element->update_control( 'form_fields', $control_data );
	}
}