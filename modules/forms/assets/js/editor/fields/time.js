module.exports = elementorModules.editor.utils.Module.extend( {

    renderField(inputField, item, i, settings) {
        var itemClasses = _.escape(item.css_classes),
          required = '',
          placeholder = '';
        if (item.required) {
          required = 'required';
        }
        if (item.placeholder) {
          placeholder = ' placeholder="' + item.placeholder + '"';
        }
        if ('yes' === item.use_native_time) {
          itemClasses += ' cool-form-use-native';
        }
        return '<input size="1" type="time"' + placeholder + ' class="elementor-field-textual cool-form-time-field elementor-field elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' >';
      },    

	onInit() {
		elementor.hooks.addFilter( 'cool_formkit/forms/content_template/field/time', this.renderField, 10, 4 );
	},
} );
