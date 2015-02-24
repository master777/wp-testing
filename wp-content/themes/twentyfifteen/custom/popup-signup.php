<?php

function add_custom_menu( $nav, $args ) {
	
	if( $args->theme_location == 'primary' && !is_user_logged_in() ) {
		return $nav . "<li class='menu-item'><a class='sign_up' href='#sign_up'>Become a member!</a></li>";		
	}

	/*
	if( $args->theme_location == 'login' && !is_user_logged_in() ) {
		return $nav . "<li class='orange-menu menu-item'><a class='sign_up' href='#sign_up'>Sign Up!</a></li>";
	}
	*/
	return $nav;
}
add_filter('wp_nav_menu_items','add_custom_menu', 10, 2);

function add_custom_style($hook) {
	//wp_enqueue_style( 'font-awesome.min.css', get_template_directory_uri() . '/css/font-awesome.min.css' );
	wp_enqueue_style( 'fs-forms.css', get_template_directory_uri() . '/css/fs-forms.css' );

	wp_enqueue_style( 'colorbox.css', get_template_directory_uri() . '/css/colorbox.css' );
	wp_enqueue_script( 'jquery.colorbox-min.js', get_template_directory_uri() . '/js/jquery.colorbox-min.js' );
	wp_enqueue_script( 'popup-init.js', get_template_directory_uri() . '/js/popup-init.js' );	

	wp_localize_script( 'popup-init.js', 'WPURLS', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'ajax_register_nonce' => wp_create_nonce( 'ajax_register_nonce' ),
		'home' => home_url(),
		'admin_profile' => admin_url( 'profile.php' )
	));
}
add_action( 'wp_enqueue_scripts', 'add_custom_style' );

function ajax_save_data() {
  $result = array();

  if ( wp_verify_nonce( $_POST['nonce'], 'ajax_register_nonce' ) ) {
  	$username = sanitize_text_field($_POST['username']);
  	$password = sanitize_text_field($_POST['password']);
  	$email = sanitize_text_field($_POST['email']);
  	$firstname = sanitize_text_field($_POST['firstname']);
  	$lastname = sanitize_text_field($_POST['lastname']);

  	// Custom Fields
  	$career_situation = sanitize_text_field($_POST['career_situation']);

  	// Validamos los campos
  	if (empty($firstname)) {
  		$result['error'] = "The first name is required!";
  	} else if (!preg_match("/^[a-zA-Z ]*$/", $firstname)) {
  		$result['error'] = "Please only letters to first name!";
  	} else if (empty($lastname)) {
  		$result['error'] = "The last name is required!";
  	} else if (!preg_match("/^[a-zA-Z ]*$/", $lastname)) {
  		$result['error'] = "Please only letters to last name!";  	
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

	  		// Registramos los campos faltantes
	  		update_user_meta($user_id, 'career_situation', $career_situation);

	  		// Logueamos al usuario en el sitio
	  		$cred = array(
					'user_login' => $username,
					'user_password' => $password,
					'remember' => false
				);

				$logged_user = wp_signon($cred, false);

				if ( is_wp_error( $logged_user ) ) {
					$result['error'] = $logged_user->get_error_message();
				} else {
					$result['logged_in'] = true;
				}		

	  	} else { // Ocurrio un error
	  		/*
	  		$error = $user_id->get_error_codes();

	  		if (in_array('empty_user_login', $error)) {
	  			$result['error'] = __('The username can not be empty');
	  		} else if (in_array('existing_user_login', $error)) {
	  			$result['error'] = __('This username is already registered');
	  		} else if (in_array('existing_user_email', $error)) {
	  			$result['error'] = __("This email address is already registered");
	  		} else {
	  			$result['error'] = $user_id->get_error_message();  			
	  		} */

	  		$result['error'] = $user_id->get_error_message();
	  		$result['error_code'] = $user_id->get_error_codes();
	  	}
  	}
  } else {
  	$result['error'] = "Invalid security code!";
  	$result['error_code'] = $user_id->get_error_codes();
  }

  header( "Content-Type: application/json" );
  echo json_encode( $result );

  exit();
}
add_action( 'wp_ajax_nopriv_save_data_action', 'ajax_save_data' );
add_action( 'wp_ajax_save_data_action', 'ajax_save_data' );

function add_register_form() {
	if (!is_user_logged_in()) {
	?>
<div style='display:none'>
	<style type="text/css">
	/* Pruebas de estilo */	
	</style>
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
				<section>
					<label class="input">
						<i class="icon-append icon-user"></i>
						<input type="text" name="firstname" id="firstname" placeholder="First Name" autocomplete="off" />
						<!--b class="tooltip tooltip-bottom-right">Only characters and numbers</b-->
					</label>
				</section>
				<section>
					<label class="input">
						<i class="icon-append icon-user"></i>
						<input type="text" name="lastname" id="lastname" placeholder="Last Name" autocomplete="off" />
					</label>
				</section>
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
					<strong>What best describes your current career situation?</strong>
					<div>
						<input type="radio" id="career-situation-1" name="career-situation" value="1" checked="checked"/>
						<label for="career-situation-1" class="pointer">Student, actively seeking internships</label>
					</div>
					<div>
						<input type="radio" id="career-situation-2" name="career-situation" value="2" />
						<label for="career-situation-2" class="pointer">Student, actively seeking first full-time job</label>
					</div>
					<div>
						<input type="radio" id="career-situation-3" name="career-situation" value="3" />
						<label for="career-situation-3" class="pointer">Unemployed, actively seeking full-time employment</label>
					</div>
					<div>
						<input type="radio" id="career-situation-4" name="career-situation" value="4" />
						<label for="career-situation-4" class="pointer">Employed, actively seeking new full-time opportunities</label>
					</div>
					<div>
						<input type="radio" id="career-situation-5" name="career-situation" value="5" />
						<label for="career-situation-5" class="pointer">Employed, open to new opportunities</label>
					</div>
				</section>
				<section>
					<input type="checkbox" id="agree" name="agree" value="1" /> I agree to the <strong><a href="<?php echo site_url(); ?>/terms" target="_blank">FindSpark Terms &amp; Conditions</a></strong>
				</section>
			</fieldset>
			<div class="form-footer">
				<input type="button" class="button" name="register_button" id="register_button" value="Register" />
			</div>
		</form>
	</div>
</div>
	<?php
	}
}
add_action( 'wp_head', 'add_register_form' );
