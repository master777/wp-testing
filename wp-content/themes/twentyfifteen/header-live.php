<?php
/**
 *EMILY YOU NO LONGER HAVE TO CHANGE THE NAV IN SINGLE POST. YOU JUST HAVE TO CHANGE IT HERE. 
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta name="msvalidate.01" content="9B70035A315210978C381723FD9CF78A" />
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="initial-scale = 1.0, width=device-width, user-scalable = no" />

<title><?php
  /*
   * Print the <title> tag based on what is being viewed.
   */
  if (is_wpjb() ) :
    echo Wpjb_Project::getInstance()->title;
    if (wpjb_is_routed_to("index.single")) :
      echo ' | ' . get_bloginfo('name');
    endif;
  else:

    global $page, $paged;
  
    wp_title( '|', true, 'right' );
    //bloginfo('name');
  
  
    // Add a page number if necessary:
    if ( $paged >= 2 || $page >= 2 )
      echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );  
  endif; ?>
</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" type="text/css" charset="utf-8"  href="<?php bloginfo('template_url'); ?>/style-mg.css"  />
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon.png" />
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon.ico" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<meta property="twitter:account_id" content="198730367" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<!-- Put in for specialty lightbox / Commented out for future removal -->
<!-- <script type='text/javascript'>(function () { var ds = document.createElement('script'); ds.type = 'text/javascript'; ds.async = true; ds.src = ('https:' == document.location.protocol ? 'https://90c3bdbac687e9eadd54-1d4ffd4392a4131a848fdbd6d8fe04c7.ssl.cf1.rackcdn.com/digioh_lightbox.js' : 'http://ca6121da4728a32f1a77-1d4ffd4392a4131a848fdbd6d8fe04c7.r99.cf1.rackcdn.com/digioh_lightbox.js'); var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ds, s); })();</script> -->

<?php
  /* We add some JavaScript to pages with the comment form
   * to support sites with threaded comments (when in use).
   */
  if ( is_singular() && get_option( 'thread_comments' ) )
    wp_enqueue_script( 'comment-reply' );


  /* Always have wp_head() just before the closing </head>
   * tag of your theme, or you will break many plugins, which
   * generally use this hook to add elements to <head> such
   * as styles, scripts, and meta tags.
   */
  wp_head();
?>
 <?php
        if (is_single() || is_page()) {
            if (is_wpjb() != 1) {
                $twitter_url = get_permalink();
                $twitter_title = get_the_title();
                $twitter_desc = get_the_excerpt();
                if (!$twitter_desc)
                    $twitter_desc = "FindSpark | The Largest Meetup for Interns and Recent Grads";
                if ($twitter_desc == "")
                    $twitter_desc = "FindSpark | The Largest Meetup for Interns and Recent Grads";
                $twitter_thumbs = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium');
                $twitter_thumb = $twitter_thumbs[0];

                if (!$twitter_thumb) {
                    $twitter_thumb = capturar_imagen();
                }
                $twitter_name = str_replace('@', '', get_the_author_meta('twitter'));
                ?>
                <meta name="twitter:card" content="summary" />
                <meta name="twitter:url" content="<?php echo $twitter_url; ?>" />
                <meta name="twitter:title" content="<?php echo $twitter_title; ?>" />
                <meta name="twitter:description" content="<?php echo esc_html_e($twitter_desc); ?>" />
                <meta name="twitter:image:src" content="<?php echo $twitter_thumb; ?>" />
                <meta name="twitter:site" content="@FindSpark" />
                <meta name="twitter:domain" content="https://www.findspark.com">
                <meta name="twitter:app:name:iphone" content="">
                <meta name="twitter:app:name:ipad" content="">
                <meta name="twitter:app:name:googleplay" content="">
                <meta name="twitter:app:url:iphone" content="">
                <meta name="twitter:app:url:ipad" content="">
                <meta name="twitter:app:url:googleplay" content="">
                <meta name="twitter:app:id:iphone" content="">
                <meta name="twitter:app:id:ipad" content="">
                <meta name="twitter:app:id:googleplay" content="">
                <?
                if ($twitter_name) {
                    ?>
                    <meta name="twitter:creator" content="@<?php echo $twitter_name; ?>" />
                    <?
                }
            }
        }
        ?>


</head>
<body <?php body_class(); ?>>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div id="page" class="hfeed">

  <header id="branding" role="banner">  
    <div id="PageTop">    
      <?php if (get_header_image()) : ?>
      <div class="logo">
        <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>" ><img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="<?php bloginfo('name'); ?> Logo" title="<?php bloginfo('name'); ?>" id="logo-img"/></a>
        <!--a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>" ><img src="<?php echo get_template_directory_uri(); ?>/images/findspark-logo-black.png" height="71" width="280" alt="<?php bloginfo('name'); ?> Logo" title="<?php bloginfo('name'); ?>" id="logo-img"/></a-->
      <?php endif; ?>
      </div>
    

    <div class="login-top clearfix">
      <ul id="menu-controls" class="menu">
        <?php if (!is_user_logged_in()) :?>
        <?php wp_nav_menu (array('theme_location' => 'login_control', 'container' => false, 'items_wrap' => '%3$s'));?>
        <?php else: ?>
        <li class="menu-item"><a href="<?php echo wp_logout_url( home_url() ); ?>" title="Logout">Logout</a></li>
        <?php endif; ?>
      </ul>
      <ul id="menu-login" class="menu">
        <?php fs_login_menu(); ?>
      </ul>
    </div>
</div>

  <div id="mobileMenu">
    <div id="mobileMenuInner">
      <span id="openMainMenuMobile">Menu</span>
    </div>
  </div>
  
  <nav id="access" role="navigation">
    <?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false)); ?>
    <form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
      <label for="s" class="assistive-text">Search</label>
      <input type="text" class="field" name="s" id="s" value="Search" onFocus="getFocus(this);" onBlur="loseFocus(this);" />
      <input type="submit" class="submit" name="submit" id="searchsubmit" value="Search" />
    </form>

  </nav>
  
  <!-- <div id="bar">
    <div class="largetext">
      <a href="http://conf.nycreativeinterns.com/">Meet Creatives from CollegeHumor, MoMA, Random House, Kickstarter, and more at Find & Follow Your Passion on Saturday, November 9th</a>
      <div class="smalltext">
        <a href="http://conf.nycreativeinterns.com/">Click here to Learn More and Register</a>
      </div>
    </div>
  </div> -->
  
  <div class="clear"></div>

<?php

// This is for blog posts - Leo 10/8/13

?>

<?php if ( !wpjb_is_routed_to ("index.index") ) { // the sidebar is visible only if the current page is not the wpjobboard home  ?>
<div id="sidebar_wrapper">
  <div id="sidebarSocialScroller" class="">
    <ul id="social_bar" style="text-align: center;">
      <li>
        <a alt="" href="https://twitter.com/share?via=FindSpark" class="twitter-share-button" data-url="<?php echo get_permalink($post->ID);?>"
          data-text="<?php single_post_title(); ?>" data-count="vertical">Tweet</a>
        <script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
      </li>
      <!--li>
        <div class="fb-like" data-href="<?php the_permalink() ?>" data-send="false" data-layout="box_count" data-width="48" data-show-faces="true">
        </div>
      </li-->
      <li>
        <div class="fb-share-button" data-href="<?php the_permalink() ?>" data-type="box_count">
        </div>
      </li>
      <!--li>
        <script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
        <script type="IN/Share"></script>
      </li-->
      <!--li class="plusonebutton">
        <g:plusone size="medium"></g:plusone>
      </li-->
      <!--li class="pinterest-mgs">
        <a data-pin-config="beside" data-pin-do="buttonBookmark" href="//pinterest.com/pin/create/button/">
          <img src="//assets.pinterest.com/images/PinExt.png" />
        </a>
        <script src="//assets.pinterest.com/js/pinit.js"></script>
      </li-->
    </ul>
  </div><!--sidebar-->
</div><!--sidebar_wrapper-->
<?php } ?>

<?php
if (is_page( array('conference', 'creativediaries', 'mixers' ))) {
?> 
<div id="sidebar_wrapper">
  <div id="sidebar" class="">
    <ul id="social_bar"><center>
      <li><a alt="" href="https://twitter.com/share?via=FindSpark" class="twitter-share-button" data-url="<?php echo get_permalink($post->ID); ?>"
data-text="<?php single_post_title(); ?>" data-count="vertical">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script></li>
      <li><div class="fb-like" data-href="<?php the_permalink() ?>" data-send="false" data-layout="box_count" data-width="48" data-show-faces="true"></div></li>
      <li><script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
<script type="IN/Share"></script></li>
            <li class="plusonebutton"><g:plusone size="medium"></g:plusone></li></center>
                </ul>
  </div><!--sidebar-->
</div><!--sidebar_wrapper-->

<?php } ?>

<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

</header><!-- #branding -->


  <div id="main">