jQuery(document).ready( function() {
	
  jQuery(".sign_up").colorbox({
    inline: true,
    width: "500px"
    /*,
    onOpen: function() { 
      var page = jQuery(this).data('page');
      jQuery("#fs_success_page").val(page);
    }*/
  });
  jQuery(".log_in").colorbox({
    inline: true,
    width: "500px"
    /*,
    onOpen: function() {
      var page = jQuery(this).data('page');
      jQuery("#fs_success_page").val(page);
    }*/
  });

	// Ajax Request - Save Data
	jQuery('#register_button').click( function( evt ) {
    //console.log(jQuery("#fs_success_page").val());

		if (jQuery("#register_form input[name='agree']:checked").val() != 1) {
			alert("You must agree to the Terms and Conditions!");			
			return false;
		}

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

		jQuery.post(WPURLS.ajaxurl, data, function(response) {

			// Limpiamos y ocultamos la seccion de errores
			jQuery("#register_form #error_text").html();
			jQuery(".form-error").hide();

			if (response.error) {
				// Mostramos el error
				jQuery("#register_form #error_text").html(response.error);
				jQuery("#register_form .form-error").show();

				jQuery('html,body').animate({
					scrollTop: jQuery("#sign_up").offset().top
				},'fast');

			} else {

				if (response.success) {
					jQuery("#register_form #success_text").html(response.success);
					jQuery("#register_form .form-success").show();

					jQuery('html,body').animate({
						scrollTop: jQuery("#sign_up").offset().top
					},'fast');
				}

				if (response.registered === true || response.logged_in === true) {
					setTimeout( function() {
						document.location.reload();
            /*
            if ( jQuery("#fs_success_page").val() ) {
              document.location.href = jQuery("#fs_success_page").val();
            } else {
              document.location.reload();
            }
            */
						//document.location.href = WPURLS.home;
						//document.location.href = WPURLS.admin_profile;
					}, 2000);					
				}
			}
		});
	});
  
  // Ajax Request - Login Action
  jQuery('#login_button').click( function( evt ) {
    //console.log(jQuery("#fs_success_page").val());

    var data = {
      action: 'login_action', // es el nombre de la accion "wp_ajax_login_action" sin el "wp_ajax_"
      username: jQuery("form#login_form #username").val(),
      password: jQuery("form#login_form #password").val(),
      nonce: jQuery("form#login_form #security_code").val(),
      remember: jQuery("form#login_form #remember_me").val(),
    };

    jQuery.post(WPURLS.ajaxurl, data, function(response) {

      // Limpiamos y ocultamos la seccion de errores
      jQuery("#login_form #login_error_text").html();
      jQuery(".form-error").hide();

      if (response.error) {
        // Mostramos el error
        jQuery("#login_form #login_error_text").html(response.error);
        jQuery("#login_form .form-error").show();

        jQuery('html,body').animate({
          scrollTop: jQuery("#log_in").offset().top
        },'fast');

      } else {

        if (response.success) {
          jQuery("#login_form #login_success_text").html(response.success);
          jQuery("#login_form .form-success").show();

          jQuery('html,body').animate({
            scrollTop: jQuery("#sign_up").offset().top
          },'fast');
        }

        if (response.logged_in === true) {
          setTimeout( function() {
            document.location.reload();
            /*
            if ( jQuery("#fs_success_page").val() ) {
              document.location.href = jQuery("#fs_success_page").val();
            } else {
              document.location.reload();
            }
            */
          }, 1000);          
        }
      }
    });
  });
  
  jQuery("#register_form").keypress(function (e) {
    if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
      jQuery('#register_button').click();
      return false;
    } else {
      return true;
    }
  });

  jQuery("#login_form").keypress(function (e) {
    if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
      jQuery('#login_button').click();
      return false;
    } else {
      return true;
    }
  });

});