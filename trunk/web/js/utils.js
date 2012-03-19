
hacer_listados_selectables = function (){
	$('.records_list tbody').selectable({ filter: "tr", cancel: "a" });
}
	
/**
 * Redirecciona a la url definida por gotoUrl y solicita 
 * confirmacion al usuario con el texto de confirmationMessage
 * @param confirmationMessage
 * @param gotoUrl
 */
confirmAndRelocate = function(confirmationMessage, gotoUrl) {
	if (!confirmationMessage || confirm(confirmationMessage))
		location.href = gotoUrl;
}



/**
 *  inicializaciones comunes a toda la aplicacion
 */
$(document).ready(function() {
	
	/**
	 * Elimina la clase "current" de los items del menu que no tienen hover
	 */
	$("ul.select").mousemove(function(event) { 
		$("ul.select").each(function(index,elem) { $(elem).removeClass('current'); });
		$(event.target).addClass('current');	
	});
	
	
}); //document.ready

