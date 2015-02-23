<?php

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