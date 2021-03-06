<?php

function add_custom_menu( $nav, $args ) {
	
	if( $args->theme_location == 'primary' && !is_user_logged_in() ) {
		return "<li class='menu-item'><a class='sign_up' href='#sign_up' data-description='<<Become a Member>> Button'>Become a Member!</a></li>" . $nav;
	}

	/*
	if( $args->theme_location == 'login' && !is_user_logged_in() ) {
    return "<li class='orange-menu menu-item'><a class='sign_up' href='#sign_up'>Become a Member!</a></li>". $nav;
  }
	*/
	return $nav;
}
add_filter('wp_nav_menu_items','add_custom_menu', 10, 2);

// Agregamos un shortcode para crear un boton que muestre el popup
function add_signup_button($params = array()) {
  $params = shortcode_atts( array(
    "name" => "Register Now",
    "login" => false,
    "page" => "",
    "img" => "",
    "description" => "",
  ), $params);

  $button_action = $params['login'] && $params['login'] != "false" ? $button_action = "log_in" : $button_action = "sign_up";

  if ($params['img']) {
    $button_content =  "<img src='" . $params['img'] . "' alt='" . $button_action . "' style='margin: 0 auto;'>";
    $orange_button = "";
  } else {
    $button_content = $params['name'];
    $orange_button = "orange-button";
  }

  if (!is_user_logged_in()) {
    
    return "<div style='text-align: center;'><a class='" . $button_action . " {$orange_button}' href='#" . $button_action . "' data-page='" . $params['page'] . "' data-description='" . $params['description'] . "'>" . $button_content . "</a></div>";

  } else {
    
    if (!empty($params['page'])) {
      return "<div style='text-align: center;'><a class='{$orange_button}' href='" . $params['page'] . "'  data-description='" . $params['description'] . "'>" . $button_content . "</a></div>";
    } else {
      return "<div style='text-align: center;'><a class='" . $button_action . " {$orange_button}' href='#" . $button_action . "' data-page='" . $params['page'] . "' data-description='" . $params['description'] . "'>" . $button_content . "</a></div>";
    }

  }
}
add_shortcode( 'signup_popup', 'add_signup_button' );

// Incorporamos en el frontend los css y js que necesitamos para el popup
function add_custom_style($hook) {
  wp_enqueue_style( 'fs-forms.css', get_template_directory_uri() . '/css/fs-forms.css' );
  wp_enqueue_style( 'colorbox.css', get_template_directory_uri() . '/css/colorbox.css' );
  wp_enqueue_script( 'jquery.colorbox-min.js', get_template_directory_uri() . '/js/jquery.colorbox-min.js' );
  wp_enqueue_script( 'popup-init.js', get_template_directory_uri() . '/js/popup-init.js' ); 

  wp_localize_script( 'popup-init.js', 'WPURLS', array(
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
    'ajax_register_nonce' => wp_create_nonce( 'ajax_register_nonce' )    
  ));
}
add_action( 'wp_enqueue_scripts', 'add_custom_style' );

function send_welcome_email( $user_id ) {
  $user = get_userdata( $user_id );
  $admin_email = get_option('admin_email');

  // Permitimos contenido HTML en el wp_mail
  function set_html_content_type() {
    return 'text/html';
  }
  add_filter( 'wp_mail_content_type', 'set_html_content_type' );

  $message = "<div>
  <p><strong>Registration Details</strong></p>
  <p><strong>First Name:</strong> {$user->first_name}</p>
  <p><strong>Last Name:</strong> {$user->last_name}</p>
  <p><strong>Username:</strong> {$user->user_login}</p>
  <p><strong>E-mail:</strong> {$user->user_email}</p>
  <br/>
  <p>Log in the <a href='". site_url(). "/wp-login.php?redirect_to=" . urlencode( site_url() . "/wp-admin/users.php?role=subscriber&orderby=ID&order=desc") . "' target='_blank'>wp admin</a> for details.</p>
  </div>";  

  @wp_mail( $admin_email, __('New job seeker registration on FindSpark'), $message);  

  $message = "<div>
  Hi {$user->first_name}, <br/>
  Congratulations! You're now a part of our community of thousands of ambitious young creatives. <br/>
  Start enjoying the perks! <a href='" . site_url() . "/events' target='_blank'>Attend our events</a> (in-person in NYC and virtual for those of you outside NYC), check out and apply for the best internships and entry-level jobs in NYC on our <a href='" . site_url() . "/jobs' target='_blank'>highly-curated job board</a>, and <a href='" . site_url() . "/blog' target='_blank'>read our blog</a> for tips geared specifically to you.<br/>
  <br/>
  Your user name is: <strong>{$user->user_login}</strong> <br/>
  Your password is hidden for your protection. <br/>
  <br/>
  To login to FindSpark, visit <a href='" . site_url() . "/login'>" . site_url() . "/login</a>. If you forgot your password, <a href='" . site_url() . "/wp-login.php?action=lostpassword&amp;redirect_to=" . urlencode( site_url() ) ."'>click here</a>. <br/>
  <br/>
  If you ever have suggestions or feedback, reach out to me at emily@findspark.com or Tweet at us <a href='https://twitter.com/findspark' target='_blank'>@FindSpark</a> <br/>
  <br/>
  To Career Optimism, <br/>
  ++++++++++++++++++++++++++++++++++ <br/>
  <font color='#888888'><br>
  Emily Miethner | <a href='http://linkedin.com/in/EmilyMiethner' target='_blank'>LinkedIn</a> // <a href='https://twitter.com/EmilyMiethner' target='_blank'>Twitter</a> <br/>
  Founder // <a href='https://findspark.com' target='_blank'>FindSpark</a>
  </font>
  </div>";

  wp_mail($user->user_email, __('Welcome to FindSpark!'), $message);

  // Reestablecemos la configuracion del wp_mail para evitar conflictos con otros plugins
  remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
}

function ajax_save_data() {
  $result = array();

  // Mailchimp account to test
  //$apikey = '473b279b7df5de614de37d3a832274df-us10';
  //$listID = 'f7a9903d4b';

  // Findspark Mailchimp - Register Form Popup (API key)
  $apikey = '8c269ac18731a63cfaf418c47f7a957e-us2';
  $listID = '8d8300cb5d';

  // Tabla con los valores correspondientes de los "Grupos" de mailchimp
  $mc_conversion_table = array(
    "career_situation" => array(
      "id" => 10505,
      "name" => "Career Status",      
      "values" => array(
        1 => "Full-time student, seeking internships",
        2 => "Full-time student, seeking first full-time job",
        3 => "Unemployed, seeking full-time employment",
        4 => "Employed, seeking new full-time opportunities",
        5 => "Employed, open to new opportunities"
      )
    ),
    "career_advice" => array(
      "id" => 10465,
      "name" => "How much career advice do you want?",
      "values" => array(
        1 => "Just send me your awesome Weekly Opportunities Newsletter",
        2 => "Give me all the great career advice as you have it!"
      )
    )
  );

  // Verificamos si existe un usuario logueado en el sistema
  if ( is_user_logged_in() ) {
    $result['registered'] = false;
    $result['logged_in'] = true;
    $result['success'] = "You're already logged in...";
  } else {
    if ( !wp_verify_nonce( $_POST['nonce'], 'ajax_register_nonce' ) ) {
      $result['error'] = "Invalid security code. Please reload the page and try again!";
    } else {
      $username = sanitize_text_field($_POST['username']);
      $password = sanitize_text_field($_POST['password']);
      $email = sanitize_text_field($_POST['email']);
      $firstname = sanitize_text_field($_POST['firstname']);
      $lastname = sanitize_text_field($_POST['lastname']);

      // Custom Fields
      $career_situation = sanitize_text_field($_POST['career_situation']);
      $career_advice = sanitize_text_field($_POST['career_advice']);

      // Validamos los campos
      if (empty($firstname)) {
        $result['error'] = "The first name is required!";
      } else if (!preg_match("/^[a-zA-Z ]*$/", $firstname)) {
        $result['error'] = "Please only enter letters in your first name!";
      } else if (empty($lastname)) {
        $result['error'] = "The last name is required!";
      } else if (!preg_match("/^[a-zA-Z ]*$/", $lastname)) {
        $result['error'] = "Please only enter letters in your last name!";
      } else if (empty($username)) {
        $result['error'] = "The username is required!";
      } else if (empty($email)) {
        $result['error'] = "The email address is required!";
      } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result['error'] = "Invalid email address!";
      } else if (empty($password)) {
        $result['error'] = "The password is required!";
      } else if (strlen($password) < 6) {
        $result['error'] = "The password must be at least 6 characters!";
      } else if (empty($career_situation)) {
        $result['error'] = "Please select an option in your career situation!";
      } else if (empty($career_advice)) {
        $result['error'] = "Please select an option in your career advice!";
      } else {
        // Intentamos registrar al usuario
        $user = array(
          'user_login' => $username,
          'user_pass' => $password,
          'first_name' => $firstname,
          'last_name' => $lastname,
          'display_name' => $firstname . ' ' . $lastname,
          'user_email' => $email,
          'role' => 'subscriber'
        );

        $user_id = wp_insert_user($user);
        if ( !is_wp_error( $user_id ) ) {
          $result['registered'] = true;
          $result['success'] = "Registration complete!";

          // Registramos los campos faltantes
          update_user_meta( $user_id, 'career_situation', $career_situation );
          update_user_meta( $user_id, 'career_advice', $career_advice );

          //===========
          if (!empty($apikey) && !empty($listID)) {
            require 'MailChimp.php';

            // Registramos en mailchimp
            $mailChimp = new \Drewm\MailChimp($apikey);
            $mc_args = array(
              'id'                => $listID,
              'email'             => array('email'=> $user['user_email']),
              'merge_vars'        => array(
                'FNAME'=> $user['first_name'], 
                'LNAME'=> $user['last_name'],
                'GROUPINGS' => array(
                  array(
                    'id' => $mc_conversion_table['career_situation']['id'],
                    //'name' => $mc_conversion_table['career_situation']['name'], // Si se especifica el "id" ya no se necesita el "name"
                    'groups' => array(
                      $mc_conversion_table['career_situation']['values'][$career_situation]
                    )
                  ),
                  array(
                    'id' => $mc_conversion_table['career_advice']['id'],
                    //'name' => $mc_conversion_table['career_advice']['name'], // Si se especifica el "id" ya no se necesita el "name"
                    'groups' => array(
                      $mc_conversion_table['career_advice']['values'][$career_advice]
                    )
                  ),
                )
              ),
              'double_optin'      => false,
              'update_existing'   => true,
              'replace_interests' => false,
              'send_welcome'      => false,
            );
            $mc_result = $mailChimp->call('lists/subscribe', $mc_args);          
            //$result['mailchimp_args'] = $mc_args;
            $result['mailchimp'] = $mc_result;            
          }
          //===========

          // Notificamos por correo al usuario y al admin
          send_welcome_email( $user_id, $password );

          // Logueamos al usuario en el sitio
          $cred = array(
            'user_login' => $username,
            'user_password' => $password,
            'remember' => false
          );

          // Removemos todas los "hooks" pertenecientes al fallo de inicio de sesion
          remove_all_actions( 'wp_login_failed' );

          $logged_user = wp_signon( $cred, false );

          if ( is_wp_error( $logged_user ) ) {
            $result['error'] = $logged_user->get_error_message();
          } else {
            $result['logged_in'] = true;
          }
        } else {
          $result['error'] = $user_id->get_error_message();
          //$result['error_code'] = $user_id->get_error_codes();
        }
      }
    }
  }

  header( "Content-Type: application/json" );
  echo json_encode( $result );

  exit();
}
add_action( 'wp_ajax_nopriv_save_data_action', 'ajax_save_data' );
add_action( 'wp_ajax_save_data_action', 'ajax_save_data' );

function ajax_login_action() {
  $result = array();

  if (is_user_logged_in()) {
    $result['logged_in'] = true;
    $result['success'] = "You're already logged in...";
  } else {
    if ( !wp_verify_nonce( $_POST['nonce'], 'ajax_login_nonce' ) ) {
      $result['error'] = "Invalid security code. Please reload the page and try again!";
    } else {
      $cred = array(
        'user_login' => sanitize_text_field($_POST['username']),
        'user_password' => sanitize_text_field($_POST['password']),
        'remember' => !empty($_POST['remember']) ? true : false
      );

      // Removemos todas los "hooks" pertenecientes al fallo de inicio de sesion
      remove_all_actions( 'wp_login_failed' );

      $logged_user = wp_signon( $cred, false );

      if ( is_wp_error( $logged_user ) ) {
        $result['error'] = 'Wrong username or password!';
      } else {
        $result['logged_in'] = true;
        $result['success'] = "Login successful, redirecting...";
      }
    }
  }

  header( "Content-Type: application/json" );
  echo json_encode( $result );

  exit();
}
add_action( 'wp_ajax_nopriv_login_action', 'ajax_login_action' );
add_action( 'wp_ajax_login_action', 'ajax_login_action' );

function add_register_form() {
  ?>
<div style='display:none'>
  <input type="hidden" id="fs_success_page" name="fs_success_page" value="" />
  <input type="hidden" id="fs_description" name="fs_description" value="" />
  <div id='sign_up'>
    <form id="register_form" class="fs-form">
      <h2>Become a FindSpark Member!</h2>
      <fieldset>
        <section class="form-error" style="display:none;">
            <i class="icon-remove-sign"></i>
            <label id="error_text"></label>
        </section>
        <section class="form-success" style="display:none;">
            <i class="icon-ok-sign"></i>
            <label id="success_text"></label>
        </section>
        <div class="row">
          <section class="col col-6">
            <label class="input">
              <input type="text" name="firstname" id="firstname" placeholder="First Name" autocomplete="off" />
            </label>
          </section>
          <section class="col col-6">
            <label class="input">
              <input type="text" name="lastname" id="lastname" placeholder="Last Name" autocomplete="off" />
            </label>
          </section>          
        </div>
        <section>
          <label class="input">
            <i class="icon-append icon-user"></i>
            <input type="text" name="username" id="username" placeholder="Username" autocomplete="off" />
          </label>
        </section>
        <section>
          <label class="input">
            <i class="icon-append icon-envelope-alt"></i>
            <input type="text" name="email" id="email" placeholder="E-mail" autocomplete="off" />
          </label>
        </section>
        <section>
          <label class="input">
            <i class="icon-append icon-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" autocomplete="off" />
          </label>
        </section>
      </fieldset>
      <fieldset>
        <section>
          <strong>What best describes you?</strong>
          <select id="career-situation" name="career-situation" style="font-size: 12px; width: 100%;">
            <option value="" disabled selected></option>
            <option value="1">Student, seeking internships</option>
            <option value="2">Student, seeking first full-time job</option>
            <option value="3">Unemployed, seeking full-time employment</option>
            <option value="4">Employed, seeking new full-time opportunities</option>
            <option value="5">Employed, open to new opportunities</option>
          </select>          
        </section>
        <section>
          <strong>How much career greatness would you like?</strong>          
          <div style="font-size: 11px;">
            <div>
              <input type="radio" id="career-advice-1" name="career-advice" value="1" checked="checked">
              <label for="career-advice-1" class="pointer">I heard your Weekly Opportunities Newsletter rocks, let's just do that</label>
            </div>
            <div>
              <input type="radio" id="career-advice-2" name="career-advice" value="2">
              <label for="career-advice-2" class="pointer">Gimme everything you've got, like those quick actionable tips I've heard about</label>
            </div>
          </div>
        </section>
        <section>
          <input type="checkbox" id="agree" name="agree" value="1" /> <label for="agree" class="pointer">I agree to the</label> <strong><a href="<?php echo site_url(); ?>/terms" target="_blank">FindSpark Terms &amp; Conditions</a></strong>
        </section>
      </fieldset>
      <div class="form-footer">
        <input type="button" class="button orange-button" name="register_button" id="register_button" value="Join the Community" />
      </div>
      <fieldset>
        <section>Already a member? <strong><a class="log_in" href="#log_in">Log In</a></section></strong>
      </fieldset>
    </form>
  </div>
  <div id='log_in'>
    <form id="login_form" class="fs-form">
      <h2>Log In</h2>
      <fieldset>
        <section class="form-error" style="display:none;">
            <i class="icon-remove-sign"></i>
            <label id="login_error_text"></label>
        </section>
        <section class="form-success" style="display:none;">
            <i class="icon-ok-sign"></i>
            <label id="login_success_text"></label>
        </section>
        <section>
          <label class="input">
            <i class="icon-append icon-user"></i>
            <input type="text" name="username" id="username" placeholder="Username" autocomplete="off" />
          </label>
        </section>
        <section>
          <label class="input">
            <i class="icon-append icon-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" autocomplete="off" />
          </label>
        </section>
        <section>
          <input type="checkbox" id="remember_me" name="remember_me" value="1" />
          <label class="pointer" for="remember_me">Remember Me</label>&nbsp;|&nbsp;<strong><a href="<?php echo site_url(); ?>/wp-login.php?action=lostpassword&amp;redirect_to=<?php echo urlencode(site_url()); ?>" target="_blank">Forgot password?</a></strong>
        </section>
        <section>
          <?php wp_nonce_field( 'ajax_login_nonce', 'security_code' ); ?>
        </section>
      </fieldset>      
      <div class="form-footer">
        <input type="button" class="button orange-button" name="login_button" id="login_button" value="Login" />
      </div>
      <fieldset>
        <section>
          Not a member? <strong><a class="sign_up" href="#sign_up">Become a member!</a></strong>
        </section>
      </fieldset>
    </form>
  </div>
</div>
  <?php
}
add_action( 'wp_head', 'add_register_form' );
