/**
 * Inicializa funciones javascript diversas
 */
var current_profile_review = "";
jQuery(document).ready( function() {
	// Cambiamos el texto de algunos campos
	jQuery("label[for='description']").text("Tell Us About Yourself");

	var user_role = jQuery("#current-user-role").val();
	// Verificamos si el usuario actual es un job seeker
	if (jQuery.inArray(user_role, ["subscriber", "premiummember"]) != -1) {
		current_profile_review = jQuery("#profile-pic-preview").attr('src');

		// Ocultamos algunos campos 
		jQuery("#wordpress-seo").next("table").hide();
		jQuery("#wordpress-seo").hide();
		jQuery("h3:contains('About the user')").hide();
		jQuery("h3:contains('Contact Info')").hide();
		
		// Cambiamos de posicion los siguientes campos:
		// New Password
		var current_row = jQuery('#pass2').closest("tr");
		var target_row = jQuery('#display_name').closest("tr");
		current_row.insertAfter(target_row);

		// Repeat New Password
		current_row = jQuery('#pass1').closest("tr");
		target_row = jQuery('#display_name').closest("tr");
		current_row.insertAfter(target_row);

		// Email
		current_row = jQuery('#email').closest("tr");
		target_row = jQuery('#year-graduation').closest("tr");
		current_row.insertAfter(target_row);

		// Twitter
		current_row = jQuery('#twitter').closest("tr");
		target_row = jQuery('#year-graduation').closest("tr");
		current_row.insertAfter(target_row);

		// Linkedin
		current_row = jQuery('#linkedin').closest("tr");
		target_row = jQuery('#year-graduation').closest("tr");
		current_row.insertAfter(target_row);

		// Website
		current_row = jQuery('#url').closest("tr");
		target_row = jQuery('#year-graduation').closest("tr");
		current_row.insertAfter(target_row);

		// Biographical Info (Tell Us About Yourself)
		current_row = jQuery('#description').closest("tr");
		target_row = jQuery('#year-graduation').closest("tr");
		current_row.insertAfter(target_row);

		// Inicializamos el datepicker
		var current_date = new Date();
		jQuery("#birth-date").datepicker({
			changeYear: true,
			changeMonth: true,
			yearRange: '1920:' + current_date.getFullYear(),
			dateFormat: "yy/mm/dd",
			defaultDate: new Date((current_date.getFullYear() - 30), current_date.getMonth(), current_date.getDate())
		});

		// Inicializamos la funcion de autocompletar para ciertos campos del perfil.
		var autofill_fields = {
			'college-attended' : {
				'maxElements': 1
			}, 
			'target-industries' : {
				'maxElements': 5
			},
			'ethnicity' : {
				'maxElements': 7
			},
			'main-skills' : {
				'maxElements': 5
			}
		};

		jQuery.each( autofill_fields, function( key, value ) {
			jQuery('#' + key).tokenize({
		  		maxElements: value['maxElements'],
		  		newElements: false
		  });
		});
	}
});

// Para la subida de imagenes
function readURL(input) {
	if (input.files && input.files[0]) {
		var file = input.files[0];
		var allowed_type_files = [ 'image/jpg', 'image/jpeg', 'image/png', 'image/gif' ];
		var max_img_size = 2; // MB

		// Verificamos que la extension del archivo este permitido
		if ( jQuery.inArray(file.type, allowed_type_files) == -1 ) {
			jQuery('#profile-pic').val('');
			jQuery('#profile-pic-preview').attr('src', current_profile_review);
			alert('Please only upload JPEG, PNG and GIF files!');
			return false;
		}

		// Verificamos que el tamanho del archivo no supere el maximo establecido
		if ( file.size > (max_img_size * 1024 * 1024) ) {
			jQuery('#profile-pic').val('');
			jQuery('#profile-pic-preview').attr('src', current_profile_review);
			alert('The maximum allowed size is ' + max_img_size + ' MB!');
			return false;
		}
		
		// Cargamos el preview de la imagen
		var reader = new FileReader();
		reader.onload = function (e) {
			jQuery('#profile-pic-preview').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);		
	}
}

// Para la subida del CV (Resume)
function validateFile(input) {
	if (input.files && input.files[0]) {
		var file = input.files[0];
		var allowed_type_files = [ 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ];
		var max_file_size = 10; // MB

		// Verificamos que la extension del archivo este permitido
		if ( jQuery.inArray(file.type, allowed_type_files) == -1 ) {
			jQuery('#resume').val('');
			jQuery("#resume-preview").hide();
			alert('Please only upload PDF, DOC and DOCX files!');
			return false;
		}

		// Verificamos que el tamanho del archivo no supere el maximo establecido
		if ( file.size > (max_file_size * 1024 * 1024) ) {
			jQuery('#resume').val('');
			jQuery("#resume-preview").hide();
			alert('The maximum allowed size is ' + max_file_size + ' MB!');
			return false;
		}

		jQuery("#resume-preview").show();
		jQuery("#resume-name").hide();
		jQuery("#resume-change").hide();
	}
}

function changeFile() {
	jQuery("#resume").show();
	jQuery("#resume-change").hide();

	// Activamos el trigger para llamar a validateFile()
	jQuery("#resume").trigger("click");
}