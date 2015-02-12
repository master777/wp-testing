/**
 * Inicializa funciones javascript diversas
 */
var current_profile_review = "";
jQuery(document).ready( function() {
	current_profile_review = jQuery("#profile-pic-preview").attr('src');

	// Inicializamos la funcion de autocompletar para ciertos campos del perfil.
	var autofill_fields = [ 'main-skills', 'target-industries' ];
	jQuery.each( autofill_fields, function( index, value ) {
		jQuery('#' + value).tokenize({
	  		maxElements: 5,
	  		newElements: false
	  });
	});
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