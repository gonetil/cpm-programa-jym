
hacer_listados_selectables = function (){
//	$('.records_list tbody').selectable({ filter: "tr", cancel: "a" });
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
 * agrega la capacidad de seleccionar todos los checkboxes
 * cuyo nombre es igual al atributo target del checkbox id='select_all'
 */
add_checkall_ability = function() {
	$("#select_all").change(function(elem){
		target = $(this).attr('target');
		$self = $(this);
		$(target).each(function(index,elem) {
											$(elem).attr("checked",$self.is(":checked")); 
											}); //.each
		
	} //.change
	).trigger('change');									
}

/**
 * agrega el soporte a las batch actions
 * 
 */
add_batch_actions_support = function() {
	
	$(".batch_actions li").mouseenter(function(event){
		$(this).children(".sub_actions").show();
	}).mouseleave(function(event) { 
		$(this).children(".sub_actions").hide();
	});
	
	
	$(".batch_actions .sub_actions a").click(function(event){
		event.preventDefault();
		$clicked= $(event.target);		
		action = $clicked.attr('href'); //el action que procesara el formulario
		type = $clicked.attr('type'); //se deben buscar todos los proyectos o solo los seleccionados
		batch_action = $clicked.attr('batch_action'); //el action a forwardear
		
		$(".batch_action_hidden").val(batch_action);
		$(".batch_action_type_hidden").val(type);
		
		target_form = $clicked.attr('target');
		$(target_form).attr('action',action).submit();
	});
	
	
	
}


/**
 *  inicializaciones comunes a toda la aplicacion
 */
$(document).ready(function() {
	
	/**
	 * Elimina la clase "current" de los items del menu que no tienen hover
	*/
	var primer = $("ul.select").first();
	$("ul.select").hover(function(event) { 
			primer.removeClass('current');
			$(event.currentTarget).addClass('current');	
		},function(event) { 
			$(event.currentTarget).removeClass('current');
			primer.addClass('current');
		}
	);
	
	add_checkall_ability();
	add_batch_actions_support();
	
}); //document.ready

