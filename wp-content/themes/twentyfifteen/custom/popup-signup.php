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
  	// TODO: Verificar los campos

  	$username = sanitize_text_field($_POST['username']);
  	$password = sanitize_text_field($_POST['password']);
  	$email = sanitize_text_field($_POST['email']);
  	$firstname = sanitize_text_field($_POST['firstname']);
  	$lastname = sanitize_text_field($_POST['lastname']);

  	// Custom Fields
  	$career_situation = sanitize_text_field($_POST['career_situation']);

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

  } else {
  	$result['error'] = "";
		//die ( 'Busted!');
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
	<div id='sign_up' style='padding:10px; background:#fff;'>
		<h2>Became a FindSpark Member!</h2>
		<br/>
		<form id="register_form" style="max-width: 95%;">
			<div>
				<label for="firstname">First Name</label>
				<input type="text" name="firstname" id="firstname" />
			</div>
			<div>
				<label for="lastname">Last Name</label>
				<input type="text" name="lastname" id="firstname" />
			</div>
			<div>
				<label for="username">Username</label>
				<input type="text" name="username" id="username" />
			</div>
			<div>
				<label for="email">E-mail</label>
				<input type="text" name="email" id="email" />
			</div>
			<div>
				<label for="password">Password</label>
				<input type="password" name="password" id="password" />
			</div>
			<div>
				<label for="career-situation">What best describes your current career situation?</label><br/>
				<input type="radio" id="career-situation" name="career-situation" value="1" checked="checked"/>Student, actively seeking internships<br/>
				<input type="radio" id="career-situation" name="career-situation" value="2" />Student, actively seeking first full-time job<br/>
				<input type="radio" id="career-situation" name="career-situation" value="3" />Unemployed, actively seeking full-time employment<br/>
				<input type="radio" id="career-situation" name="career-situation" value="4" />Employed, actively seeking new full-time opportunities<br/>
				<input type="radio" id="career-situation" name="career-situation" value="5" />Employed, open to new opportunities<br/>
			</div>
			<br/>
			<input type="checkbox" id="agree" name="agree" value="1" /> I agree to the <strong><a href="<?php echo site_url(); ?>/terms" target="_blank">FindSpark Terms &amp; Conditions</a></strong>
			<input type="hidden" name="sent" value="true" />
			<p><input type="button" name="register_button" id="register_button" value="Register" /></p>
		</form>
	</div>
</div>
	<?php
	}
}
add_action( 'wp_head', 'add_register_form' );
