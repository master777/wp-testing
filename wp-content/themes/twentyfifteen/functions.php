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
		// Para crear variables javascript equivalentes a las obtenidas mediante PHP:
		wp_register_script( 'custom_script.js', get_template_directory_uri() . '/js/custom_script.js');
		$var_list = array( 
			'template_url' => get_template_directory_uri()
		);
		wp_localize_script( 'custom_script.js', 'WPURLS', $var_list );

		// Para la funcion de autocompletar:
		wp_enqueue_style( 'jquery.tokenize.css', get_template_directory_uri() . '/css/jquery.tokenize.css' );
		wp_enqueue_script( 'jquery.tokenize.js', get_template_directory_uri() . '/js/jquery.tokenize.js' );
		wp_enqueue_script( 'custom_script.js' ); // No ingresamos el segundo paramentro porque ya se lo especifico en wp_register_script
	}
}

add_action( 'admin_enqueue_scripts', 'add_custom_scripts' );

function add_custom_fields( $user ) {
	$is_job_seeker = false;
	$is_premium_member = false;
	foreach ($user->roles as $role) {
		if ( in_array( $role, array( 'subscriber', 'premiummember' ) ) ) {
			$is_job_seeker = true;
			$is_premium_member = $role == 'premiummember';
		}
	}

	if ($is_job_seeker) {
		// Lista de elementos por defecto para el autocompletado

		$industries_tags = array(
			"Advertising", "Animation", "Architecture", "Art History", "Business", "Communications", "Computer Science/Engineering", "Creative Writing", "Entertainment", "Entrepreneurship",
			"Event Planning", "Fashion", "Fine Art", "Graphic Design", "Journalism", "Marketing", "Music", "Photography", "Production", "Product Design", "Public Relations",
			"Publishing", "Social Media", "Theater", "TV / Film", "Undecided", "Other"
		);

		$school_tags = array(
			"Academy of Art University",
			"Adelphi University",
			"Allegheny College",
			"American University in Dubai",
			"Amherst College",
			"Aoyama Gakuin University",
			"Arizona State University",
			"Art institute of New York",
			"Ateneo de Manila University",
			"Athens University of Economics and Business",
			"Attended College of Staten Island",
			"Auburn University",
			"Audencia Nantes School of Managementàç",
			"Audencia School of Management",
			"Azusa Pacific University",
			"Babson College",
			"Bahria University",
			"Ball State University",
			"Bangalore University, New York University",
			"Barnard College, Columbia University",
			"Barry University",
			"Baruch College",
			"Bates College",
			"Bay State College",
			"Baylor University",
			"Bell Language School",
			"Belmont University",
			"Bennington College",
			"Berkeley College",
			"Bernard M. Baruch City University of New York",
			"Bethune-Cookman University",
			"Billy Blue (Melbourne, Australia)",
			"Binghamton University",
			"BMCC",
			"Borough of manhattan community college",
			"Boston College",
			"Boston University",
			"Bowling Green State University",
			"Brandeis University",
			"Briarcliffe Coolege",
			"Bronx Community College",
			"Bronx Compass High School",
			"Brookdale Community College",
			"Brooklyn College, CUNY",
			"Brown University",
			"Brunel University",
			"Bryant University",
			"Bucknell University",
			"Budapest University of Technology and Economics",
			"Buffalo State",
			"Business Academy Smilevski, Skopje, R.Macedonia",
			"California College of the Arts",
			"California Polytechnic University",
			"California State University",
			"Carnegie Mellon University",
			"Católica University",
			"Cedarville University",
			"Centenary College",
			"Central Connecticut State University",
			"Central Michigan University",
			"Champlain College",
			"Chapman University",
			"Charles Péguy College Paris",
			"Chemeketa Community College",
			"City College of New York",
			"Claremont McKenna College",
			"Clark Atlanta University",
			"Clark University",
			"Cleveland State University",
			"Colby College",
			"Colegiatura Colombiana de Diseño",
			"Colegio Americano de Fotografia Ansel Adams",
			"Colgate University",
			"College of Charleston",
			"College of Mount Saint Vincent",
			"College of Saint Elizabeth",
			"College of Saint Rose",
			"College of Staten Island",
			"College of the Holy Cross",
			"College of William & Mary",
			"Colorado College",
			"Colorado State University",
			"Columbia Journalism School",
			"Columbia University",
			"Columbus College of Art and Design",
			"Concord University",
			"Concordia University",
			"Cornell University",
			"Creative Circus",
			"CUNY",
			"Curtin University",
			"Darmstadt University",
			"Dartmouth College",
			"Davidson College",
			"Deakin University",
			"Delhi University",
			"DePaul University",
			"DeVry University",
			"Dickinson College",
			"Dominican University",
			"Dowling College",
			"Drake University",
			"Drexel University",
			"DSK International school of Design",
			"Duke University",
			"Dundalk Institute of Technology",
			"East Carolina University",
			"East Stroudsburg University",
			"East Tennessee State University",
			"Eckerd College",
			"Ecole de commerce européenne INSEEC,",
			"École supérieur de gestion Paris",
			"Edinburgh Napier University",
			"El Camino College",
			"Elegance School of Professional Make-up",
			"ELISAVA, Barcelona",
			"Elon University",
			"EM LYON Business School",
			"Emerson College",
			"Emory University",
			"Empire State College",
			"ENS Cachan, University of Paris-Sud",
			"ESC DIJON",
			"ESGCI - Ecole supérieure de gestion et commerce international",
			"ESSCA Paris Business School",
			"ESSEC BUSINESS SCHOOL PARIS",
			"Essex County College",
			"Eureka College",
			"European School of Economics",
			"Everest University Online",
			"Fairfield University",
			"Fairleigh Dickinson University",
			"Farmingdale State College",
			"FIT",
			"Fashion Institute of Design and Merchandising Los Angeles",
			"Fitchburg State University",
			"Florida Agricultural & Mechanical University",
			"Florida Atlantic University",
			"Florida International University",
			"Florida State University",
			"Fordham University",
			"Franklin & Marshall College",
			"Franklin and Marshall College",
			"Free University of Brussels",
			"Fresno City College",
			"Full Sail University",
			"General Assembly, NYC Tech-Design-Business School",
			"George Mason University",
			"George Washington University",
			"Georgetown University",
			"Georgia College & State University",
			"Georgia Institute of Technology",
			"Georgia State University",
			"Goucher College",
			"Grand Valley State University",
			"Grenoble Business School",
			"Grove City College",
			"Guttman Community College",
			"Hamilton College",
			"Hampshire College",
			"Hampton University",
			"Harvard University",
			"Hebrew University of Jerudalem",
			"HEC Paris",
			"Herbert H. Lehman University",
			"High Point University",
			"High School",
			"High School Paul Langevin",
			"Higher School of Economics",
			"Hobart and William Smith Colleges",
			"Hofstra University",
			"Hogeschool Rotterdam",
			"Houdegbe North American University, Benin Republic",
			"Howard University",
			"Hudson County Community College",
			"Hult International Business School, Master's of Social Entrepreneurship",
			"Hunter College",
			"IAE Aix-Marseille Graduate School of Management",
			"IDC Herzliya",
			"Illinois Wesleyan University",
			"Indiana University",
			"INSEEC Business School Paris",
			"INSEEC Business School, Bordeaux, France",
			"INSEEC BUSINESS SCHOOL, FRANCE",
			"Institucion Universitaria Colegiatura Colombiana de Diseno",
			"Institute of Business Administration of Aix en Provence",
			"Instituto superior de Economia e Gestão",
			"Iowa State University",
			"ISC PARIS, Business school",
			"ISCOM Paris",
			"ISG Business School Paris",
			"Ithaca College",
			"Jackson State University",
			"John Jay College of Criminal Justice",
			"Johns Hopkins University",
			"Johnson & Wales University",
			"Kansas State University",
			"Kansas State University, Wichita State University",
			"Kansas University",
			"Kean University",
			"KEDGE Business School",
			"Kedge Business School (Marseilles, France)",
			"Keene State College",
			"Keller School of Management, MS in Information Technology",
			"Kennesaw State University",
			"Kent State University",
			"Kenyon College",
			"Keystone College",
			"Kingsborough Community College",
			"Kobe University",
			"Kutztown University",
			"Kyiv Slavonic University",
			"La Guardia Community College",
			"Laboratory Institute of Merchandising College",
			"Laboratory Institute of Technology",
			"Lafayette College",
			"LaGuardia Community College",
			"Lake Superior College",
			"Lang",
			"Laval University",
			"Lehigh University",
			"Lehman College (M.S.Ed. Anticipated)), Case Western University (B.A.)",
			"Lincoln University",
			"London college of fashion",
			"Long Island University - CW Post Campus",
			"Long Island University Post Campus",
			"Long Island University- brooklyn",
			"Louisiana State University",
			"Loyola Marymount Universitiy",
			"Loyola University",
			"Macalester College",
			"Macaulay Honors College at John Jay College of Criminal Justice",
			"Manhattan College",
			"Manhattanville College",
			"Marist College",
			"Marquette University",
			"Maryland Institute Collage of Art",
			"Marymount Manhattan College",
			"Massachusetts College of Art and Design",
			"McGill University",
			"Mercy College",
			"Mercyhurst University",
			"Merrimack College",
			"Metropolitan College of New York",
			"Miami University",
			"Michigan State University",
			"Middlebury College",
			"Middlesex University, London",
			"Millennium High School",
			"Molloy College",
			"Monroe College",
			"Montana State University",
			"Montclair State University",
			"Montclair University",
			"Monterrey Institute of Technology",
			"Moore College of Art & Design",
			"Morgan State University",
			"Mount Holyoke College",
			"Mount Saint Mary College",
			"Muhelnberg College (B.A)  Rutgers University (M.A.)",
			"Muhlenberg College",
			"Mumbai  University",
			"Other",
			"Nanyang Academy of Fine Arts, Singapore",
			"Nassau Community College",
			"National Insititute Of Fashion Technology, Hyderabad",
			"National University of Ireland Galway",
			"National University of Singapore",
			"NDDU",
			"Neoma Business school",
			"The New School",
			"New York Conservatory for the Dramatic Arts",
			"New York Film Academy",
			"New York Institute of technology",
			"New York University Stern School of Business",
			"New York University, Stony Brook University",
			"New York University, The New School",
			"New York University, Tisch School of the Arts",
			"Norfolk State University",
			"North Carolina A&T State University",
			"North Carolina State University",
			"Northampton Community College",
			"Northeastern State University",
			"Northeastern University",
			"Northern Illinois University",
			"Northumbria University, Newcastle, UK",
			"Northwestern University",
			"Norwich University of the Arts",
			"NYIT",
			"NYU Polytechnic School of engineering",
			"Oakwood University",
			"Ocean County College",
			"Ohio University",
			"Old Dominion University",
			"Old Dominion University, New York University",
			"Otis College of Art and Design",
			"Oxford Brookes",
			"Pace University",
			"Parsons The Newschool for Design",
			"Pennsylvania State University",
			"Pepperdine University",
			"Philadelphia University",
			"Point Park University",
			"Pontificia Universidad Javeriana (Bogotá)",
			"Pratt Institute, New York, Ny",
			"premiere career college",
			"Princeton University",
			"Principia College",
			"Purchase College",
			"Purdue University",
			"Queen's University",
			"Queens College",
			"Quinnipiac University",
			"Raffles Lasalle International Design College",
			"Ramapo College of New Jersey",
			"Randolph College",
			"Regents Business School of London",
			"Rennes 2 University",
			"Rhode Island School of Design",
			"Rice University",
			"Richmond, the American International University in London",
			"Rider University",
			"Rietveld International School of Art & Design",
			"Ringling College of Art & Design",
			"Roberts Wesleyan College",
			"Rochester Institute of Technology",
			"Roger Williams University",
			"Rowan University",
			"Ruprecht-Karls-Universität Heidelberg",
			"Rutgers University",
			"Ryerson University",
			"S.I. Newhouse School of Public Communications",
			"Sacred Heart University",
			"SAE Intitute of NY",
			"Sage College of Albany",
			"Saint Francis College",
			"Saint Joseph's University",
			"Saint Peter's University",
			"San Diego State University",
			"San Francisco State University",
			"Sarah Lawrence College",
			"Savannah College of Art and Design",
			"School of Arts, University of Nova Gorica",
			"SVA",
			"Scripps College, Claremont Colleges",
			"SCU",
			"Seattle University",
			"Seton Hall University",
			"Sheridan College / University of Toronto",
			"Shillington Schol",
			"Shillington School, New York State College of Ceramics at Alfred University",
			"Shippensburg University",
			"Siena College",
			"Simmons College",
			"Skidmore College",
			"Smith College",
			"Southeastern University",
			"Spelman College",
			"St .Francis College",
			"St Johns University",
			"St. Lawrence University",
			"St. Mary's College of CA, New York University",
			"Stanford University",
			"State Univeristy of New York the College at Old Westbury",
			"State University at Albany",
			"State University at Buffalo State College",
			"State University of Londrina",
			"Stella and Charles Guttman Community College",
			"Stonestreet studios",
			"Stony Brook University",
			"Suffolk University",
			"Sungshin woman's university",
			"SUNY",
			"Susquehanna University",
			"Symbiosis International University",
			"Syracuse University",
			"Syracuse University S.I. Newhouse School of Public Communications",
			"Syracuse University, London College of Fashion",
			"Teachers College, Columbia University",
			"Tecnologico de Monterrey",
			"Telecom ParisTech",
			"Temple University",
			"Texas A&M University",
			"The Art Institute of Philadelphia",
			"The Art Institute of Pittsburgh",
			"The Catholic University of America",
			"The City College of New York",
			"The College at Old Westbury",
			"The College of New Jersey",
			"The College of Saint Rose",
			"The Cooper Union",
			"The Fashion Institute of Technology",
			"The George Washington University",
			"The Illinois Institute of Art - Chicago",
			"The Juilliard School",
			"The King's College",
			"The New School",
			"The Ohio State University",
			"The Pennsylvania state university",
			"The Richard Stockton College of New Jersey",
			"The Savannah College of Art and Design",
			"The University of Arizona",
			"The University of Georgia",
			"The University of Melbourne",
			"The University of North Carolina Greensboro",
			"The University of Scranton",
			"The University of Southern Mississippi",
			"The University of Texas at Austin",
			"The University of Virginia",
			"Toulouse Business School (France)",
			"Toulouse university",
			"Touro College",
			"Tribeca Flashpoint Media Arts Academy",
			"Trinity College",
			"Tufts University",
			"Tulane University",
			"Tunisia Engineering University",
			"UC Davis",
			"UCLA",
			"UFRGS",
			"UNIACC",
			"Union County College",
			"Union University",
			"Univer",
			"Universidad Argentina de la Empresa, Universidat Autónoma de Barcelona, Wimbledon School of English",
			"Universidad Autonoma de Madrid",
			"Universidad de Palermo",
			"Universidad Iberoamericana",
			"Universidad Jorge Tadeo Lozano de Bogotá",
			"Universidad Nacional de Educacion a Distancia (Spain)",
			"Universidad Santa Maria, Venezuela",
			"Università Statale di Milano",
			"Université De Montréal",
			"Université de Savoie",
			"Université de Strasbourg",
			"University At Albany",
			"University at Buffalo",
			"University at Buffalo, State University of New York",
			"University Carlos III of Madrid",
			"University College Cork, Cork Ireland",
			"University for the Creative Arts, Farnham, UK",
			"University Inholland in the Netherlands",
			"University of Antwerp",
			"University of Bridgeport",
			"University of British Columbia",
			"University of California at Riverside",
			"University of Central Arkansas",
			"University of Central Florida",
			"University of Central Oklahoma",
			"University of Cincinnati",
			"University of Colorado",
			"University of Connecticut",
			"University of Delaware",
			"University of Denver",
			"University of Edinburgh",
			"University of Florida",
			"University of Georgia",
			"University of Huddersfield",
			"University of Illinois at Chicago",
			"University of Iowa",
			"University of Kentucky",
			"University of Law",
			"University of Leeds",
			"University of Lees",
			"University of Louisville",
			"University of Manchester",
			"University of Maryland",
			"University of Massachusetts Amherst",
			"University of Miami",
			"University of Michigan",
			"University of Minnesota - Twin Cities",
			"University of Mississippi",
			"University of Missouri - Columbia",
			"University of Montana",
			"University of Navarre",
			"University of Nebraska",
			"University of New Hampshire",
			"University of New Haven",
			"University Of North Carolina at Asheville",
			"University of North Carolina at Chapel Hill",
			"University of North Carolina Wilmington",
			"University of North Texas",
			"University of Northwestern",
			"University of Nottingham",
			"University of Pennsylvania",
			"University of Pittsburgh",
			"University of Rhode Island",
			"University of Richmond",
			"University of Rochester",
			"University of Salford",
			"University of San Diego",
			"University of San Francisco",
			"University of Science and Arts Oklahoma",
			"University of Scranton",
			"University of South Carolina",
			"University of South Florida",
			"University of Southern California",
			"University of Tampa",
			"University of Tennessee",
			"University of Texas at Austin",
			"University of the Arts",
			"University of the Pacific",
			"University of the Philippines",
			"University of Utah",
			"University of Vermont",
			"University of Victoria",
			"University of Virginia",
			"University of Virginia, Rollins College",
			"University of Washington",
			"University Of Western Sydney",
			"University of Westminster",
			"University of Wisconsin - Madison",
			"Universtiy of Iowa",
			"UPenn",
			"Ursinus College",
			"Uuniversity of California at Berkeley",
			"Valencia University",
			"Vanderbilt University",
			"Vassar College",
			"Victoria University Wellington",
			"Villanova University",
			"Virginia Commonwealth University",
			"Virginia State University",
			"Virginia Tech",
			"Wagner College",
			"Wake Forest University",
			"Washington and Lee University",
			"Washington State University",
			"Washington University in Saint Louis",
			"Webster University",
			"Wellesley College",
			"Wesleyan University",
			"West Virginia University",
			"Westchester Community College",
			"Western Kentucky University",
			"Western Univerisity",
			"Westmont College",
			"Wheaton College",
			"Wheaton College, MA",
			"Wilfrid Laurier University",
			"William Paterson University",
			"Williams College",
			"Winthrop University",
			"Wood Tobe-Coburn",
			"Worcester State University",
			"Xavier University",
			"Yale University",
			"Yeshiva University",
			"Yonsei University",
			"York University",
			"Youngstown State University",
			"Zurich University"
		);
		
		// Inicializamos los valores de cada campo con autocompletado

		$target_industries = get_user_meta( $user->ID, 'target_industries', true ); // get_user_meta "deserializa" el valor almacenado
		if (!is_array($target_industries)) {
			$target_industries = array();
		}

		$college_attended = get_user_meta( $user->ID, 'college_attended', true );
		if (!is_array($college_attended)) {
			$college_attended = array();
		}

		$pic_data = get_user_meta( $user->ID, 'profile_pic', true );

		/*
		echo "pic_data:";
		echo "<pre>";
		print_r($pic_data);
		echo "</pre>";
		*/		

		if (!empty($pic_data) && !isset($pic_data['error'])) {
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
					<img width="125px" height="125px" id="profile-pic-preview" src="<?php echo $profile_pic_url; ?>" alt="Your profile image" style="border-radius: 50%" />
					<br/>
					<input type="file" id="profile-pic" name="profile-pic" onchange="readURL(this);" accept=".jpg,.jpeg,.png,.gif" />
					<p class="description">(Recommended 250x250 pixels)</p>
      	</td>
      </tr>
      <tr>
				<th><label for="college-attended">College Attended</label></th>
				<td>
					<select id="college-attended" name="college-attended[]" multiple="multiple" class="profile-field">
						<?php foreach($school_tags as $tag) { ?>
							<option value="<?php echo $tag; ?>" <?php echo in_array($tag, $college_attended) ? 'selected="selected"' : ''; ?>><?php echo $tag; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="year-graduation">Year of Graduation</label></th>
				<td><input type="text" maxlength="4" id="year-graduation" name="year-graduation" value="<?php echo esc_attr( get_the_author_meta( 'year_graduation', $user->ID ) ); ?>" class="regular-text" /></td>
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
				<th><label for="main-skills">What are your main skills?</label></th>
				<td><input type="text" id="main-skills" name="main-skills" value="<?php echo esc_attr( get_the_author_meta( 'main_skills', $user->ID ) ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="target-industries">What are your target industries?</label><p class="description">(Up to 5)</p></th>
				<td>
					<select id="target-industries" name="target-industries[]" multiple="multiple" class="profile-field">
						<?php foreach($industries_tags as $tag) { ?>
							<option value="<?php echo $tag; ?>" <?php echo in_array($tag, $target_industries) ? 'selected="selected"' : ''; ?>><?php echo $tag; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="career-situation">What best describes your current career situation?</label></th>
				<td>
					<input type="radio" id="career-situation" name="career-situation" value="1" <?php echo esc_attr( get_the_author_meta( 'career_situation', $user->ID ) ) == 1 ? 'checked="checked"' : '' ; ?> />Student, actively seeking internships<br/>
					<input type="radio" id="career-situation" name="career-situation" value="2" <?php echo esc_attr( get_the_author_meta( 'career_situation', $user->ID ) ) == 2 ? 'checked="checked"' : '' ; ?> />Student, actively seeking first full-time job<br/>
					<input type="radio" id="career-situation" name="career-situation" value="3" <?php echo esc_attr( get_the_author_meta( 'career_situation', $user->ID ) ) == 3 ? 'checked="checked"' : '' ; ?> />Unemployed, actively seeking full-time employment<br/>
					<input type="radio" id="career-situation" name="career-situation" value="4" <?php echo esc_attr( get_the_author_meta( 'career_situation', $user->ID ) ) == 4 ? 'checked="checked"' : '' ; ?> />Employed, actively seeking new full-time opportunities<br/>
					<input type="radio" id="career-situation" name="career-situation" value="5" <?php echo esc_attr( get_the_author_meta( 'career_situation', $user->ID ) ) == 5 ? 'checked="checked"' : '' ; ?> />Employed, open to new opportunities<br/>
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
		</tbody>
	</table>
	<?php //if ($is_premium_member) {	?>
	<!--h3>(Will only be available for Premium Members)</h3-->
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="resume">Upload Resume</label></th>
				<td>
					<?php $resume = get_user_meta( $user->ID, 'resume', true ); ?>
					<div id="resume-preview" <?php echo empty($resume['url']) ? 'style="display: none; "' : '' ?>>
						<img width="48px" height="64px" src="<?php echo includes_url() . "images/media/document.png"; ?>" alt="Your Resume" />
						<br/>
						<span id="resume-name">
							<?php echo !empty($resume['url']) ? basename($resume['url']) : ''; ?>
							<br/>
						</span>
						<input id="resume-change" type="button" value="Change File" onclick="changeFile()" />
					</div>
					<input <?php echo !empty($resume['url']) ? 'style="display: none; "' : '' ?> type="file" onchange="validateFile(this);" id="resume" name="resume" class="regular-text" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
				</td>
			</tr>
		</tbody>
	</table>
	<?php //} ?>
	<h3>Privacity Settings</h3>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="public-profile-status">Public Profile</label></th>
				<td>
					<input type="radio" id="public-profile-status" name="public-profile-status" value="0" <?php echo esc_attr( get_the_author_meta( 'public_profile_status', $user->ID ) ) == 0 ? 'checked="checked"' : '' ; ?> />Everyone can view my profile<br/>
					<input type="radio" id="public-profile-status" name="public-profile-status" value="1" <?php echo esc_attr( get_the_author_meta( 'public_profile_status', $user->ID ) ) == 1 ? 'checked="checked"' : '' ; ?> />Only FindSpark members can view my profile<br/>
					<?php //if ($is_premium_member) { // Restringimos para que las otras opciones esten disponibles SOLO para miembros premium ?>
					<input type="radio" id="public-profile-status" name="public-profile-status" value="2" <?php echo esc_attr( get_the_author_meta( 'public_profile_status', $user->ID ) ) == 2 ? 'checked="checked"' : '' ; ?> />Only FindSpark employers can view my profile<br/>
					<input type="radio" id="public-profile-status" name="public-profile-status" value="3" <?php echo esc_attr( get_the_author_meta( 'public_profile_status', $user->ID ) ) == 3 ? 'checked="checked"' : '' ; ?> />FindSpark members &amp; employers can view my profile<br/>
					<?php //} ?>
				</td>
			</tr>
			<tr>
				<th><label for="contact-me-status">Contact me</label></th>
				<td>
					<input type="radio" id="contact-me-status" name="contact-me-status" value="0" <?php echo esc_attr( get_the_author_meta( 'contact_me_status', $user->ID ) ) == 0 ? 'checked="checked"' : '' ; ?> />Everyone can contact me<br/>
					<input type="radio" id="contact-me-status" name="contact-me-status" value="1" <?php echo esc_attr( get_the_author_meta( 'contact_me_status', $user->ID ) ) == 1 ? 'checked="checked"' : '' ; ?> />Only FindSpark members can contact me<br/>
					<?php //if ($is_premium_member) { // Restringimos para que las otras opciones esten disponibles SOLO para miembros premium ?>
					<input type="radio" id="contact-me-status" name="contact-me-status" value="2" <?php echo esc_attr( get_the_author_meta( 'contact_me_status', $user->ID ) ) == 2 ? 'checked="checked"' : '' ; ?> />Only FindSpark employers can contact me<br/>
					<input type="radio" id="contact-me-status" name="contact-me-status" value="3" <?php echo esc_attr( get_the_author_meta( 'contact_me_status', $user->ID ) ) == 3 ? 'checked="checked"' : '' ; ?> />FindSpark members &amp; employers can contact me<br/>
					<?php //} ?>
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
	$is_premium_member = false;
	$user = get_userdata( $user_id );
	foreach ($user->roles as $role) {
		if (in_array($role, array('subscriber', 'premiummember'))) {
			$is_job_seeker = true;
			$is_premium_member = $role == 'premiummember';
		}
	}

	if ($is_job_seeker) {
		update_user_meta( $user_id, 'college_attended', $_POST['college-attended'] );
		update_user_meta( $user_id, 'year_graduation', sanitize_text_field( $_POST['year-graduation'] ) );
		update_user_meta( $user_id, 'job_title', sanitize_text_field( $_POST['job-title'] ) );
		update_user_meta( $user_id, 'job_company', sanitize_text_field( $_POST['job-company'] ) );
		update_user_meta( $user_id, 'main_skills', sanitize_text_field( $_POST['main-skills'] ) );
		update_user_meta( $user_id, 'career_situation', sanitize_text_field( $_POST['career-situation'] ) );
		update_user_meta( $user_id, 'target_industries', $_POST['target-industries'] );

		if ( $_FILES['profile-pic']['error'] === UPLOAD_ERR_OK ) {
			// Obtenemos el tipo del archivo subido. Esto es retornado como "type/extension"
			$arr_file_type = wp_check_filetype(basename($_FILES['profile-pic']['name']));
			$uploaded_file_type = $arr_file_type['type'];

			// Lista de formatos aceptados
			$allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
			// Verificamos si el tipo de archivo se encuentra entre los aceptados
			if( in_array($uploaded_file_type, $allowed_file_types) ) {
				$max_img_size = 2; // MB
				// Verificamos que el tamanho del archivo no se exceda del limite
				if ( $_FILES['profile-pic']['size'] <= ($max_img_size * 1024 * 1024) ) {
					// Por defecto Wordpress hara fallar la subida si no se pone: 'test_form' => false
					$upload_overrides = array( 'test_form' => false );
					// Subimos la imagen mediante wordpress (la guarda en wp-contents/uploads/anho/mes/)
				 	$img = wp_handle_upload( $_FILES['profile-pic'], $upload_overrides );
				 	// Actualizamos la informacion del campo respectivo
					update_user_meta( $user_id, 'profile_pic', $img );
				}
			}
		}

		update_user_meta( $user_id, 'age', sanitize_text_field( $_POST['age'] ) );
		update_user_meta( $user_id, 'ethnicity', sanitize_text_field( $_POST['ethnicity'] ) );

		if ( $_FILES['resume']['error'] === UPLOAD_ERR_OK ) {
			// Obtenemos el tipo del archivo subido. Esto es retornado como "type/extension"
			$arr_file_type = wp_check_filetype(basename($_FILES['resume']['name']));
			$uploaded_file_type = $arr_file_type['type'];

			// Lista de formatos aceptados (pdf, doc y docx)
			$allowed_file_types = array('application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
			// Verificamos si el tipo de archivo se encuentra entre los aceptados
			if( in_array($uploaded_file_type, $allowed_file_types) ) {
				$max_file_size = 10; // MB
				// Verificamos que el tamanho del archivo no se exceda del limite
				if ( $_FILES['resume']['size'] <= ($max_file_size * 1024 * 1024) ) {
					// Por defecto Wordpress hara fallar la subida si no se pone: 'test_form' => false
					$upload_overrides = array( 'test_form' => false );
					// Subimos la imagen mediante wordpress (la guarda en wp-contents/uploads/anho/mes/)
				 	$file = wp_handle_upload( $_FILES['resume'], $upload_overrides );
				 	// Actualizamos la informacion del campo respectivo
					update_user_meta( $user_id, 'resume', $file );
				}
			}
		}

		$public_profile_status = sanitize_text_field( $_POST['public-profile-status'] );
		$contact_me_status = sanitize_text_field( $_POST['contact-me-status'] );
		/*
		if (!$is_premium_member) { // Si no es miembro premium, no debe poder escoger las opciones adicionales de los radiobutton (value = 2 y 3)
			if ($public_profile_status >= 2) {
				$public_profile_status = 0;
			}
			if ($contact_me_status >= 2) {
				$contact_me_status = 0;
			}
		}
		*/
		update_user_meta( $user_id, 'public_profile_status', $public_profile_status );
		update_user_meta( $user_id, 'contact_me_status', $contact_me_status );
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