<?php
namespace Cool_FormKit\Modules\Forms\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Repeater;
use Cool_FormKit\Includes\Utils;
use Cool_FormKit\Modules\Forms\Classes\Form_Base;
use Cool_FormKit\Modules\Forms\Classes\Render\Widget_Form_Render;
use Cool_FormKit\Modules\Forms\Components\Ajax_Handler;
use Cool_FormKit\Modules\Forms\Controls\Fields_Repeater;
use Cool_FormKit\Modules\Forms\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Cool_Form extends Form_Base {

	public function get_name() {
		return 'cool-form';
	}

	public function get_title() {
		return esc_html__( 'Cool Form Kit Form', 'cool-formkit' );
	}

	public function get_icon() {
		return 'eicon-ehp-forms';
	}

	public function get_keywords() {
		return [ 'form', 'forms', 'field', 'button' ];
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	protected function get_upsale_data(): array {
		return [];
	} 

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the widget requires.
	 *
	 * @since 3.24.0
	 * @access public
	 *
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends(): array {
		return [ 'Cool_FormKit-forms' ];
	}

	public function get_script_depends(): array {
		return [ 'Cool_FormKit-forms-fe' ];
	}

	protected function render(): void {
		$render_strategy = new Widget_Form_Render( $this );

		$render_strategy->render();
	}

	protected function register_controls() {
		// $this->add_content_text_section();
		$this->add_content_form_fields_section();
		$this->add_content_button_section();
		$this->add_content_actions_after_submit_section();
		$this->add_content_additional_options_section();

		// $this->add_style_text_section();
		$this->add_style_form_section();
		$this->add_style_fields_section();
		$this->add_style_buttons_section();
		$this->add_style_messages_section();
		$this->add_style_box_section();
	}

	protected function add_content_text_section(): void {
		$this->start_controls_section(
			'section_text',
			[
				'label' => esc_html__( 'Text', 'cool-formkit' ),
			]
		);

		$this->add_control(
			'text_heading',
			[
				'label' => esc_html__( 'Heading', 'cool-formkit' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Contact Us', 'cool-formkit' ),
				'placeholder' => esc_html__( 'Add your text here', 'cool-formkit' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'text_heading_tag',
			[
				'label' => esc_html__( 'Heading HTML Tag', 'cool-formkit' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'text_description',
			[
				'label' => esc_html__( 'Description', 'cool-formkit' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Fill out the form below and we will contact you as soon as possible', 'cool-formkit' ),
				'placeholder' => esc_html__( 'Add your text here', 'cool-formkit' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_content_form_fields_section(): void {
		$repeater = new Repeater();

		$field_types = [
			'text' => esc_html__( 'Text', 'cool-formkit' ),
			'email' => esc_html__( 'Email', 'cool-formkit' ),
			'textarea' => esc_html__( 'Textarea', 'cool-formkit' ),
			'cool-tel' => esc_html__( 'Tel', 'cool-formkit' ),
			'select' => esc_html__( 'Select', 'cool-formkit' ),
			'cool-acceptance' => esc_html__( 'Acceptance', 'cool-formkit' ),
		];

		$repeater->start_controls_tabs( 'form_fields_tabs' );

		$repeater->start_controls_tab( 'form_fields_content_tab', [
			'label' => esc_html__( 'Content', 'cool-formkit' ),
		] );

		$repeater->add_control(
			'field_type',
			[
				'label' => esc_html__( 'Type', 'cool-formkit' ),
				'type' => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text',
			]
		);

		$repeater->add_control(
			'field_label',
			[
				'label' => esc_html__( 'Label', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'cool-tel',
								'text',
								'email',
								'textarea',
							],
						],
					],
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'required',
			[
				'label' => esc_html__( 'Required', 'cool-formkit' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default' => '',
			]
		);

		$repeater->add_control(
			'field_options',
			[
				'label' => esc_html__( 'Options', 'cool-formkit' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'description' => esc_html__( 'Enter each option in a separate line. To differentiate between label and value, separate them with a pipe char ("|"). For example: First Name|f_name', 'cool-formkit' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'select',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'allow_multiple',
			[
				'label' => esc_html__( 'Multiple Selection', 'cool-formkit' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'select_size',
			[
				'label' => esc_html__( 'Rows', 'cool-formkit' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 2,
				'step' => 1,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
						[
							'name' => 'allow_multiple',
							'value' => 'true',
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'width',
			[
				'label' => esc_html__( 'Column Width', 'cool-formkit' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'100' => '100%',
					'50' => '50%',
					'33' => '33%',
				],
				'default' => '100',
				'tablet_default' => '100',
				'mobile_default' => '100',
			]
		);

		$repeater->add_control(
			'rows',
			[
				'label' => esc_html__( 'Rows', 'cool-formkit' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'textarea',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'css_classes',
			[
				'label' => esc_html__( 'CSS Classes', 'cool-formkit' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => '',
				'title' => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'cool-formkit' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'form_fields_advanced_tab',
			[
				'label' => esc_html__( 'Advanced', 'cool-formkit' ),
				'condition' => [
					'field_type!' => 'html',
				],
			]
		);

		$repeater->add_control(
			'field_value',
			[
				'label' => esc_html__( 'Default Value', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => false,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'text',
								'email',
								'textarea',
								'tel',
								'select',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_id',
			[
				'label' => esc_html__( 'ID', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'description' => sprintf(
					/* translators: %1$s: Opening code tag, %2$s: Closing code tag. */
					esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page. This field allows %1$sA-z 0-9%2$s & underscore chars without spaces.', 'cool-formkit' ),
					'<code>',
					'</code>'
				),
				'render_type' => 'none',
				'required' => true,
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$shortcode_template = '{{ view.container.settings.get( \'custom_id\' ) }}';

		$repeater->add_control(
			'shortcode',
			[
				'label' => esc_html__( 'Shortcode', 'cool-formkit' ),
				'type' => Controls_Manager::RAW_HTML,
				'classes' => 'forms-field-shortcode',
				'raw' => '<input class="elementor-form-field-shortcode" value=\'[field id="' . $shortcode_template . '"]\' readonly />',
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => esc_html__( 'Form Fields', 'cool-formkit' ),
			]
		);

		$this->add_control(
			'form_name',
			[
				'label' => esc_html__( 'Form Name', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'New Form', 'cool-formkit' ),
				'placeholder' => esc_html__( 'Form Name', 'cool-formkit' ),
			]
		);

		$this->add_control(
			'form_fields',
			[
				'type' => Fields_Repeater::CONTROL_TYPE,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'custom_id' => 'name',
						'field_type' => 'text',
						'field_label' => esc_html__( 'Name', 'cool-formkit' ),
						'placeholder' => esc_html__( 'Name', 'cool-formkit' ),
						'width' => '100',
						'dynamic' => [
							'active' => true,
						],
					],
					[
						'custom_id' => 'email',
						'field_type' => 'email',
						'required' => 'true',
						'field_label' => esc_html__( 'Email', 'cool-formkit' ),
						'placeholder' => esc_html__( 'Email', 'cool-formkit' ),
						'width' => '100',
					],
					[
						'custom_id' => 'message',
						'field_type' => 'textarea',
						'field_label' => esc_html__( 'Message', 'cool-formkit' ),
						'placeholder' => esc_html__( 'Message', 'cool-formkit' ),
						'width' => '100',
					],
				],
				'title_field' => '{{{ field_label }}}',
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => esc_html__( 'Label', 'cool-formkit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'cool-formkit' ),
				'label_off' => esc_html__( 'Hide', 'cool-formkit' ),
				'return_value' => 'true',
				'default' => 'true',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mark_required',
			[
				'label' => esc_html__( 'Required Mark', 'cool-formkit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'cool-formkit' ),
				'label_off' => esc_html__( 'Hide', 'cool-formkit' ),
				'default' => '',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_content_button_section(): void {
		$this->start_controls_section(
			'section_buttons',
			[
				'label' => esc_html__( 'Button', 'cool-formkit' ),
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => esc_html__( 'Column Width', 'cool-formkit' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'100' => '100%',
					'50' => '50%',
					'33' => '33%',
				],
				'default' => '100',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Submit', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Send', 'cool-formkit' ),
				'placeholder' => esc_html__( 'Send', 'cool-formkit' ),
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'selected_button_icon',
			[
				'label' => esc_html__( 'Icon', 'cool-formkit' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'button_css_id',
			[
				'label' => esc_html__( 'Button ID', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'ai' => [
					'active' => false,
				],
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'cool-formkit' ),
				'description' => sprintf(
					/* translators: %1$s: Opening code tag, %2$s: Closing code tag. */
					esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page. This field allows %1$sA-z 0-9%2$s & underscore chars without spaces.', 'cool-formkit' ),
					'<code>',
					'</code>'
				),
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_content_actions_after_submit_section(): void {
		$this->start_controls_section(
			'section_integration',
			[
				'label' => esc_html__( 'Actions After Submit', 'cool-formkit' ),
			]
		);

		$this->add_control(
			'should_redirect',
			[
				'label' => esc_html__( 'Redirect To Thank You Page', 'cool-formkit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'cool-formkit' ),
				'label_off' => esc_html__( 'No', 'cool-formkit' ),
				'return_value' => 'true',
				'default' => '',
			]
		);

		$this->add_control(
			'redirect_to',
			[
				'label' => esc_html__( 'Redirect To', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'https://your-link.com', 'cool-formkit' ),
				'ai' => [
					'active' => false,
				],
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::TEXT_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'label_block' => true,
				'render_type' => 'none',
				'classes' => 'elementor-control-direction-ltr',
				'condition' => [
					'should_redirect' => 'true',
				],
			]
		);

		$this->add_control(
			'email_heading',
			[
				'label' => esc_html__( 'Email Submissions', 'cool-formkit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'email_to',
			[
				'label' => esc_html__( 'To', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => get_option( 'admin_email' ),
				'ai' => [
					'active' => false,
				],
				'placeholder' => get_option( 'admin_email' ),
				'label_block' => true,
				'title' => esc_html__( 'Separate emails with commas', 'cool-formkit' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		/* translators: %s: Site title. */
		$default_message = sprintf( esc_html__( 'New message from [%s]', 'cool-formkit' ), get_bloginfo( 'name' ) );

		$this->add_control(
			'email_subject',
			[
				'label' => esc_html__( 'Subject', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_message,
				'ai' => [
					'active' => false,
				],
				'placeholder' => $default_message,
				'label_block' => true,
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'email_content',
			[
				'label' => esc_html__( 'Message', 'cool-formkit' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '[all-fields]',
				'ai' => [
					'active' => false,
				],
				'placeholder' => '[all-fields]',
				'description' => sprintf(
				/* translators: %s: The [all-fields] shortcode. */
					esc_html__( 'By default, all form fields are sent via %s shortcode. To customize sent fields, copy the shortcode that appears inside each field and paste it above.', 'cool-formkit' ),
					'<code>[all-fields]</code>'
				),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$site_domain = Module::get_site_domain();

		$this->add_control(
			'email_from',
			[
				'label' => esc_html__( 'From Email', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'email@' . $site_domain,
				'ai' => [
					'active' => false,
				],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'email_from_name',
			[
				'label' => esc_html__( 'From Name', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => get_bloginfo( 'name' ),
				'ai' => [
					'active' => false,
				],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'email_reply_to',
			[
				'label' => esc_html__( 'Reply-To', 'cool-formkit' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => '',
				],
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'email_to_cc',
			[
				'label' => esc_html__( 'Cc', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'ai' => [
					'active' => false,
				],
				'title' => esc_html__( 'Separate emails with commas', 'cool-formkit' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'email_to_bcc',
			[
				'label' => esc_html__( 'Bcc', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'ai' => [
					'active' => false,
				],
				'title' => esc_html__( 'Separate emails with commas', 'cool-formkit' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'form_metadata',
			[
				'label' => esc_html__( 'Meta Data', 'cool-formkit' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'separator' => 'before',
				'default' => [
					'date',
					'time',
					'page_url',
					'user_agent',
					'remote_ip',
					'credit',
				],
				'options' => [
					'date' => esc_html__( 'Date', 'cool-formkit' ),
					'time' => esc_html__( 'Time', 'cool-formkit' ),
					'page_url' => esc_html__( 'Page URL', 'cool-formkit' ),
					'user_agent' => esc_html__( 'User Agent', 'cool-formkit' ),
					'remote_ip' => esc_html__( 'Remote IP', 'cool-formkit' ),
					'credit' => esc_html__( 'Credit', 'cool-formkit' ),
				],
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'email_content_type',
			[
				'label' => esc_html__( 'Send As', 'cool-formkit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'html',
				'render_type' => 'none',
				'options' => [
					'html' => esc_html__( 'HTML', 'cool-formkit' ),
					'plain' => esc_html__( 'Plain', 'cool-formkit' ),
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_content_additional_options_section(): void {
		$this->start_controls_section(
			'section_form_options',
			[
				'label' => esc_html__( 'Additional Options', 'cool-formkit' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'form_id',
			[
				'label' => esc_html__( 'Form ID', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'ai' => [
					'active' => false,
				],
				'placeholder' => 'new_form_id',
				'description' => sprintf(
					/* translators: %1$s: Opening code tag, %2$s: Closing code tag. */
					esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page. This field allows %1$sA-z 0-9%2$s & underscore chars without spaces.', 'cool-formkit' ),
					'<code>',
					'</code>'
				),
				'separator' => 'after',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'custom_messages',
			[
				'label' => esc_html__( 'Custom Messages', 'cool-formkit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'separator' => 'before',
				'render_type' => 'none',
			]
		);

		$default_messages = Ajax_Handler::get_default_messages();

		$this->add_control(
			'success_message',
			[
				'label' => esc_html__( 'Success Message', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::SUCCESS ],
				'placeholder' => $default_messages[ Ajax_Handler::SUCCESS ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'error_message',
			[
				'label' => esc_html__( 'Form Error', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::ERROR ],
				'placeholder' => $default_messages[ Ajax_Handler::ERROR ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'server_message',
			[
				'label' => esc_html__( 'Server Error', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::SERVER_ERROR ],
				'placeholder' => $default_messages[ Ajax_Handler::SERVER_ERROR ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'invalid_message',
			[
				'label' => esc_html__( 'Invalid Form', 'cool-formkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::INVALID_FORM ],
				'placeholder' => $default_messages[ Ajax_Handler::INVALID_FORM ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_text_section(): void {
		$this->start_controls_section(
			'section_text_style',
			[
				'label' => esc_html__( 'Text', 'cool-formkit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_align',
			[
				'label' => esc_html__( 'Align', 'cool-formkit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'cool-formkit' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'cool-formkit' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'cool-formkit' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-text-container-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_text',
			[
				'label' => esc_html__( 'Heading', 'cool-formkit' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Text Color', 'cool-formkit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-heading-color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .cool-form__heading',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'heading_description',
			[
				'label' => esc_html__( 'Description', 'cool-formkit' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Text Color', 'cool-formkit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-description-color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .cool-form__description',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_form_section(): void {
		$this->start_controls_section(
			'section_form_style',
			[
				'label' => esc_html__( 'Form', 'cool-formkit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'column_gap',
			[
				'label' => esc_html__( 'Columns Gap', 'cool-formkit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 32,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'max' => 60,
					],
					'em' => [
						'max' => 6,
					],
					'rem' => [
						'max' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => esc_html__( 'Rows Gap', 'cool-formkit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 32,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'max' => 60,
					],
					'em' => [
						'max' => 6,
					],
					'rem' => [
						'max' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_label',
			[
				'label' => esc_html__( 'Label', 'cool-formkit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label' => esc_html__( 'Spacing', 'cool-formkit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'max' => 60,
					],
					'em' => [
						'max' => 6,
					],
					'rem' => [
						'max' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-label-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => esc_html__( 'Text Color', 'cool-formkit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-label-color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_control(
			'mark_required_color',
			[
				'label' => esc_html__( 'Mark Color', 'cool-formkit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF0000',
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-mark-color: {{VALUE}};',
				],
				'condition' => [
					'mark_required' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .cool-form__field-label',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_fields_section(): void {
		$this->start_controls_section(
			'section_field_style',
			[
				'label' => esc_html__( 'Fields', 'cool-formkit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'input_size',
			[
				'label' => esc_html__( 'Input Size', 'cool-formkit' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => esc_html__( 'Extra Small', 'cool-formkit' ),
					'sm' => esc_html__( 'Small', 'cool-formkit' ),
					'md' => esc_html__( 'Medium', 'cool-formkit' ),
					'lg' => esc_html__( 'Large', 'cool-formkit' ),
					'xl' => esc_html__( 'Extra Large', 'cool-formkit' ),
				],
				'default' => 'sm',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label' => esc_html__( 'Text Color', 'cool-formkit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-field-text-color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'selector' => '{{WRAPPER}} .cool-form__field, {{WRAPPER}} .cool-form__field::placeholder',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label' => esc_html__( 'Background Color', 'cool-formkit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-field-bg-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_switcher',
			[
				'label' => esc_html__( 'Border', 'cool-formkit' ),
				'type' => Controls_Manager::SWITCHER,
				'options' => [
					'yes' => esc_html__( 'Yes', 'cool-formkit' ),
					'no' => esc_html__( 'No', 'cool-formkit' ),
				],
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_width',
			[
				'label' => esc_html__( 'Border Width', 'cool-formkit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 10,
					],
					'rem' => [
						'max' => 10,
					],
				],
				'default' => [
					'size' => 2,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-field-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'field_border_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'field_border_color',
			[
				'label' => esc_html__( 'Border Color', 'cool-formkit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-field-border-color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'separator' => 'before',
				'condition' => [
					'field_border_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'fields_shape',
			[
				'label' => esc_html__( 'Shape', 'cool-formkit' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => 'Default',
					'sharp' => 'Sharp',
					'rounded' => 'Rounded',
					'round' => 'Round',
				],
				'default' => 'default',
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_buttons_section(): void {
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'cool-formkit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_type',
			[
				'label' => esc_html__( 'Type', 'cool-formkit' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'button' => esc_html__( 'Button', 'cool-formkit' ),
					'link' => esc_html__( 'Link', 'cool-formkit' ),
				],
				'default' => 'button',
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label' => esc_html__( 'Position', 'cool-formkit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'cool-formkit' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'cool-formkit' ),
						'icon' => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'cool-formkit' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-button-align: {{VALUE}};',
				],
				'condition' => [
					'button_width!' => '100',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .cool-form__button',
			]
		);

		$start = is_rtl() ? 'right' : 'left';
		$end = is_rtl() ? 'left' : 'right';

		$this->add_control(
			'button_icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'cool-formkit' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => is_rtl() ? 'row-reverse' : 'row',
				'options' => [
					'row' => [
						'title' => esc_html__( 'Start', 'cool-formkit' ),
						'icon' => "eicon-h-align-{$start}",
					],
					'row-reverse' => [
						'title' => esc_html__( 'End', 'cool-formkit' ),
						'icon' => "eicon-h-align-{$end}",
					],
				],
				'selectors_dictionary' => [
					'left' => is_rtl() ? 'row-reverse' : 'row',
					'right' => is_rtl() ? 'row' : 'row-reverse',
				],
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-button-icon-position: {{VALUE}};',
				],
				'condition' => [
					'selected_button_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'cool-formkit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 10,
					],
					'rem' => [
						'max' => 10,
					],
				],
				'default' => [
					'size' => 8,
					'unit' => 'px',
				],
				'condition' => [
					'selected_button_icon[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-button-icon-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'cool-formkit' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'cool-formkit' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-button-text-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .is-type-button.cool-form__button',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					],
				],
				'condition' => [
					'button_type' => 'button',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'cool-formkit' ),
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'cool-formkit' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-button-text-color-hover: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .is-type-button.cool-form__button:hover, {{WRAPPER}} .is-type-button.cool-form__button:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					],
				],
				'condition' => [
					'button_type' => 'button',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'cool-formkit' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_switcher',
			[
				'label' => esc_html__( 'Border', 'cool-formkit' ),
				'type' => Controls_Manager::SWITCHER,
				'options' => [
					'yes' => esc_html__( 'Yes', 'cool-formkit' ),
					'no' => esc_html__( 'No', 'cool-formkit' ),
				],
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'cool-formkit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 10,
					],
					'rem' => [
						'max' => 10,
					],
				],
				'default' => [
					'size' => 2,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-button-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'button_border_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'cool-formkit' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-button-border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_shape',
			[
				'label' => esc_html__( 'Shape', 'cool-formkit' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => 'Default',
					'sharp' => 'Sharp',
					'rounded' => 'Rounded',
					'round' => 'Round',
				],
				'default' => 'default',
			]
		);

		$this->add_responsive_control(
			'button_text_padding',
			[
				'label' => esc_html__( 'Padding', 'cool-formkit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => '8',
					'right' => '40',
					'bottom' => '8',
					'left' => '40',
					'unit' => 'px',
				],
				'mobile_default' => [
					'top' => '8',
					'right' => '40',
					'bottom' => '8',
					'left' => '40',
					'unit' => 'px',
				],
				'tablet_default' => [
					'top' => '8',
					'right' => '40',
					'bottom' => '8',
					'left' => '40',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-button-padding-block-end: {{BOTTOM}}{{UNIT}}; --cool-form-button-padding-block-start: {{TOP}}{{UNIT}}; --cool-form-button-padding-inline-end: {{RIGHT}}{{UNIT}}; --cool-form-button-padding-inline-start: {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_messages_section(): void {
		$this->start_controls_section(
			'section_messages_style',
			[
				'label' => esc_html__( 'Messages', 'cool-formkit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'message_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .elementor-message',
			]
		);

		$this->add_control(
			'success_message_color',
			[
				'label' => esc_html__( 'Success Message Color', 'cool-formkit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-message-success' => 'color: {{COLOR}};',
				],
			]
		);

		$this->add_control(
			'error_message_color',
			[
				'label' => esc_html__( 'Error Message Color', 'cool-formkit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-message-danger' => 'color: {{COLOR}};',
				],
			]
		);

		$this->add_control(
			'inline_message_color',
			[
				'label' => esc_html__( 'Inline Message Color', 'cool-formkit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-help-inline' => 'color: {{COLOR}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_box_section(): void {
		$this->start_controls_section(
			'section_box_style',
			[
				'label' => esc_html__( 'Box', 'cool-formkit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'box_heading',
			[
				'label' => esc_html__( 'Background', 'cool-formkit' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'box_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .cool-form',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_responsive_control(
			'content_width',
			[
				'label' => esc_html__( 'Content Width', 'cool-formkit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 1600,
					],
					'%' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 640,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 640,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 320,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-content-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => esc_html__( 'Padding', 'cool-formkit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .cool-form' => '--cool-form-box-padding-block-end: {{BOTTOM}}{{UNIT}}; --cool-form-box-padding-block-start: {{TOP}}{{UNIT}}; --cool-form-box-padding-inline-end: {{RIGHT}}{{UNIT}}; --cool-form-box-padding-inline-start: {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => '60',
					'right' => '60',
					'bottom' => '60',
					'left' => '60',
					'unit' => 'px',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}
}
