
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
		$(":checkbox[name='"+target+"']").each(function(index,elem) {
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
		posX = event.pageX + $(event.target).css('width')
		$(this).children(".sub_actions").show();
	}).mouseleave(function(event) { 
		$(this).children(".sub_actions").hide();
	});
	
	
	$(".batch_actions .sub_actions a").click(function(event){
		event.preventDefault();
		$sub_action = $(event.target);
		
		action = $sub_action.attr('href');
		
		if ($sub_action.hasClass("all_elements")) { 
			target_form = $sub_action.attr('target');
			console.log(target_form);
			$(target_form).attr('action',action).submit();
		} else {
			target_items = $sub_action.attr('target');
			target_form = "";
			
			$("body").append("<form id='dynamic_form' method='post' action='"+action+"' />");

			$form = $("#dynamic_form")
			$("input:checkbox[name='"+target_items+"']").each(function(index,elem){
				$form.append(elem);
			});
			$form.submit();
		}
	});
	
	
	
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
	
	add_checkall_ability();
	add_batch_actions_support();
	
}); //document.ready

