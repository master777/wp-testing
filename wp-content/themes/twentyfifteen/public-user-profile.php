<?php
/**
 * Template Name: User Profile (Public)
 *
 */

global $wp_query;
global $wpdb;

/*					
echo "query_vars:";
echo "<pre>";
print_r($wp_query->query_vars);
echo "</pre>";
*/

function redirec_to_home() {
	wp_redirect( home_url() );
	exit();
}

if (!empty($wp_query->query_vars['user'])) {
	$user_nicename = $wp_query->query_vars['user'];
	$user_profile = get_user_by("slug", $user_nicename);
	
	if (!empty($user_profile)) {
		/*
		echo "Datos del Usuario Actual:";
		echo "<br/>";
		echo "<pre>";
		print_r($user_profile);
		echo "</pre>";
		*/
		
		function set_title($title, $sep) {
			global $user_profile;
			$title = $user_profile->first_name . " " . $user_profile->last_name . " - Findspark";
						
			return $title;
		}
		add_filter( 'wp_title', 'set_title', 10, 2 );
		

		$email = esc_attr( get_the_author_meta( 'email', $user_profile->ID ) );
		$website = esc_attr( get_the_author_meta( 'url', $user_profile->ID ) );
		$facebook = esc_attr( get_the_author_meta( 'facebook', $user_profile->ID ) );
		$twitter = esc_attr( get_the_author_meta( 'twitter', $user_profile->ID ) );
		$google_plus = esc_attr( get_the_author_meta( 'googleplus', $user_profile->ID ) );
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<style>
	.test {
		background: yellow;
	}
	.header-profile {
		min-height: 200px;
		height: auto;
	}
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
	.profile-title {
		border-bottom: 2px solid #D9D9D9;
	}
	.icon-medall {
    background: url(images/medal_bronze.png) no-repeat;
    float: left;
    width: 32px;
    height: 32px;
	}
</style>
<div id="primary">
	<div id="content" role="main">
		<?php get_sidebar(); ?>
		<div class="home_content">
			<article>
				<div class="row">
	      	<div class="col-sm-4 header-profile">
      			<img title="profile image" width="200" height="200" class="img-circle img-responsive" src="<?php echo $profile_pic_url; ?>">
	        </div>
	        <div class="col-sm-8 header-profile">
	          <h1 class=""><?php echo $user_profile->first_name . " " . $user_profile->last_name; ?>&nbsp;<img src="<?php echo get_template_directory_uri(); ?>/images/medal_bronze.png" width="32" height="32" title="Premium Member" /></h1>
	         	<a href="<?php echo $website; ?>" target="_blank" class="btn btn-success"><span class="glyphicon glyphicon-globe"></span>&nbsp;My website</a>
	          <a href="mailto:<?php echo $email; ?>" class="btn btn-info"><span class="glyphicon glyphicon-envelope"></span>&nbsp;Contact me</a>	          
						<br/>
	        </div>
    		</div>
			</article>
			<article>
				<div>
					<div>
						<div>
							<strong>Social Media</strong>	
						</div>						
						<div>
							<p>
		         		<a href="<?php echo $facebook; ?>" target="_blank">
									<img alt="" width="40" height="40" class="avatar-bg" src="<?php echo get_template_directory_uri(); ?>/images/square-facebook-128.png" />
								</a>
								<a href="<?php echo $twitter; ?>" target="_blank">
									<img alt="" width="40" height="40" class="avatar-bg" src="<?php echo get_template_directory_uri(); ?>/images/square-twitter-128.png" />
								</a>
								<a href="<?php echo $google_plus; ?>" target="_blank">
									<img alt="" width="40" height="40" class="avatar-bg" src="<?php echo get_template_directory_uri(); ?>/images/square-google-plus-128.png" />
								</a>
								<!--a href="<?php echo $yahoo; ?>" target="_blank">
									<img alt="" width="40" height="40" class="avatar-bg" src="<?php echo get_template_directory_uri(); ?>/images/square-yahoo-128.png" />
								</a-->
		         	</p>
						</div>
					</div>
					<div>
						<div>
							<strong>About me</strong>	
						</div>						
						<div>
							<p>
		         		<?php echo esc_attr( get_the_author_meta( 'description', $user_profile->ID ) ); ?>
		         	</p>
						</div>
					</div>
					<div>
						<div>
							<strong>College Attended</strong>	
						</div>						
						<div>
							<p>
								<?php foreach ($college_attended as $college) {
								echo $college . "<br/>";
								} ?>
							</p>
						</div>
					</div>
					<div>
						<div>
							<strong>Year of Graduation</strong>	
						</div>						
						<div>
							<p>
								<?php echo esc_attr( get_the_author_meta( 'year_graduation', $user_profile->ID ) ); ?>
							</p>
						</div>
					</div>
					<div>
						<div>
							<strong>Current Job</strong>	
						</div>						
						<div>
							<p>
								<?php echo esc_attr( get_the_author_meta( 'job_title', $user_profile->ID ) ); ?>
							</p>
						</div>
					</div>
					<div>
						<div>
							<strong>Current Company</strong>	
						</div>						
						<div>
							<p>
								<?php echo esc_attr( get_the_author_meta( 'job_company', $user_profile->ID ) ); ?>
							</p>
						</div>
					</div>
					<div>
						<div>
							<strong>Main Skills</strong>	
						</div>						
						<div>
							<p>
								<?php echo esc_attr( get_the_author_meta( 'main_skills', $user_profile->ID ) ); ?>							
							</p>
						</div>
					</div>
					<div>
						<div>
							<strong>Targed Industries</strong>	
						</div>						
						<div>
							<p>
								<?php foreach ($target_industries as $industry) {
								echo $industry . "<br/>";
								} ?>
							</p>
						</div>
					</div>
					<?php if (!empty($resume['url'])) { ?>
					<div>
						<div>
							<strong>My Resume</strong>	
						</div>						
						<div>
							<p>
								<a href="<?php echo $resume['url']; ?>" target="_blank" >Download</a>
							</p>
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
	} else {
		//echo "<strong>El usuario no existe!!!</strong>";
		redirec_to_home();	
	}
} else {
	redirec_to_home();
}
?>