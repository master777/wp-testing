<?php
/**
 * Twenty Fifteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

//================

// This allows a custom URL to public profiles
function custom_profile_rules() {	
	add_rewrite_tag('%user%', '([^&]+)');  
  add_rewrite_rule(
    '^profile/([^/]*)/?',
    //'index.php?page_id=18250&user=$matches[1]', // '18250' is the ID of the "My Profile" page in the wp admin
    'index.php?page_id=48&user=$matches[1]', // '18250' is the ID of the "My Profile" page in the wp admin
    'top'
	);

	//flush_rewrite_rules(); // Once you get working, please comment this next line
}
add_action('init', 'custom_profile_rules', 10, 0);

//================

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Twenty Fifteen 1.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 660;
}

/**
 * Twenty Fifteen only works in WordPress 4.1 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.1-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'twentyfifteen_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Twenty Fifteen 1.0
 */
function twentyfifteen_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on twentyfifteen, use a find and replace
	 * to change 'twentyfifteen' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'twentyfifteen', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 825, 510, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu',      'twentyfifteen' ),
		'social'  => __( 'Social Links Menu', 'twentyfifteen' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );

	$color_scheme  = twentyfifteen_get_color_scheme();
	$default_color = trim( $color_scheme[0], '#' );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'twentyfifteen_custom_background_args', array(
		'default-color'      => $default_color,
		'default-attachment' => 'fixed',
	) ) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', 'genericons/genericons.css', twentyfifteen_fonts_url() ) );
}
endif; // twentyfifteen_setup
add_action( 'after_setup_theme', 'twentyfifteen_setup' );

/**
 * Register widget area.
 *
 * @since Twenty Fifteen 1.0
 *
 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
 */
function twentyfifteen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Widget Area', 'twentyfifteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentyfifteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'twentyfifteen_widgets_init' );

if ( ! function_exists( 'twentyfifteen_fonts_url' ) ) :
/**
 * Register Google fonts for Twenty Fifteen.
 *
 * @since Twenty Fifteen 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function twentyfifteen_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Noto Sans, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Noto Sans font: on or off', 'twentyfifteen' ) ) {
		$fonts[] = 'Noto Sans:400italic,700italic,400,700';
	}

	/* translators: If there are characters in your language that are not supported by Noto Serif, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Noto Serif font: on or off', 'twentyfifteen' ) ) {
		$fonts[] = 'Noto Serif:400italic,700italic,400,700';
	}

	/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'twentyfifteen' ) ) {
		$fonts[] = 'Inconsolata:400,700';
	}

	/* translators: To add an additional character subset specific to your language, translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language. */
	$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'twentyfifteen' );

	if ( 'cyrillic' == $subset ) {
		$subsets .= ',cyrillic,cyrillic-ext';
	} elseif ( 'greek' == $subset ) {
		$subsets .= ',greek,greek-ext';
	} elseif ( 'devanagari' == $subset ) {
		$subsets .= ',devanagari';
	} elseif ( 'vietnamese' == $subset ) {
		$subsets .= ',vietnamese';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), '//fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Enqueue scripts and styles.
 *
 * @since Twenty Fifteen 1.0
 */
function twentyfifteen_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentyfifteen-fonts', twentyfifteen_fonts_url(), array(), null );

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.2' );

	// Load our main stylesheet.
	wp_enqueue_style( 'twentyfifteen-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentyfifteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentyfifteen-style' ), '20141010' );
	wp_style_add_data( 'twentyfifteen-ie', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'twentyfifteen-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'twentyfifteen-style' ), '20141010' );
	wp_style_add_data( 'twentyfifteen-ie7', 'conditional', 'lt IE 8' );

	wp_enqueue_script( 'twentyfifteen-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20141010', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'twentyfifteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20141010' );
	}

	wp_enqueue_script( 'twentyfifteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20141212', true );
	wp_localize_script( 'twentyfifteen-script', 'screenReaderText', array(
		'expand'   => '<span class="screen-reader-text">' . __( 'expand child menu', 'twentyfifteen' ) . '</span>',
		'collapse' => '<span class="screen-reader-text">' . __( 'collapse child menu', 'twentyfifteen' ) . '</span>',
	) );
}
add_action( 'wp_enqueue_scripts', 'twentyfifteen_scripts' );

/**
 * Add featured image as background image to post navigation elements.
 *
 * @since Twenty Fifteen 1.0
 *
 * @see wp_add_inline_style()
 */
function twentyfifteen_post_nav_background() {
	if ( ! is_single() ) {
		return;
	}

	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );
	$css      = '';

	if ( is_attachment() && 'attachment' == $previous->post_type ) {
		return;
	}

	if ( $previous &&  has_post_thumbnail( $previous->ID ) ) {
		$prevthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $previous->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-previous { background-image: url(' . esc_url( $prevthumb[0] ) . '); }
			.post-navigation .nav-previous .post-title, .post-navigation .nav-previous a:hover .post-title, .post-navigation .nav-previous .meta-nav { color: #fff; }
			.post-navigation .nav-previous a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	if ( $next && has_post_thumbnail( $next->ID ) ) {
		$nextthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $next->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-next { background-image: url(' . esc_url( $nextthumb[0] ) . '); }
			.post-navigation .nav-next .post-title, .post-navigation .nav-next a:hover .post-title, .post-navigation .nav-next .meta-nav { color: #fff; }
			.post-navigation .nav-next a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	wp_add_inline_style( 'twentyfifteen-style', $css );
}
add_action( 'wp_enqueue_scripts', 'twentyfifteen_post_nav_background' );

/**
 * Display descriptions in main navigation.
 *
 * @since Twenty Fifteen 1.0
 *
 * @param string  $item_output The menu item output.
 * @param WP_Post $item        Menu item object.
 * @param int     $depth       Depth of the menu.
 * @param array   $args        wp_nav_menu() arguments.
 * @return string Menu item with possible description.
 */
function twentyfifteen_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'primary' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'twentyfifteen_nav_description', 10, 4 );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Twenty Fifteen 1.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function twentyfifteen_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'twentyfifteen_search_form_modify' );

/**
 * Implement the Custom Header feature.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/customizer.php';

//========================

function add_custom_scripts( $hook ) {
	if ( in_array( $hook, array( 'profile.php', 'user-edit.php' ) ) ) {
		// Para la funcion de autocompletar
		wp_enqueue_style( 'jquery.tokenize.css', get_template_directory_uri() . '/css/jquery.tokenize.css' );
		wp_enqueue_script( 'jquery.tokenize.js', get_template_directory_uri() . '/js/jquery.tokenize.js' );
		wp_enqueue_script( 'custom_script.js', get_template_directory_uri() . '/js/custom_script.js' );
	}
}

add_action( 'admin_enqueue_scripts', 'add_custom_scripts' );

function add_custom_fields( $user ) {
	$is_job_seeker = false;
	foreach ($user->roles as $role) {
		if ( in_array( $role, array( 'subscriber', 'premiummember' ) ) ) {
			$is_job_seeker = true;
		}
	}

	if ($is_job_seeker) {
		$delimiter_str = ",";

		// Lista de elementos por defecto para el autocompletado

		$default_skills_tags = array(
			"Advertising", "Animation", "Architecture", "Art History", "Business", "Communications", "Computer Science/Engineering", "Creative Writing", "Entrepreneurship",
			"Event Planning", "Fashion", "Fine Art", "Graphic Design", "Journalism", "Marketing", "Music", "Photography", "Production", "Product Design", "Public Relations",
			"Publishing", "Social Media", "Theater", "TV / Film", "Undecided"
		);

		// Inicializamos los valores de cada campo con autocompletado

		$main_skills = esc_attr( get_the_author_meta( 'main_skills', $user->ID ) );
		if (!empty($main_skills)) {
			$main_skills = explode($delimiter_str, $main_skills);
		} else {
			$main_skills = array();
		}

		$target_industries = esc_attr( get_the_author_meta( 'target_industries', $user->ID ) );
		if (!empty($target_industries)) {
			$target_industries = explode($delimiter_str, $target_industries);
		} else {
			$target_industries = array();
		}

		$pic_data = get_user_meta( $user->ID, 'profile_pic', true );

		/*
		echo "pic_data:";
		echo "<pre>";
		print_r($pic_data);
		echo "</pre>";
		*/		

		if (!empty($pic_data) && !isset($pic_data['error']) /*&& file_exists($pic_data['file'])*/) {
			$profile_pic_url = $pic_data['url'];
		} else {
			$profile_pic_url = get_template_directory_uri() . "/images/profile-default.png";
		}

		// Formamos la URL del perfil publico del usuario actual (formato: home/profile/username)
		$public_profile_url = site_url() . "/profile/" . $user->user_nicename;
?>

	<h3>Basic Info</h3>
	<table class="form-table">
		<tbody>
			<tr>
      	<th>
      		<label for="profile-pic"><?php _e('Profile Image', 'shr') ?><p class="description">(it will only be shown in your public profile)</p></label>
      	</th>
      	<td>      		
          <img width="125px" height="125px" id="profile-pic-preview" src="<?php echo $profile_pic_url; ?>" alt="Your profile image" />
        	<p>
    				<input type="file" id="profile-pic" name="profile-pic" onchange="readURL(this);" />
    			</p>
          <p class="description">(Recommended 250x250 pixels)</p>
      	</td>
      </tr>
      <tr>
				<th><label for="college-attended">College Attended</label></th>
				<td><input type="text" id="college-attended" name="college-attended" value="<?php echo esc_attr( get_the_author_meta( 'college_attended', $user->ID ) ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="year-graduation">Year of Graduation</label></th>
				<td><input type="text" id="year-graduation" name="year-graduation" value="<?php echo esc_attr( get_the_author_meta( 'year_graduation', $user->ID ) ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th>Public Profile URL<p class="description">(Not editable)</p></th>
				<td>
					<a href="<?php echo $public_profile_url; ?>" target="_blank"><?php echo $public_profile_url; ?></a>
				</td>
			</tr>
		</tbody>
	</table>
	<h3>Career Background</h3>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="job-title">Current Job</label></th>
				<td><input type="text" id="job-title" name="job-title" value="<?php echo esc_attr( get_the_author_meta( 'job_title', $user->ID ) ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="job-company">Current Company</label></th>
				<td><input type="text" id="job-company" name="job-company" value="<?php echo esc_attr( get_the_author_meta( 'job_company', $user->ID ) ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="main-skills">What are your main skills?</label><p class="description">(Up to 5)</p></th>
				<td>
					<select id="main-skills" name="main-skills[]" multiple="multiple" class="profile-field">
						<?php foreach($default_skills_tags as $tag) { ?>
							<option value="<?php echo $tag; ?>" <?php echo in_array($tag, $main_skills) ? 'selected="selected"' : ''; ?>><?php echo $tag; ?></option>
						<?php } ?>
					</select>
				</td>				
			</tr>
			<tr>
				<th><label for="target-industries">What are your target industries?</label><p class="description">(Up to 5)</p></th>
				<td>
					<select id="target-industries" name="target-industries[]" multiple="multiple" class="profile-field">
						<?php foreach($default_skills_tags as $tag) { ?>
							<option value="<?php echo $tag; ?>" <?php echo in_array($tag, $target_industries) ? 'selected="selected"' : ''; ?>><?php echo $tag; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	<h3>Demographic Info</h3>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="age">Age</label></th>
				<td><input type="text" id="age" name="age" value="<?php echo esc_attr( get_the_author_meta( 'age', $user->ID ) ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="ethnicity">Ethnicity</label></th>
				<td><input type="text" id="ethnicity" name="ethnicity" value="<?php echo esc_attr( get_the_author_meta( 'ethnicity', $user->ID ) ); ?>" class="regular-text" /></td>
			</tr>
			<!--tr>
				<th><label for="social-media-platform">Which social media platform do you use on a daily basis?</label></th>
				<td><input type="text" id="social-media-platform" name="social-media-platform" value="<?php echo esc_attr( get_the_author_meta( 'social-media-platform', $user->ID ) ); ?>" class="regular-text" /></td>
			</tr-->
		</tbody>
	</table>
	<!--h3>(Only Premium Members)</h3-->
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="resume">Upload Resume</label></th>
				<td><input type="file" id="resume" name="resume" value="<?php echo esc_attr( get_the_author_meta( 'resume', $user->ID ) ); ?>" class="regular-text" /></td>
			</tr>
		</tbody>
	</table>
	<h3>Privacity Settings</h3>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="public-profile-status">Public Profile</label></th>
				<td>
					<input type="radio" id="public-profile-status" name="public-profile-status" value="0" <?php echo esc_attr( get_the_author_meta( 'public-profile-status', $user->ID ) ) == 0 ? 'checked="checked"' : '' ; ?> />Everyone can view my profile<br/>
					<input type="radio" id="public-profile-status" name="public-profile-status" value="1" <?php echo esc_attr( get_the_author_meta( 'public-profile-status', $user->ID ) ) == 1 ? 'checked="checked"' : '' ; ?> />Only FindSpark members can view my profile<br/>
					<?php if (true) { // TODO: Restringir las ultimas opciones para que esten disponibles SOLO para miembros premium ?>
					<input type="radio" id="public-profile-status" name="public-profile-status" value="2" <?php echo esc_attr( get_the_author_meta( 'public-profile-status', $user->ID ) ) == 2 ? 'checked="checked"' : '' ; ?> />Only FindSpark employers can view my profile<br/>
					<input type="radio" id="public-profile-status" name="public-profile-status" value="3" <?php echo esc_attr( get_the_author_meta( 'public-profile-status', $user->ID ) ) == 3 ? 'checked="checked"' : '' ; ?> />FindSpark members &amp; employers can view my profile<br/>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<th><label for="contact-me-status">Contact me</label></th>
				<td>
					<input type="radio" id="contact-me-status" name="contact-me-status" value="0" <?php echo esc_attr( get_the_author_meta( 'contact-me-status', $user->ID ) ) == 0 ? 'checked="checked"' : '' ; ?> />Everyone can contact me<br/>
					<input type="radio" id="contact-me-status" name="contact-me-status" value="1" <?php echo esc_attr( get_the_author_meta( 'contact-me-status', $user->ID ) ) == 1 ? 'checked="checked"' : '' ; ?> />Only FindSpark members can contact me<br/>
					<?php if (true) { // TODO: Restringir las ultimas opciones para que esten disponibles SOLO para miembros premium ?>
					<input type="radio" id="contact-me-status" name="contact-me-statuss" value="2" <?php echo esc_attr( get_the_author_meta( 'contact-me-status', $user->ID ) ) == 2 ? 'checked="checked"' : '' ; ?> />Only FindSpark employers can contact me<br/>
					<input type="radio" id="contact-me-status" name="contact-me-status" value="3" <?php echo esc_attr( get_the_author_meta( 'contact-me-status', $user->ID ) ) == 3 ? 'checked="checked"' : '' ; ?> />FindSpark members &amp; employers can contact me<br/>
					<?php } ?>
				</td>
			</tr>
		</tbody>
	</table>
<?php
	}
}

add_action( 'show_user_profile', 'add_custom_fields' );
add_action( 'edit_user_profile', 'add_custom_fields' );

function save_custom_fields( $user_id ) {
	$is_job_seeker = false;
	$user = get_userdata( $user_id );
	foreach ($user->roles as $role) {
		if (in_array($role, array('subscriber', 'premiummember'))) {
			$is_job_seeker = true;
		}
	}

	if ($is_job_seeker) {
		$delimiter_str = ",";

		update_user_meta( $user_id, 'college_attended', sanitize_text_field( $_POST['college-attended'] ) );
		update_user_meta( $user_id, 'year_graduation', sanitize_text_field( $_POST['year-graduation'] ) );
		update_user_meta( $user_id, 'job_title', sanitize_text_field( $_POST['job-title'] ) );
		update_user_meta( $user_id, 'job_company', sanitize_text_field( $_POST['job-company'] ) );
		update_user_meta( $user_id, 'main_skills', sanitize_text_field( implode( $delimiter_str, $_POST['main-skills'] ) ) );
		update_user_meta( $user_id, 'target_industries', sanitize_text_field( implode( $delimiter_str, $_POST['target-industries'] ) ) );

		if( $_FILES['profile-pic']['error'] === UPLOAD_ERR_OK ) {
			/*
			// Obtenemos el tipo del archivo subido. Esto es retornado como "type/extension"
			$arr_file_type = wp_check_filetype(basename($_FILES['profile-pic']['name']));
			$uploaded_file_type = $arr_file_type['type'];

			// Lista de formatos aceptados
			$allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
			if(in_array($uploaded_file_type, $allowed_file_types)) {

			}
			*/

			// La subida siempre fallara si no se pone esto: 'test_form' => false
			$upload_overrides = array( 'test_form' => false );
			// Subimos la imagen mediante wordpress (la guarda en wp-contents/uploads)
		 	$img = wp_handle_upload( $_FILES['profile-pic'], $upload_overrides );
		 	// Actualizamos la informacion del campo respectivo
			update_user_meta( $user_id, 'profile_pic', $img );
		}
	}
}

add_action( 'personal_options_update', 'save_custom_fields' );
add_action( 'edit_user_profile_update', 'save_custom_fields' );

function make_form_accept_uploads() {
	// Para poder subir archivos en el formulario del profile, se debe agregar la siguiente propiedad:
	echo ' enctype="multipart/form-data"';
}

add_action('user_edit_form_tag', 'make_form_accept_uploads');

//========================