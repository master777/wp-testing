<?php
/**
 * Template Name: User Profile (Public)
 *
 */

get_header();  

global $wp_query;
global $wpdb;


echo "Test of profile/username";
echo "<br/>";

/*					
echo "query_vars:";
echo "<pre>";
print_r($wp_query->query_vars);
echo "</pre>";
*/

if (!empty($wp_query->query_vars['user'])) {
	$user_nicename = $wp_query->query_vars['user'];
	$user = get_user_by("slug", $user_nicename);
	//$user = get_user_by("login", "levi2");

	if (empty($user)) {
		echo "<strong>El usuario no existe!!!</strong>";		
	} else {
		echo "Datos del Usuario Actual:";
		echo "<br/>";
		echo "<pre>";
		print_r($user);
		echo "</pre>";

		//echo "<br/>";
		//echo 'User is <strong>' . $user->first_name . ' ' . $user->last_name . '</strong>';
		//echo "<br/>";
		//echo '<div><img alt="" class="avatar-bg" src="' . get_bloginfo('template_directory') . '/images/profile-default.png" /><br/><strong>First Name: </strong>' . $user->first_name . '<br/><strong>Last Name: </strong>' . $user->last_name. '</div>';

		?>

		<div>
			<img alt="" class="avatar-bg" src="<?php echo get_bloginfo('template_directory');?>/images/profile-default.png" /><br/>
			<strong>First Name: </strong><?php echo $user->first_name; ?><br/>
			<strong>Last Name: </strong><?php echo $user->last_name; ?><br/>
		</div>


		<?php 
			$form_id = 51; // ID del formulario de registro donde los job seekers ingresan toda su informacion
			$field_number = 18; // ID del campo 'username' en el formulario de registro

			$username = $user->user_login;

			echo "Username: $username <br/>";

			$table_name = $wpdb->prefix . "rg_lead_detail";

			$sql = "SELECT rg_ld.* 
				FROM $table_name rg_ld
				WHERE rg_ld.field_number = $field_number
				AND rg_ld.value = '$username'
				LIMIT 1";
			// La condicion "AND rg_ld.form_id = $form_id" no fue incluida
			$values = $wpdb->get_row($sql, ARRAY_A);

			if ($values) {
				echo "<pre>";
				print_r($values);
				echo "</pre>";

				// Obtenemos la informacion
				$lead_id = $values['lead_id'];
				$sql = "SELECT * FROM $table_name rg_ld
					WHERE rg_ld.lead_id = $lead_id";
				$form_content = $wpdb->get_results($sql, ARRAY_A);

				if ($form_content) {
					//echo "<pre>";
					//print_r($form_content);
					//echo "</pre>";
				} else {
					echo "No se encontraron resultados (ALGO RARO)...";
				}

			} else {
				echo "No hay elementos encontrados...";
			}

			//$values = GFFormsModel::get_leads($form_id); // Por defecto solo muestra 30 tuplas
			//$form = GFAPI::get_form( $form_id);
			/*
			$lead_id = 0;
	    $lead = RGFormsModel::get_lead( $lead_id ); 
	    $form = GFFormsModel::get_form_meta( $lead['form_id'] ); 

	    $values= array();

	    foreach( $form['fields'] as $field ) {

	        $values[$field['id']] = array(
	            'id'    => $field['id'],
	            'label' => $field['label'],
	            'value' => $lead[ $field['id'] ],
	        );
	    }
	    */
		?>

		<?php
	}
} else {
	wp_redirect( home_url() );
	exit();
}

get_footer();
?>