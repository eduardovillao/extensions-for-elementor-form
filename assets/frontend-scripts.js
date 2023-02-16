jQuery( document ).ready( function( $ ) {

	$('.elementor-form').submit( function( event ) {
		
		var form = $(this);
		var form_id = $(this).find('input[name="form_id"]').val();		
		$(form).addClass(form_id);
						
		$(document).ajaxSuccess( function( event ) {

			if ($('.elementor-form-fields-wrapper').hasClass( "ele-extensions-hide-form" ) != false ) {

					$(form).find('.ele-extensions-hide-form').hide();
			}

			if ($('.' + form_id).prev().hasClass( "custom-sucess-message" ) != false ) {

					$('.elementor-message-success').hide();
					$('.' + form_id).prev('.custom-sucess-message').delay( 100 ).fadeIn();
			}
		})
	})
});