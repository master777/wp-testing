<?php
/**
 * Template Name: User Profile (Public)
 *
 */

global $wp_query;
global $wpdb;

function redirec_to_home() {
  wp_redirect( home_url() );
  exit();
}

if (!empty($wp_query->query_vars['user'])) {
  $user_nicename = $wp_query->query_vars['user'];
  $user_profile = get_user_by("slug", $user_nicename);
  
  if (!empty($user_profile)) {
    $is_premium_member = false;
    $is_job_seeker = false;
    foreach ($user_profile->roles as $role) {
      if ( in_array( $role, array( 'subscriber', 'premiummember' ) ) ) {
        $is_job_seeker = true;
        //$is_premium_member = $role == 'premiummember';
        $is_premium_member = true; // habilitado siempre solo para testeo
      }
    }

    if ($is_job_seeker) {
      function set_title($title, $sep) {
        global $user_profile;
        $title = $user_profile->first_name . " " . $user_profile->last_name . " - Findspark";
              
        return $title;
      }
      add_filter( 'wp_title', 'set_title', 10, 2 );
      

      $email = esc_attr( get_the_author_meta( 'email', $user_profile->ID ) );
      $website = esc_attr( get_the_author_meta( 'url', $user_profile->ID ) );
      $twitter = esc_attr( get_the_author_meta( 'twitter', $user_profile->ID ) );
      $linkedin = esc_attr( get_the_author_meta( 'linkedin', $user_profile->ID ) );
      //$facebook = esc_attr( get_the_author_meta( 'facebook', $user_profile->ID ) );
      //$google_plus = esc_attr( get_the_author_meta( 'googleplus', $user_profile->ID ) );
      //$yahoo = esc_attr( get_the_author_meta( 'yim', $user_profile->ID ) );

      /*
      if (!empty($facebook)) {
        $facebook = "https://www.facebook.com/" . $facebook;
      }
      if (!empty($twitter)) {
        $twitter = "https://www.twitter.com/" . $twitter;
      }
      */

      /*
      echo "website: $website <br/>";
      echo "facebook: $facebook <br/>";
      echo "twitter: $twitter <br/>";
      echo "yahoo: $yahoo <br/>";
      echo "google_plus: $google_plus <br/>";
      */

      $pic_data = get_user_meta( $user_profile->ID, 'profile_pic', true );
      if (!empty($pic_data) && !isset($pic_data['error'])) {
        $profile_pic_url = $pic_data['url'];
      } else {
        $profile_pic_url = get_template_directory_uri() . "/images/profile-default.png";
      }

      $college_attended = get_user_meta( $user_profile->ID, 'college_attended', true );
      if (!is_array($college_attended)) {
        $college_attended = array();
      }

      $target_industries = get_user_meta( $user_profile->ID, 'target_industries', true );
      if (!is_array($target_industries)) {
        $target_industries = array();
      }

      $skills = get_user_meta( $user_profile->ID, 'main_skills', true );
      if (!is_array($skills)) {
        $skills = array();
      }

      $resume = get_user_meta( $user_profile->ID, 'resume', true );
      /*
      echo "resume:";
      echo "<pre>";
      print_r($resume);
      echo "</pre>";
      */

      get_header();
?>
<!--link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" rel="stylesheet"-->
<!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css"-->
<link href="<?php echo get_template_directory_uri(); ?>/css/fs-profile.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.min.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/css/tooltips.css" rel="stylesheet">
<style>
  .test {
    background: yellow;
  }
  /*
  .blockquote {
    background: #f9f9f9;
    border-left: 10px solid #ccc;
    margin: 1.5em 10px;
    padding: 0.5em 10px;
    quotes: "\201C""\201D""\2018""\2019";
  }
  .blockquote:before {
    color: #ccc;
    content: open-quote;
    font-size: 4em;
    line-height: 0.1em;
    margin-right: 0.25em;
    vertical-align: -0.4em;
  }
  */  
  /*
  .icon-medall {
    background: url(images/medal_bronze.png) no-repeat;
    float: left;
    width: 32px;
    height: 32px;
  }
  */
</style>
<div id="primary">
  <div id="content" role="main">
    <?php get_sidebar(); ?>
    <div class="home_content">
      <article>
        <div class="section group">
          <div class="col span_2_of_6">
            <img title="profile image" width="200" height="200" class="img-circle" src="<?php echo $profile_pic_url; ?>">
          </div>
          <div class="col span_4_of_6">
            <!--h1 class="profile-name"><?php echo $user_profile->first_name . " " . $user_profile->last_name; ?>&nbsp;<span class="tooltip-top" data-tooltip="Premium Member"><img src="<?php echo get_template_directory_uri(); ?>/images/medal_bronze.png" width="32" height="32" /></span></h1-->            
            <h1 class="profile-name"><?php echo $user_profile->first_name . " " . $user_profile->last_name; ?>&nbsp;<?php if ($is_premium_member) { ?><span class="tooltip-top" data-tooltip="Premium Member"><i class="fa fa-check-circle" style="color: #009fbc;"></i></span><?php } ?></h1>            
            <a href="<?php echo $website; ?>" target="_blank" class="button large green"><i class="fa fa-globe"></i>&nbsp;My website</a>
            <a href="mailto:<?php echo $email; ?>" class="button large orange"><i class="fa fa-envelope"></i>&nbsp;Contact me</a>
            <br/>
          </div>
        </div>
      </article>
      <article>
        <div>
          <div>
            <div class="profile-title">
              Social Media
            </div>            
            <div class="profile-description">
              <a href="<?php echo $twitter; ?>" target="_blank">
                <i class="fa fa-twitter social-circle"></i>
              </a>
              <a href="<?php echo $linkedin; ?>" target="_blank">
                <i class="fa fa-linkedin social-circle"></i>
              </a>
            </div>
          </div>
          <div>
            <div class="profile-title">
              About me
            </div>            
            <div class="profile-description">
              <?php echo esc_attr( get_the_author_meta( 'description', $user_profile->ID ) ); ?>
            </div>
          </div>
          <div>
            <div class="profile-title">
              College Attended
            </div>            
            <div class="profile-description">
              <?php foreach ($college_attended as $college) {
              echo $college . "<br/>";
              } ?>
            </div>
          </div>
          <div>
            <div class="profile-title">
              Year of Graduation
            </div>            
            <div class="profile-description">
              <?php echo esc_attr( get_the_author_meta( 'year_graduation', $user_profile->ID ) ); ?>
            </div>
          </div>
          <div>
            <div class="profile-title">
              Current Job
            </div>            
            <div class="profile-description">
              <?php echo esc_attr( get_the_author_meta( 'job_title', $user_profile->ID ) ); ?>
            </div>
          </div>
          <div>
            <div class="profile-title">
              Current Company
            </div>            
            <div class="profile-description">
              <?php echo esc_attr( get_the_author_meta( 'job_company', $user_profile->ID ) ); ?>
            </div>
          </div>
          <div>
            <div class="profile-title">
              Main Skills
            </div>            
            <div class="profile-description">
              <?php foreach ($skills as $skill) {
              echo $skill . "<br/>";
              } ?>
            </div>
          </div>
          <div>
            <div class="profile-title">
              Targed Industries
            </div>            
            <div class="profile-description">
              <?php foreach ($target_industries as $industry) {
              echo $industry . "<br/>";
              } ?>
            </div>
          </div>
          <?php if (!empty($resume['url'])) { ?>
          <div>
            <div class="profile-title">
              My Resume
            </div>            
            <div class="profile-description">
              <a class="button medium blue" href="<?php echo $resume['url']; ?>" target="_blank" ><i class="fa fa-download"></i>&nbsp;Download</a>
            </div>
          </div>
          <?php } ?>
        </div>
      </article>
    </div> <!-- #home_content -->
  </div><!-- #content -->
</div><!-- #primary -->

<?php
      get_footer();
    } else { // el usuario no es un job seeker
      redirec_to_home();
    }
  } else { // el usuario no existe
    redirec_to_home();  
  }
} else {
  redirec_to_home();
}
?>