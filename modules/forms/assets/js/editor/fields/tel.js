module.exports = elementorModules.editor.utils.Module.extend( {

	renderField( inputField, item, i, settings ) {
		var itemClasses = _.escape( item.css_classes ),
			required = '',
			placeholder = '';

		if ( item.required ) {
			required = 'required';
		}

		if ( item.placeholder ) {
			placeholder = ' placeholder="' + item.placeholder + '"';
		}

		itemClasses = 'elementor-field-textual ' + itemClasses;

		return '<input size="1" type="' + item.field_type + '" class="elementor-field-textual elementor-field elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' ' + placeholder + ' pattern="[0-9()-]" >';
	},

	onInit() {
		elementor.hooks.addFilter( 'cool_formkit/forms/content_template/field/tel', this.renderField, 10, 4 );
	},
} );
