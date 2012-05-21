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
	javascript:
	
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
init_datepicker = function(dpSelector){
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
	$(dpSelector).datepicker();

}

function clear_form_elements(form) {
    $(form).find(':input').each(function() {
        switch(this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
            	this.value="";
                break;
            case 'checkbox':
            case 'radio':
            	this.checked="";
        }
        return true;
    });

}

autofill_select = function ( value_to_send , select_to_fill , url_to_json, empty_value)
{
   	select = $(select_to_fill)
   			.first()
   			.empty()
   			.append("<option value=''>" + empty_value + "</option>");

	if (value_to_send != null )
	 {
	   	$.getJSON(BASE_PATH + url_to_json, value_to_send , function(data) {
	   		if (data.length > 0) {
		   		for (var i=0; i<data.length; i++) 
		   			select.append("<option value="+data[i].id+">"+data[i].nombre+"</option>");
		   	}
		}); 
	}
};

buscar_localidades = function() {
	id = jQuery("select.distrito-selector").val();
			
	select = "select.localidad-selector";
	if (id == "") 
		id = "-1";
	autofill_select( { distrito_id : id} ,  select , "/public/find_by_distrito" , "Todas");			
};

buscar_distritos = function() {
		id = jQuery("select.region-selector").val();
			
		select = "select.distrito-selector";
		if (id == "")
			id =-1;
		autofill_select( { region_id : id} , select  , "/public/find_by_region" , "Todos");
};

bind_rdl_selects = function(){
	$('select.region-selector').change(buscar_distritos);
	$('select.distrito-selector').change(buscar_localidades);
}
/**
 *  inicializaciones comunes a toda la aplicacion
 */
jQuery(function($){
	init_datepicker('.datepicker');
	
	//Elimina la clase "current" de los items del menu que no tienen hover
	var primer = $("ul.select").first();
	$("ul.select").hover(function(event) { 
			primer.removeClass('current');
			$(event.currentTarget).addClass('current');	
		},function(event) { 
			$(event.currentTarget).removeClass('current');
			primer.addClass('current');
		}
	);
	
	
	$(".icon").tooltip();
	
	bind_rdl_selects();
});
	
invitacion_reinvitar = function(clicked_node,$destination) {
	target = $(clicked_node).attr('target');
	$.get(target, function( msg) {
			if (msg == 'success') {
				if ($destination)
					$destination.removeClass().addClass("icon yes");
				else
					$(clicked_node).removeClass().addClass("icon yes");
			}
	});
}

mostrar_campo_archivo = function() { 
	$("#cpm_jovenesbundle_estadoproyectotype_estado").change(function() {
		val = $("#cpm_jovenesbundle_estadoproyectotype_estado").val();
		if (val == 10) //presentado... FIXME: usar la constante de PHP
			$("#archivo_presentacion").show();
		else
			$("#archivo_presentacion").hide();
		$("#archivo_presentacion .error").remove();
	});
}

validar_form_cambio_estado = function() {
	archivo = $("#cpm_jovenesbundle_estadoproyectotype_archivo");
	if  ( (archivo.is(":visible")) && (archivo.val() == "") ) {
		parent = $("#archivo_presentacion"); 
		parent.children(".error").remove();
		parent.append("<p class='error'>Debe seleccionar un archivo para subir</p>");
		return false;
	}
	return true;
		
}

cambiar_estado = function() { 
	$("#estado_proyecto_form").dialog({
		 title: "Nuevo estado del proyecto",
		 resizable: false, width:650, height:350, modal: true,
		 buttons : { 
			 		 "Aceptar" : function() { $("#nuevo_estado_proyecto_form").submit(); } ,
			 		 "Cancelar" : function() { $(this).dialog('close'); }
			 	}
					
		});
	
}