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

// Agregamos un shortcode para crear un boton que muestre el popup
function add_signup_button($params = array()) {
	if (!is_user_logged_in()) {
		$params = shortcode_atts( array(
			"name" => "Register Now"
		), $params);

		return "<div style='text-align: center;'><a class='sign_up orange-button' href='#sign_up'>" . $params['name'] . "</a></div>";
		
	} else {

		return "";
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
		'ajax_register_nonce' => wp_create_nonce( 'ajax_register_nonce' ),
		'home' => home_url(),
		'admin_profile' => admin_url( 'profile.php' )
	));
}
add_action( 'wp_enqueue_scripts', 'add_custom_style' );

function send_welcome_email( $user_id ) {
	$user = get_userdata( $user_id );
	$admin_email = get_option('admin_email');
	//$admin_email = "hackmaster777@gmail.com";
	//$admin_email = "emily@findspark.com";

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

	/*
	$message  = __('Details:') . "\r\n\r\n";
	$message .= sprintf(__('First Name: %s'), $user->first_name) . "\r\n";
	$message .= sprintf(__('Last Name: %s'), $user->last_name) . "\r\n";
	$message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n";
	$message .= sprintf(__('E-mail: %s'), $user->user_email) . "\r\n";
	*/

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

  if ( wp_verify_nonce( $_POST['nonce'], 'ajax_register_nonce' ) ) {
  	// Verificamos si existe un usuario logueado en el sistema
  	if ( is_user_logged_in() ) {

  		$result['registered'] = false;
  		$result['logged_in'] = true;

  	} else {

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
		  		update_user_meta( $user_id, 'career_situation', $career_situation );

		  		// Notificamos por correo al usuario y al admin
		  		//wp_new_user_notification( $user_id, $password );
		  		send_welcome_email( $user_id, $password );

		  		// Logueamos al usuario en el sitio
		  		$cred = array(
						'user_login' => $username,
						'user_password' => $password,
						'remember' => false
					);

					$logged_user = wp_signon( $cred, false );

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
	// if (!is_user_logged_in()) {
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
	// }
}
add_action( 'wp_head', 'add_register_form' );
