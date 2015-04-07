jQuery(document).ready( function() {
  var popup_width = "530px";
  var colorbox_config = {
    inline: true,
    width: popup_width,
    onOpen: function() { 
      var page = jQuery(this).data('page');
      var description = jQuery(this).data('description');
      jQuery("#fs_success_page").val(page);
      jQuery("#fs_description").val(description);
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
        wpjb_button.addClass("sign_up");
        wpjb_button.attr("href", "#sign_up");
        wpjb_button.html("Join");
        wpjb_button.colorbox(colorbox_config);
        break;
      case "Register":
        wpjb_button.removeClass("wpjb-button cboxElement");
        wpjb_button.addClass("log_in");
        wpjb_button.attr("href", "#log_in");
        wpjb_button.html("Login");
        wpjb_button.colorbox(colorbox_config);
        break;
    }
  });

  // Para abrir automaticamente el popup si el hashtag esta en la url
  var hash = window.location.hash;
  setTimeout( function() {
    switch(hash) {
      case "#sign_up":
        jQuery.colorbox({
          inline: true, 
          width: popup_width,
          href: "#sign_up"
        });
        break;
      case "#log_in":
        jQuery.colorbox({
          inline: true, 
          width: popup_width,
          href: "#log_in"
        });
        break;
    }
  }, 500);

  // Ajax Request - Save Data
  jQuery('#register_button').click( function( evt ) {
    
    if (jQuery("#register_form input[name='agree']:checked").val() != 1) {
      alert("You must agree to the Terms and Conditions!");     
      return false;
    }

    var security_code = WPURLS.ajax_register_nonce;

    var data = {
      action: 'save_data_action', // es el nombre de la accion "wp_ajax_save_data_action" sin el "wp_ajax_"
      username: jQuery("#register_form input[name='username']").val(),
      password: jQuery("#register_form input[name='password']").val(),
      email: jQuery("#register_form input[name='email']").val(),
      firstname: jQuery("#register_form input[name='firstname']").val(),
      lastname: jQuery("#register_form input[name='lastname']").val(),
      career_situation: jQuery("#register_form #career-situation").val(),
      career_advice: jQuery("#register_form input[name='career-advice']:checked").val(),
      //career_situation: jQuery("#register_form input[name='career-situation']:checked").val(),
      //career_advice: jQuery("#register_form #career-advice").val(),
      nonce: security_code
    }

    jQuery.post(WPURLS.ajaxurl, data, function(response) {

      jQuery("#register_form #error_text").html();
      jQuery(".form-error").hide();

      if (response.error) {
        
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
          // === Tracking with Google Analytics ===
          if (typeof ga !== 'undefined') {
            var current_page = window.location.href;
            var description = jQuery("#fs_description").val();
            ga('send', 'event', 'Job Seeker Registration', current_page, description);
          }
          // ===

          setTimeout( function() {
            
            if ( jQuery("#fs_success_page").val() ) {
              document.location.href = jQuery("#fs_success_page").val();
            } else {
              window.location.hash = "";
              document.location.reload();
            }
            
          }, 2000);         
        }
      }
    });
  });
  
  // Ajax Request - Login Action
  jQuery('#login_button').click( function( evt ) {

    var data = {
      action: 'login_action', // es el nombre de la accion "wp_ajax_login_action" sin el "wp_ajax_"
      username: jQuery("form#login_form #username").val(),
      password: jQuery("form#login_form #password").val(),
      nonce: jQuery("form#login_form #security_code").val(),
      remember: jQuery("form#login_form #remember_me").val(),
    };

    jQuery.post(WPURLS.ajaxurl, data, function(response) {

      jQuery("#login_form #login_error_text").html();
      jQuery(".form-error").hide();

      if (response.error) {

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
          // === Tracking with Google Analytics ===
          if (typeof ga !== 'undefined') {
            var current_page = window.location.href;
            var description = jQuery("#fs_description").val();
            ga('send', 'event', 'User Login', current_page, description);
          }
          // ===

          setTimeout( function() {
            
            if ( jQuery("#fs_success_page").val() ) {
              document.location.href = jQuery("#fs_success_page").val();
            } else {
              window.location.hash = "";
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

  // === Tracking with Google Analytics ===
  if (typeof ga !== 'undefined') {
    jQuery('.sign_up').on('click', function() {
      ga('send', 'event', 'Button', 'Click', 'Popup to register');
    });

    jQuery('.log_in').on('click', function() {
      ga('send', 'event', 'Button', 'Click', 'Popup to login');
    });
  }

});