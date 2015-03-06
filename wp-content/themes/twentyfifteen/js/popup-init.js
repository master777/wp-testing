jQuery(document).ready( function() {

  var colorbox_config = {
    inline: true,
    width: "430px",
    onOpen: function() { 
      var page = jQuery(this).data('page');
      jQuery("#fs_success_page").val(page);
    }
  };

  jQuery(".sign_up").colorbox(colorbox_config);
  jQuery(".log_in").colorbox(colorbox_config);

  if (jQuery(".wpjb-job-apply .wpjb-flash-error span").html() == "Only registered members can apply for jobs.") {
    jQuery(".wpjb-job-apply .wpjb-flash-error span").html("Only community members can see details and apply for jobs.");
  }

  var wpjb_buttons = jQuery(".wpjb-job-apply > div > a.wpjb-button ");

  jQuery.each( wpjb_buttons, function( key, value ) {

    var wpjb_button = jQuery(value);

    switch(wpjb_button.html()) {
      case "Login":
        //wpjb_button.addClass("sign_up");
        wpjb_button.attr("href", "#sign_up");
        wpjb_button.html("Join");
        wpjb_button.colorbox(colorbox_config);
        break;
      case "Register":
        wpjb_button.removeClass("wpjb-button cboxElement");
        //wpjb_button.addClass("log_in");
        wpjb_button.attr("href", "#log_in");
        wpjb_button.html("Login");
        wpjb_button.colorbox(colorbox_config);
        break;
    }
  });

  // Ajax Request - Save Data
  jQuery('#register_button').click( function( evt ) {
    console.log(jQuery("#fs_success_page").val());

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

        jQuery.colorbox.resize();

        jQuery('html,body').animate({
          scrollTop: jQuery("#sign_up").offset().top
        },'fast');

      } else {

        if (response.success) {
          jQuery("#register_form #success_text").html(response.success);
          jQuery("#register_form .form-success").show();

          jQuery.colorbox.resize();

          jQuery('html,body').animate({
            scrollTop: jQuery("#sign_up").offset().top
          },'fast');
        }

        if (response.registered === true || response.logged_in === true) {
          setTimeout( function() {
            
            if ( jQuery("#fs_success_page").val() ) {
              document.location.href = jQuery("#fs_success_page").val();
            } else {
              document.location.reload();
            }
            
          }, 2000);         
        }
      }
    });
  });
  
  // Ajax Request - Login Action
  jQuery('#login_button').click( function( evt ) {
    console.log(jQuery("#fs_success_page").val());

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

        jQuery.colorbox.resize();

      } else {

        if (response.success) {
          jQuery("#login_form #login_success_text").html(response.success);
          jQuery("#login_form .form-success").show();

          jQuery.colorbox.resize();
        }

        if (response.logged_in === true) {
          setTimeout( function() {
            
            if ( jQuery("#fs_success_page").val() ) {
              document.location.href = jQuery("#fs_success_page").val();
            } else {
              document.location.reload();
            }
            
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