jQuery(document).ready( function() {
	
	jQuery(".sign_up").colorbox({
		inline: true, 
		width: "50%"
	});

	// Ajax Request - Save Data
	jQuery('#register_button').click( function( evt ) {
		if (jQuery("#register_form input[name='agree']:checked").val() != 1) {
			alert("You must agree to the Terms and Conditions!");			
			return false;
		}

		var security_code = WPURLS.ajax_register_nonce;
		//var frm = jQuery('#register_form').serialize();

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

			if (response.logged_in === true) {
				document.location.href = WPURLS.home;
				//document.location.href = WPURLS.admin_profile;
			} else if (response.error) {
				console.log("Error: " + response.error);
			}
		});
	});

});