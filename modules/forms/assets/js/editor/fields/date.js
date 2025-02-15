module.exports = elementorModules.editor.utils.Module.extend( {

    renderField(inputField, item, i, settings) {
        var itemClasses = _.escape(item.css_classes),
          required = '',
          min = '',
          max = '',
          placeholder = '';
        if (item.required) {
          required = 'required';
        }
        if (item.min_date) {
          min = ' min="' + item.min_date + '"';
        }
        if (item.max_date) {
          max = ' max="' + item.max_date + '"';
        }
        if (item.placeholder) {
          placeholder = ' placeholder="' + item.placeholder + '"';
        }
        if ('yes' === item.use_native_date) {
          itemClasses += ' cool-form-use-native';
        }
        return '<input size="1"' + min + max + placeholder + ' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" type="date" class="elementor-field-textual cool-form-date-field elementor-field elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' >';
      },

	onInit() {
		elementor.hooks.addFilter( 'cool_formkit/forms/content_template/field/date', this.renderField, 10, 4 );
	},
} );
