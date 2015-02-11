/**
 * Inicializa funciones javascript diversas
 */
jQuery(document).ready(function() {
	// Inicializamos la funcion de autocompletar para ciertos campos del perfil.
	var autofill_fields = [ 'main-skills', 'target-industries' ];
	jQuery.each( autofill_fields, function( index, value ){
		jQuery('#' + value).tokenize({
	  		maxElements: 5,
	  		newElements: false
	  });		
	});
});

// Para la subida de imagenes
function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			jQuery('#profile-pic-preview').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}