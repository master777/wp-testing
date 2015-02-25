jQuery(document).ready( function() {
	
	jQuery(".sign_up").colorbox({
		inline: true,
		width: "500px" // "50%"
	});

	// Ajax Request - Save Data
	jQuery('#register_button').click( function( evt ) {
		evt.preventDefault();

		if (jQuery("#register_form input[name='agree']:checked").val() != 1) {
			alert("You must agree to the Terms and Conditions!");			
			return false;
		}

		// Limpiamos el mensaje de error y lo ocultamos
		jQuery("#error_text").text();
		jQuery(".form-error").hide();

		var security_code = WPURLS.ajax_register_nonce;

		// Creamos el objecto
		var data = {
			action: 'save_data_action', // es el nombre de la accion "wp_ajax_save_data_action" sin el "wp_ajax_"
			username: jQuery("#register_form input[name='username']").val(),
			password: jQuery("#register_form input[name='password']").val(),
			email: jQuery("#register_form input[name='email']").val(),
			firstname: jQuery("#register_form input[name='firstname']").val(),
			lastname: jQuery("#register_form input[name='lastname']").val(),
			career_situation: jQuery("#register_form input[name='career-situation']:checked").val(),
			nonce: security_code
		}

		console.log("data");
		console.log(data);

		jQuery.post(WPURLS.ajaxurl, data, function(response) {
			console.log("response");
			console.log(response);

			if (response.registered === true || response.logged_in === true) {
				console.log("Registration Complete!");

				jQuery("#success_text").text("Registration Complete!");
				jQuery(".form-success").show();

				jQuery('html,body').animate({
					scrollTop: jQuery("#sign_up").offset().top
				},'fast');

				setTimeout( function() {
					document.location.href = WPURLS.home;
					//document.location.href = WPURLS.admin_profile;
				}, 2000);

			} else if (response.error) {
				console.log("Error: " + response.error);

				jQuery("#error_text").text(response.error);
				jQuery(".form-error").show();

				jQuery('html,body').animate({
					scrollTop: jQuery("#sign_up").offset().top
				},'fast');
			}
		});
	});

});