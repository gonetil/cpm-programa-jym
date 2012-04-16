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
            	this.checked="checked";
        }
        return true;
    });

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
});
	
