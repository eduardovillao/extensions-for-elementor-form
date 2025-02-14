module.exports = elementorModules.editor.utils.Module.extend( {

	renderField( inputField, item, i, settings ) {
		var itemClasses = _.escape( item.css_classes ),
			required = '',
			label = '',
			checked = '';

		if ( item.required ) {
			required = 'required';
		}

		if ( item.acceptance_text ) {
			label = '<label for="form_field_' + i + '" class="cool-form__field-label">' + item.acceptance_text + '</label>';
		}

		if ( item.checked_by_default ) {
			checked = ' checked="checked"';
		}

		return '<div class="elementor-field-subgroup">' +
			'<span class="elementor-field-option">' +
			'<input size="1" type="checkbox"' + checked + ' class="elementor-acceptance-field elementor-field elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' > ' + label + '</span></div>';
	},

	onInit() {
		elementor.hooks.addFilter( 'cool_formkit/forms/content_template/field/acceptance', this.renderField, 10, 4 );
	},
} );
