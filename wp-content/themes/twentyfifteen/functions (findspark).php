<?php
/**
 * Twenty Eleven functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentyeleven_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

//================

// This allows a custom URL to public profiles
function custom_profile_rules() {	
	add_rewrite_tag('%user%', '([^&]+)');  
  add_rewrite_rule(
    '^profile/([^/]*)/?',
    'index.php?page_id=18250&user=$matches[1]', // '18250' is the ID of the "My Profile" page in the wp admin
    'top'
	);

	//flush_rewrite_rules(); // Once you get working, please comment this next line
}
add_action('init', 'custom_profile_rules', 10, 0);

//================

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 584;

/**
 * Tell WordPress to run twentyeleven_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'twentyeleven_setup' );

if ( ! function_exists( 'twentyeleven_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyeleven_setup() in a child theme, add your own twentyeleven_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, and Post Formats.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_setup() {

	/* Make Twenty Eleven available for translation.
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Eleven, use a find and replace
	 * to change 'twentyeleven' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'twentyeleven', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Load up our theme options page and related code.
	require( dirname( __FILE__ ) . '/inc/theme-options.php' );

	// Grab Twenty Eleven's Ephemera widget.
	require( dirname( __FILE__ ) . '/inc/widgets.php' );

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'twentyeleven' ) );
	
	// Use for Login and Logout Controls
	register_nav_menu('login_control', __('Login Control', 'twentyeleven'));
	
	// Use for Login Menu
	register_nav_menu('login', __('Login Menu', 'twentyeleven') );

	// Use for Login Menu when a user is logged in
	register_nav_menu('general_logged_in', __('General Member Logged In Menu', 'twentyeleven') );

	// Use for Login Menu when a user is logged in
	register_nav_menu('employer_logged_in', __('Employer Logged In Menu', 'twentyeleven') );

	// Use for Login Menu when a user is logged in
	register_nav_menu('premium_logged_in', __('Premium Logged In Menu', 'twentyeleven') );

	// Add support for a variety of post formats
	add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );

	// Add support for custom backgrounds
	add_custom_background();

	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
	add_theme_support( 'post-thumbnails' );

	// The next four constants set how Twenty Eleven supports custom headers.

	// By leaving empty, we allow for random image rotation.
	define( 'HEADER_IMAGE', '' );

	// Turn on random header image rotation by default.
	add_theme_support( 'custom-header', array( 'random-default' => true, 'default-text-color' => '000', 'height' => '45', 'flex-height' => true, 'width' => 280, 'flex-width' => false ) );


	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'wheel' => array(
			'url' => '%s/images/headers/wheel.jpg',
			'thumbnail_url' => '%s/images/headers/wheel-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Wheel', 'twentyeleven' )
		),
		'shore' => array(
			'url' => '%s/images/headers/shore.jpg',
			'thumbnail_url' => '%s/images/headers/shore-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Shore', 'twentyeleven' )
		),
		'trolley' => array(
			'url' => '%s/images/headers/trolley.jpg',
			'thumbnail_url' => '%s/images/headers/trolley-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Trolley', 'twentyeleven' )
		),
		'pine-cone' => array(
			'url' => '%s/images/headers/pine-cone.jpg',
			'thumbnail_url' => '%s/images/headers/pine-cone-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Pine Cone', 'twentyeleven' )
		),
		'chessboard' => array(
			'url' => '%s/images/headers/chessboard.jpg',
			'thumbnail_url' => '%s/images/headers/chessboard-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Chessboard', 'twentyeleven' )
		),
		'lanterns' => array(
			'url' => '%s/images/headers/lanterns.jpg',
			'thumbnail_url' => '%s/images/headers/lanterns-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Lanterns', 'twentyeleven' )
		),
		'willow' => array(
			'url' => '%s/images/headers/willow.jpg',
			'thumbnail_url' => '%s/images/headers/willow-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Willow', 'twentyeleven' )
		),
		'hanoi' => array(
			'url' => '%s/images/headers/hanoi.jpg',
			'thumbnail_url' => '%s/images/headers/hanoi-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Hanoi Plant', 'twentyeleven' )
		)
	) );
}
endif; // twentyeleven_setup

/** 
 * Set up jQuery for WordPress
 */

function jQuery_load () {
	wp_enqueue_script('jquery', '' , '', '' , true);
	wp_enqueue_script('jquery_easing', get_template_directory_uri() .'/js/jquery.easing.js', array('jquery'), '1.0.0', true);
	wp_enqueue_script('jquery_coursel', get_template_directory_uri() .'/js/carouFredSel.js', array('jquery'), '1.0.0', true);
	wp_enqueue_script('jquery_global', get_template_directory_uri() .'/js/global.js', array('jquery'), '1.0.0', true);
}	

add_action ('wp_enqueue_scripts', 'jQuery_load');




/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function twentyeleven_excerpt_length( $length ) {
	return 70;
}
add_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 */
function twentyeleven_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( '', 'twentyeleven' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyeleven_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function twentyeleven_auto_excerpt_more( $more ) {
	return ' &hellip;' . twentyeleven_continue_reading_link();
}
add_filter( 'excerpt_more', 'twentyeleven_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function twentyeleven_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= twentyeleven_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyeleven_custom_excerpt_more' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function twentyeleven_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyeleven_page_menu_args' );

/**
 * Register our sidebars and widgetized areas. Also register the default Epherma widget.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_widgets_init() {

	register_widget( 'Twenty_Eleven_Ephemera_Widget' );

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s"><div style="margin:0 auto; max-width:250px; overflow:hidden;" >',
		'after_widget' => "</div></aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Showcase Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-2',
		'description' => __( 'The sidebar for the optional Showcase Template', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area One', 'twentyeleven' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Two', 'twentyeleven' ),
		'id' => 'sidebar-4',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Three', 'twentyeleven' ),
		'id' => 'sidebar-5',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'twentyeleven_widgets_init' );

/**
 * Display navigation to next/previous pages when applicable
 */
function twentyeleven_content_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentyeleven' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyeleven' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></div>
		</nav><!-- #nav-above -->
	<?php endif;
}

/**
 * Top Menu Management
 */
function fs_login_menu() {
	if (is_user_logged_in()) {
	global $current_user;
	get_currentuserinfo();
	$userid = $current_user->ID;
	$usermeta = get_user_meta($userid);
	$userrole = $current_user->roles;	
	?>
		<?php if (empty($current_user->user_firstname)) {
				$username = $current_user->user_login;
			} else {
				$username = $current_user->user_firstname;
			}
		?>
		<li class="menu-item"><?php echo get_theme_mod('welcome_message') . ' ' .  $username; ?></li> 
	<?php
		if ($userrole[0] == 'premiummember' ||  $userrole[0] == 'administrator' ||  $userrole[0] == 'editor' ) {
			wp_nav_menu (array('theme_location' => 'premium_logged_in', 'container' => false, 'items_wrap' => '%3$s'));
		} elseif (current_user_can("manage_jobs")) {
			wp_nav_menu (array('theme_location' => 'employer_logged_in', 'container' => false, 'items_wrap' => '%3$s'));
		} else {
			wp_nav_menu (array('theme_location' => 'general_logged_in', 'container' => false, 'items_wrap' => '%3$s'));
		}
	} else {
		wp_nav_menu (array('theme_location' => 'login', 'container' => false, 'items_wrap' => '%3$s'));
	}
}

/**
 * Return the URL for the first link found in the post content.
 *
 * @since Twenty Eleven 1.0
 * @return string|bool URL or false when no link is present.
 */
function twentyeleven_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function twentyeleven_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-5' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}

if ( ! function_exists( 'twentyeleven_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyeleven_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyeleven' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'twentyeleven' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'twentyeleven' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentyeleven' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'twentyeleven' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for twentyeleven_comment()

if ( ! function_exists( 'twentyeleven_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own twentyeleven_posted_on to override in a child theme
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span>', 'twentyeleven' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		sprintf( esc_attr__( 'View all posts by %s', 'twentyeleven' ), get_the_author() ),
		esc_html( get_the_author() )
	);
}
function twentyeleven_posted_on_single() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a>', 'twentyeleven' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		sprintf( esc_attr__( 'View all posts by %s', 'twentyeleven' ), get_the_author() ),
		esc_html( get_the_author() )
	);
}
endif;

/**
 * Adds two classes to the array of body classes.
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_body_classes( $classes ) {

	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	if ( is_singular() && ! is_home() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) )
		$classes[] = 'singular';

	return $classes;
}
add_filter( 'body_class', 'twentyeleven_body_classes' );



function get_post_thumbnail() {
$files = get_children('post_parent='.get_the_ID().'&post_type=attachment&post_mime_type=image');
if($files) :
$keys = array_reverse(array_keys($files));
$j=0;
$num = $keys[$j];
$image=wp_get_attachment_image($num, 'large', false);
$imagepieces = explode('"', $image);
$imagepath = $imagepieces[1];
$thumb=wp_get_attachment_thumb_url($num);
echo "<img src=\"$thumb\" height=\"170px\" max-width=\"170px\" class=\"front-list_thumbnail\" alt=\"\" />";
else:
echo "<img src=\"http://nycreativeinterns.com/images/NYCI_thumb.jpeg\" height=\"100px\" class=\"front-list_thumbnail\" alt=\"\" />";
endif;
}

function get_excerpt_thumbnail() {
$files = get_children('post_parent='.get_the_ID().'&post_type=attachment&post_mime_type=image');
if($files) :
$keys = array_reverse(array_keys($files));
$j=0;
$num = $keys[$j];
$image=wp_get_attachment_image($num, 'large', false);
$imagepieces = explode('"', $image);
$imagepath = $imagepieces[1];
$thumb=wp_get_attachment_thumb_url($num);
echo "<img src=\"$thumb\" height=\"100px\" class=\"front-list_thumbnail\" alt=\"\" />";
else:
echo "<img src=\"http://nycreativeinterns.com/images/NYCI_thumb.jpeg\" height=\"100px\" class=\"front-list_thumbnail\" alt=\"\" />";
endif;
}

if ( function_exists( 'add_theme_support' ) ) { 
  add_theme_support( 'post-thumbnails' ); 
}

add_image_size('excerpt-thumbnail', 140, 140, true);


function string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}

function wp_new_excerpt($text)
{
	if ($text == '')
	{
		$text = get_the_content('');
		$text = strip_shortcodes( $text );
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]>', $text);
		$text = strip_tags($text);
		$text = nl2br($text);
		$excerpt_length = apply_filters('excerpt_length', 7655);
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words) > $excerpt_length) {
			array_pop($words);
			array_push($words, '');
			$text = implode(' ', $words);
		}
	}
	return $text;
}

/* Remove Private From Post TItles */
function the_title_trim($title)
  {
   $pattern[0] = '/Protected:/';
    $pattern[1] = '/Private:/';
    $replacement[0] = ''; // Enter some text to put in place of Protected:
    $replacement[1] = ''; // Enter some text to put in place of Private:

    return preg_replace($pattern, $replacement, $title);
  }
  add_filter('the_title', 'the_title_trim');

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'wp_new_excerpt');


// Login Form Changes
function login_logo() { ?>
    <style type="text/css">
    	body.login {background: #e2e2e2;}
		body.login div#login {background: white; height: 100%; padding-left: 10px; padding-right: 10px;}
        body.login div#login h1 a {
            background-image: url(<?php echo get_header_image(); ?>);
            background-size: 100%;
            height: <?php echo get_custom_header()->height; ?>px;
            width: <?php echo get_custom_header()->width; ?>px;
        }
        body.login #loginform {box-shadow: none;}
        body.login #nav {display: none;}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'login_logo' );

function login_logo_url() {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'login_logo_url' );

function login_logo_url_title() {
    return 'FindSpark | The Largest Meetup for Interns and Recent Grads | Setting up every young creative for career success';
}
add_filter( 'login_headertitle', 'login_logo_url_title' );

// Handling Failed Login Pages
add_action( 'wp_login_failed', 'fs_login_fail' );  // hook failed login

function fs_login_fail( $username ) {
   $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
   // if there's a valid referrer, and it's not the default log-in screen
   if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {

	  $pos = strpos($referrer, '?login=failed');

		if($pos === false) {
		 	// add the failed
		 	wp_redirect( $referrer . '?login=failed' );  // let's append some information (login=failed) to the URL for the theme to use
		}
		else {
			// already has the failed don't appened it again
			wp_redirect( $referrer );  // already appeneded redirect back
		}	

      exit;
   }
}

// Gravity Forms Specific

add_filter("gform_address_street2", "change_address_street2", 10, 2);

function change_address_street2($label, $form_id){
    return "Apartment number, etc";
}

// Disabiling the Administrative Bar for Non-Admins
if (!current_user_can('manage_options')) {
	add_filter('show_admin_bar', '__return_false');
}

//Adding the Open Graph in the Language Attributes
function add_opengraph_doctype( $output ) {
		return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
	}
add_filter('language_attributes', 'add_opengraph_doctype');

//Lets add Open Graph Meta Info

function insert_tw_in_head() {
        global $post;
        if (!is_singular()) {
            return;
        }
        echo "\n";
        echo '<meta name="twitter:card" content="summary" />';
        echo "\n";
        $default = "| FindkSpark";
        $description = "FindSpark sets up every young creative for career success through events, a membership program, and a highly-curated job board.";
       
        if (function_exists("is_wpjb") && (is_wpjb() || is_wpjr())) :
            $twitter_title = Wpjb_Project::getInstance()->title;
            $description = "FindSpark sets up every young creative for career success through events, a membership program, and a highly-curated job board.";
            if (wpjb_is_routed_to("job")) :
               $twitter_title = 'Job Alert: ' . $twitter_title;
			    //$twitter_title .= ' at ' . wpjb_job_company_string(Wpjb_Project::getInstance()->placeHolder->job);
                //$twitter_title .= ' '.wpjb_job_type_string();
                $twitter_title .= ' | FindSpark ';
                //$twitter_url = wpjb_link_to("job", Wpjb_Project::getInstance()->placeHolder->job);
            else:
                $twitter_url = get_permalink();
            endif;

        else:
            $twitter_title = get_the_title();
            $twitter_url = get_permalink();
            $description = $post->post_excerpt;
            if ($description) {
                $description = $post->post_content;
            }
            if ($description) {
                $description = "FindSpark sets up every young creative for career success through events, a membership program, and a highly-curated job board.";
            }
        endif;

        if (!has_post_thumbnail($post->ID)) { //the post does not have featured image, use a default image
            $thumbnail_src = get_template_directory_uri() . '/images/findspark_logo.jpg'; //replace this with a default image on your server or an image in your media library
        } else {
            $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium');
            $thumbnail_src = esc_attr($thumbnail_src[0]);
        }

        echo '<meta name="twitter:url" content="' . $twitter_url . ' " />';
        echo "\n";
        echo '<meta name="twitter:title" content="' . $twitter_title . '" />';
        echo "\n";
        echo '<meta name="twitter:description" content="' . $description . '" />';
        echo "\n";
        echo '<meta name="twitter:image:src" content="' . $thumbnail_src . '" />';
        echo "\n";
        echo '<meta name="twitter:site" content="@FindSpark" />';
        echo "\n";
        echo '<meta name="twitter:domain" content="https://www.findspark.com">';
        echo "\n";
        echo '<meta name="twitter:app:name:iphone" content="">';
        echo "\n";
        echo '<meta name="twitter:app:name:ipad" content="">';
        echo "\n";
        echo '<meta name="twitter:app:name:googleplay" content="">';
        echo "\n";
        echo '<meta name="twitter:app:url:iphone" content="">';
        echo "\n";
        echo '<meta name="twitter:app:url:ipad" content="">';
        echo "\n";
        echo '<meta name="twitter:app:url:googleplay" content="">';
        echo "\n";
        echo '<meta name="twitter:app:id:iphone" content="">';
        echo "\n";
        echo '<meta name="twitter:app:id:ipad" content="">';
        echo "\n";
        echo '<meta name="twitter:app:id:googleplay" content="">';
        echo "\n";
    }

    add_action('wp_head', 'insert_tw_in_head', 5);



function insert_fb_in_head() {
	global $post;
	if ( !is_singular()) //if it is not a post or a page
		return;
        echo '<meta property="og:title" content="';
    		if (function_exists("is_wpjb") && (is_wpjb() || is_wpjr()) ) :
			if (wpjb_is_routed_to("job")) :
			echo 'Job alert: ';
			endif;
				echo Wpjb_Project::getInstance()->title;
				//echo ' at ' . wpjb_job_company_string(Wpjb_Project::getInstance()->placeHolder->job);
				if (wpjb_is_routed_to("job")) :
				echo ' at ' /*. wpjb_job_company_string(Wpjb_Project::getInstance()->placeHolder->job)*/;
									//echo ' | FindSpark';
				endif;
			else:
				echo get_the_title();
			endif;
		echo '"/>';
		echo "\n"; // Creating a new line for code readability

        echo '<meta property="og:type" content="article"/>';
		echo "\n"; // Creating a new line for code readability

        echo '<meta property="og:url" content="';
			if (function_exists("is_wpjb") && (is_wpjb() || is_wpjr()) && wpjb_is_routed_to("job")) :
				echo wpjb_link_to("job", Wpjb_Project::getInstance()->placeHolder->job);
			else:
				echo get_permalink();
			endif;
        echo '"/>';
		echo "\n"; // Creating a new line for code readability
        
        echo '<meta property="og:site_name" content="FindSpark"/>';
		echo "\n"; // Creating a new line for code readability
		
	if(!has_post_thumbnail( $post->ID )) { //the post does not have featured image, use a default image
		//$default_image=get_theme_mod('social_thumbnail'); //replace this with a default image on your server or an image in your media library
		  $default_image = get_template_directory_uri() . '/images/findspark_logo.jpg';
		echo '<meta property="og:image" content="' . $default_image . '"/>';
	}
	else{
		$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
		echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
	}
	echo "\n"; // Creating a new line for code readability
	  //if (function_exists("is_wpjb") && (is_wpjb() || is_wpjr())) :
      //      $description = "FindSpark sets up every young creative for career success through events, a membership program, and a highly-curated job board.";
      //  else:
      // $description = get_theme_mod('social_description');
	  //    $description = "FindSpark sets up every young creative for career success through events, a membership program, and a highly-curated job board.";
      //  endif;
	          if (function_exists("is_wpjb") && (is_wpjb() || is_wpjr())) :
       $description = " FindSpark posts the best creative internship and entry-level jobs in New York City.";
        else:
            $description = $post->post_excerpt;
            if ($description) {
                $description = $post->post_content;
            }
            if ($description) {
                $description = " FindSpark posts the best creative internship and entry-level jobs in New York City.";
            }
        endif;
	echo '<meta property="og:description" content="' . $description . '" />';
	echo "\n"; // Creating a new line for code readability	
}
//add_action( 'wp_head', 'insert_fb_in_head', 5 );

function findspark_customize_register( $wp_customize ) {

	$wp_customize->add_setting('social_description');
	$wp_customize->add_setting('social_thumbnail');
	$wp_customize->add_setting('welcome_message');
	
	$wp_customize->add_section('findspark_social', array(
		'title'		=> __('FindSpark Social Media', 'twentyeleven'),
		'priority'	=> 40
		)
	);
	
	$wp_customize->add_section('findspark_user_settings', array(
		'title'		=> __('FindSpark User Settings', 'twentyeleven'),
		'priority'	=> 50
		)
	);


	$wp_customize->add_control(new WP_Customize_Control(
		$wp_customize,
		'findspark_social_description',
		array(
			'label'		=> __('Description for Social Media', 'twentyeleven'),
			'section'	=> 'findspark_social',
			'settings'	=> 'social_description')
		)
	);

	$wp_customize->add_control(new WP_Customize_Image_Control(
		$wp_customize,
		'findspark_social_thumbnail',
		array(
			'label'		=> __('Upload an image to appear for Social Media', 'twentyeleven'),
			'section'	=> 'findspark_social',
			'settings'	=> 'social_thumbnail')
		)
	);

	$wp_customize->add_control(new WP_Customize_Control(
		$wp_customize,
		'findspark_welcome_message',
		array(
			'label'		=> __('Welcome Message For User', 'twentyeleven'),
			'section'	=> 'findspark_user_settings',
			'settings'	=> 'welcome_message')
		)
	);

	$pages = get_pages(array(
		'meta_key'		=> '_wp_page_template',
		'meta_value'	=> 'page-sub-menu.php',
		'hierachical'	=> 0
	));
	
	$wp_customize->add_section('findspark_subpages_menu', array(
		'title'		=> __('FindSpark Sub-Menu Titles', 'twentyeleven'),
		'priority'	=> 51
		) );
	
	foreach ($pages as $page) {
		$page_id = $page->ID;
		$page_title = $page->post_title;
		$wp_customize->add_setting("sub_menu_$page_id");
		
		$wp_customize->add_control(New WP_Customize_Control(
			$wp_customize,
			"findspark_page_submenu_$page_id",
			array(
				'label'		=> "Sub-menu title for $page_title",
				'section'	=> 'findspark_subpages_menu',
				'settings'	=> "sub_menu_$page_id")
			)
		);
	}

	
	
}

add_action( 'customize_register', 'findspark_customize_register' );

/**
* Merge Tags as Dynamic Population Parameters
* http://gravitywiz.com/dynamic-products-via-post-meta/
*/

add_filter('gform_pre_render', 'gw_prepopluate_merge_tags');
function gw_prepopluate_merge_tags($form) {
    
    $filter_names = array();
    
    foreach($form['fields'] as &$field) {
        
        if(!rgar($field, 'allowsPrepopulate'))
            continue;
        
        // complex fields store inputName in the "name" property of the inputs array
        if(is_array(rgar($field, 'inputs')) && $field['type'] != 'checkbox') {
            foreach($field['inputs'] as $input) {
                if(rgar($input, 'name'))
                    $filter_names[] = array('type' => $field['type'], 'name' => rgar($input, 'name'));
            }
        } else {
            $filter_names[] = array('type' => $field['type'], 'name' => rgar($field, 'inputName'));
        }
        
    }
    
    foreach($filter_names as $filter_name) {
        
        $filtered_name = GFCommon::replace_variables_prepopulate($filter_name['name']);
        
        if($filter_name['name'] == $filtered_name)
            continue;
        
        add_filter("gform_field_value_{$filter_name['name']}", create_function("", "return '$filtered_name';"));
    }
    
    return $form;
}

//add_filter( 'jetpack_disable_twitter_cards', '__return_true', 99 );
 function capturar_imagen() {
        global $post, $posts;
            $first_img = '';
            ob_start();
            ob_end_clean();
        if(preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches)){
                $first_img = $matches [1] [0];
            return $first_img;
        }
        else {
            $first_img = "http://ejemplo.com/images/default-thumb.jpg";
        }
    return $first_img;
    }
if ( (isset($_GET['action']) && $_GET['action'] != 'logout') || (isset($_POST['login_location']) && !empty($_POST['login_location'])) ) {
	add_filter('login_redirect', 'my_login_redirect', 10, 3);
	function my_login_redirect() {
		$location = $_SERVER['HTTP_REFERER'];
		wp_safe_redirect($location);
		exit();
	}
}
