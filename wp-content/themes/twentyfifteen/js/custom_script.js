/**
 * Inicializa la funciona de autocompletar para los campos del perfil.
 */
jQuery(document).ready(function() {
	var autofill_fields = [ 'main-skills', 'target-industries' ];
	// Activamos el "autofill" (autocompletado) para los campos de la lista
	jQuery.each( autofill_fields, function( index, value ){
		jQuery('#' + value).tokenize({
	  		maxElements: 5,
	  		newElements: false
	  });		
	});
});