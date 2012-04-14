instancia_reinvitar = function() { 
	$("#reinvitar_form").dialog({
								 resizable: false, width:450, height:200, modal: true,
								 buttons : { 
									 		 "Re-enviar invitaciones" : function() { $("#reinvitar_form form").submit(); } ,
									 		 "Cancelar" : function() { $(this).dialog('close'); }
									 	}
											
								});
	}

aviso_coordinador_inscripcion_realizada = function(path) {
	
  $(document).ready(function() {
	$('body').append('<div id="dialog-confirm" title="Escuela ya inscripta" style="display:none"> \
					   <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>\
						Usted ya inscribió a una escuela <strong> ¿está seguro que quiere inscribir otra?</strong>\
						</p>'
	 );
	
	
	$("a[href='"+path+"']").click(function(event) { 
		
		event.preventDefault();  
		href = $(this).attr('href');
		$( "#dialog-confirm" ).dialog({ resizable: false, width:650, height:200, modal: true,
					buttons: {
						"Sí, quiero inscribir otra escuela": function() {
								$( this ).dialog( "close" );
							   window.location = href;
						},
						"No, gracias": function() {
							$( this ).dialog( "close" );
						}
					}
				});
	});
  });
	
}





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


/*funcion para pasar asistencia a las instancias de eventos*/
pasar_asistencia = function() { 
	
    $(".col-asistencia input:checkbox").click(function(target) {
		$checkbox = $(this);
		$checkbox.parent().removeClass('yes no');
		$checkbox.siblings(".inline-loading").show();
		url = $checkbox.val();
		asistio = $checkbox.is(":checked");
		$.get(url,{asistencia:asistio}, function( msg) {
					  			if (msg == 'success') {
					  				$checkbox.siblings(".inline-loading").hide();
					  				var td = $checkbox.parent();
					  				if (asistio)
					  					td.addClass("yes");
					  				else 
					  					td.addClass("no");
					  			}
				  		}
		);
	});
	
	$(".col-asistencia").each(function(index,elem){
		$elem = $(elem);
		$elem.mouseenter(function() { $(this).children('input').show(); }).
			  mouseleave(function() { $(this).children('input').hide(); });

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
	
	$(".icon").tooltip();
}); //document.ready


function definirIntervalo(inicioID, finID){
	var d = $("#"+inicioID+", #" +finID).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 3,
		onSelect: function( selectedDate ) {
			var option = this.id == inicioID ? "minDate" : "maxDate",
				instance = $( this ).data( "datepicker" ),
				date = $.datepicker.parseDate(
					instance.settings.dateFormat ||
					$.datepicker._defaults.dateFormat,
					selectedDate, instance.settings );
			d.not( this ).datepicker( "option", option, date );
		}
	});
}
$(function() {
	jQuery(function($){
		$.datepicker.regional['es'] = {
			closeText: 'Cerrar',
			prevText: '&#x3c;Ant',
			nextText: 'Sig&#x3e;',
			currentText: 'Hoy',
			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
			'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
			'Jul','Ago','Sep','Oct','Nov','Dic'],
			dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
			dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
			dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
			weekHeader: 'Sm',
			dateFormat: 'dd/mm/yy',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['es']);
	});
	
	$('.datepicker').datepicker();
});
