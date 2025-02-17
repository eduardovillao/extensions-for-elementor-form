<?php
namespace Cool_FormKit\Modules\Forms\Classes;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;

use Cool_FormKit\Modules\Forms\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Form_Base extends Widget_Base {

	public function on_export( $element ) {
		/** @var \Cool_FormKit\Modules\Forms\Classes\Action_Base[] $actions */
		$actions = Module::instance()->actions_registrar->get();

		foreach ( $actions as $action ) {
			$new_element_data = $action->on_export( $element );
			if ( null !== $new_element_data ) {
				$element = $new_element_data;
			}
		}

		return $element;
	}

	public static function get_button_sizes(): array {
		return [
			'xs' => esc_html__( 'Extra Small', 'cool-formkit' ),
			'sm' => esc_html__( 'Small', 'cool-formkit' ),
			'md' => esc_html__( 'Medium', 'cool-formkit' ),
			'lg' => esc_html__( 'Large', 'cool-formkit' ),
			'xl' => esc_html__( 'Extra Large', 'cool-formkit' ),
		];
	}

	public function make_textarea_field( $item, $item_index, $instance ): string {
		$this->add_render_attribute( 'textarea' . $item_index, [
			'class' => [
				'elementor-field-textual',
				'cool-form__field',
				'cool-form__textarea',
				esc_attr( $item['css_classes'] ),
			],
			'name' => $this->get_attribute_name( $item ),
			'id' => $this->get_attribute_id( $item ),
			'rows' => $item['rows'],
		] );

		if ( $item['placeholder'] ) {
			$this->add_render_attribute( 'textarea' . $item_index, 'placeholder', $item['placeholder'] );
		}

		if ( $item['required'] ) {
			$this->add_required_attribute( 'textarea' . $item_index );
		}

		$value = empty( $item['field_value'] ) ? '' : $item['field_value'];

		return '<textarea ' . $this->get_render_attribute_string( 'textarea' . $item_index ) . '>' . $value . '</textarea>';
	}

	public function make_select_field( $item, $i ) {
		$this->add_render_attribute(
			[
				'select-wrapper' . $i => [
					'class' => [
						'cool-form__field',
						'cool-form__select',
						'remove-before',
						esc_attr( $item['css_classes'] ),
					],
				],
				'select' . $i => [
					'name' => $this->get_attribute_name( $item ) . ( ! empty( $item['allow_multiple'] ) ? '[]' : '' ),
					'id' => $this->get_attribute_id( $item ),
					'class' => [
						'cool-form-field-textual',
						'cool-form__field',
					],
				],
			]
		);

		if ( $item['required'] ) {
			$this->add_required_attribute( 'select' . $i );
		}

		if ( $item['allow_multiple'] ) {
			$this->add_render_attribute( 'select' . $i, 'multiple' );
			if ( ! empty( $item['select_size'] ) ) {
				$this->add_render_attribute( 'select' . $i, 'size', $item['select_size'] );
			}
		}

		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );

		if ( ! $options ) {
			return '';
		}

		ob_start();
		?>
		<div <?php $this->print_render_attribute_string( 'select-wrapper' . $i ); ?>>
			<div class="select-caret-down-wrapper">
				<?php
				if ( ! $item['allow_multiple'] ) {
					$icon = [
						'library' => 'eicons',
						'value' => 'eicon-caret-down',
						'position' => 'right',
					];
					Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
				}
				?>
			</div>
			<select <?php $this->print_render_attribute_string( 'select' . $i ); ?>>
				<?php
				foreach ( $options as $key => $option ) {
					$option_id = esc_attr( $item['custom_id'] . $key );
					$option_value = esc_attr( $option );
					$option_label = $option;

					if ( false !== strpos( $option, '|' ) ) {
						list( $label, $value ) = explode( '|', $option );
						$option_value = esc_attr( $value );
						$option_label = $label;
					}

					$this->add_render_attribute( $option_id, 'value', $option_value );

					// Support multiple selected values
					if ( ! empty( $item['field_value'] ) && in_array( $option_value, explode( ',', $item['field_value'] ), true ) ) {
						$this->add_render_attribute( $option_id, 'selected', 'selected' );
					} ?>
					<option <?php $this->print_render_attribute_string( $option_id ); ?>><?php
						// PHPCS - $option_label is already escaped
						echo esc_html( $option_label ); ?></option>
				<?php } ?>
			</select>
		</div>
		<?php

		$select = ob_get_clean();
		return $select;
	}

	public function make_radio_checkbox_field( $item, $item_index, $type ): string {
		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );
		$html = '';
		if ( $options ) {
			$html .= '<div class="elementor-field-subgroup ' . esc_attr( $item['css_classes'] ) . ' ' . esc_attr( $item['inline_list'] ) . '">';
			foreach ( $options as $key => $option ) {
				$element_id = esc_attr( $item['custom_id'] ) . $key;
				$html_id = $this->get_attribute_id( $item ) . '-' . $key;
				$option_label = $option;
				$option_value = $option;
				if ( false !== strpos( $option, '|' ) ) {
					list( $option_label, $option_value ) = explode( '|', $option );
				}

				$this->add_render_attribute(
					$element_id,
					[
						'type' => $type,
						'value' => $option_value,
						'id' => $html_id,
						'name' => $this->get_attribute_name( $item ) . ( ( 'checkbox' === $type && count( $options ) > 1 ) ? '[]' : '' ),
					]
				);

				if ( ! empty( $item['field_value'] ) && $option_value === $item['field_value'] ) {
					$this->add_render_attribute( $element_id, 'checked', 'checked' );
				}

				if ( $item['required'] && 'radio' === $type ) {
					$this->add_required_attribute( $element_id );
				}

				$html .= '<span class="elementor-field-option"><input ' . $this->get_render_attribute_string( $element_id ) . '> <label for="' . $html_id . '">' . $option_label . '</label></span>';
			}
			$html .= '</div>';
		}

		return $html;
	}

	public function form_fields_render_attributes( $i, $instance, $item ) {
		$this->add_render_attribute(
			[
				'field-group' . $i => [
					'class' => [
						'cool-form__field-group',
						'is-field-type-' . $item['field_type'],
						'is-field-group-' . $item['custom_id'],
					],
				],
				'input' . $i => [
					'type' => $item['field_type'],
					'name' => $this->get_attribute_name( $item ),
					'id' => $this->get_attribute_id( $item ),
					'class' => [
						'cool-form__field',
						'cool-form-field-type-' . $item['field_type'],
						empty( $item['css_classes'] ) ? '' : esc_attr( $item['css_classes'] ),
					],
				],
				'label' . $i => [
					'for' => $this->get_attribute_id( $item ),
					'class' => 'cool-form__field-label',
				],
			]
		);

		if ( empty( $item['width'] ) ) {
			$item['width'] = '100';
		}

		$this->add_render_attribute( 'field-group' . $i, 'class', 'has-width-' . $item['width'] );

		if ( $item['allow_multiple'] ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'elementor-field-type-' . $item['field_type'] . '-multiple' );
		}

		if ( ! empty( $item['width_tablet'] ) ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'has-width-md-' . $item['width_tablet'] );
		}

		if ( ! empty( $item['width_mobile'] ) ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'has-width-sm-' . $item['width_mobile'] );
		}

		// Allow zero as placeholder.
		if ( ! Utils::is_empty( $item['placeholder'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'placeholder', $item['placeholder'] );
		}

		if ( ! empty( $item['field_value'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'value', $item['field_value'] );
		}

		if ( ! $instance['show_labels'] ) {
			$this->add_render_attribute( 'label' . $i, 'class', 'elementor-screen-only' );
		}

		if ( ! empty( $item['required'] ) ) {
			$class = 'is-field-required';
			if ( ! empty( $instance['mark_required'] ) ) {
				$class .= ' is-mark-required';
			}
			$this->add_render_attribute( 'field-group' . $i, 'class', $class );
			$this->add_required_attribute( 'input' . $i );
		}

		if ( 'yes' === $instance['field_border_switcher'] ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'has-border' );
		}

		if ( ! empty( $instance['fields_shape'] ) ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'has-shape-' . $instance['fields_shape'] );
		}
	}

	public function render_plain_content() {}

	public function get_attribute_name( $item ): string {
		return "form_fields[{$item['custom_id']}]";
	}

	public function get_attribute_id( $item ): string {
		return 'form-field-' . esc_attr( $item['custom_id'] );
	}

	private function add_required_attribute( $element ) {
		$this->add_render_attribute( $element, 'required', 'required' );
		$this->add_render_attribute( $element, 'aria-required', 'true' );
	}

	public function get_categories(): array {
		return [ 'general' ];
	}
}
