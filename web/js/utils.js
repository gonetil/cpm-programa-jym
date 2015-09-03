instancia_reinvitar = function() { 
	$("#reinvitar_form").dialog({
								 resizable: false, width:450, height:200, modal: true,
								 buttons : { 
									 		 "Re-enviar invitaciones" : function() { $("#reinvitar_form form").submit(); } ,
									 		 "Cancelar" : function() { $(this).dialog('close'); }
									 	}
											
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

/**
 * habilita/deshabilita los select de regiones, distritos y localidades segun la cantidad de opciones seleccionadas en cada uno
 **/

disableMultipleSelects = function() {
    $regiones = jQuery('select.region-selector');
    $distritos = jQuery('select.distrito-selector');
    $localidades = jQuery('select.localidad-selector');

    if(($regiones.find(':selected').length>1)) { //si selecciona multiples regiones, no puede seleccionar ni distritos ni localidades
        $distritos.prop('disabled','disabled');
        $localidades.prop('disabled','disabled');
    } else if ($distritos.find(':selected').length>1) { //si selecciono multiples distritos, solo se bloquean las localidades
        $localidades.prop('disabled','disabled');
        $distritos.prop('disabled',false);
    } else //no hay multiple seleccion ni de regiones ni de distritos, queda todo habilitado
    {
        $distritos.prop('disabled',false);
        $localidades.prop('disabled',false);
    }
}

buscar_localidades = function() {
    disableMultipleSelects();
    var id = -1;
    select = "select.localidad-selector";

    if (jQuery('select.distrito-selector :selected').length > 1) {
        return;
    }
    else if (jQuery('select.distrito-selector :selected').length == 1) {
        id = jQuery("select.distrito-selector :selected")[0].value;
    }

	autofill_select( { distrito_id : id} ,  select , "/public/find_by_distrito" , "Todas");
};

buscar_distritos = function() {
        disableMultipleSelects();
        var id = -1;
        select = "select.distrito-selector";

        if (jQuery('select.region-selector :selected').length > 1) {
                return;
        }
        else if (jQuery("select.region-selector :selected").length == 1)
            id = jQuery("select.region-selector :selected")[0].value;


		autofill_select( { region_id : id} , select  , "/public/find_by_region" , "Todos");
};

buscar_instancias_eventos = function() {
	id = jQuery("#cpm_jovenesbundle_filter_modelFilter_eventoFilter_evento").val();
	select = "#cpm_jovenesbundle_filter_modelFilter_instanciaEventoFilter_instanciaEvento";
	if (id == "")
		id =-1;
	autofill_select( { evento_id : id} , select  , "/public/find_instancias_by_evento" , "Todos");
	
};

bind_rdl_selects = function(){
    disableMultipleSelects();
	$('select.region-selector').change(buscar_distritos);
	$('select.distrito-selector').change(buscar_localidades);
	$("#cpm_jovenesbundle_filter_modelFilter_eventoFilter_evento").change(buscar_instancias_eventos);
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
	
	invitaciones_actionx_fx();
	
    $('#search_online').keyup(function() {
    			text = $.trim($(this).val());
    			if (text != '')
    				$(this).css('background','yellow');
    			else
    				$(this).css('background','none');
		
    			searchTable(text);
    });
    
    filtrarPorCiclo(); //fitra proyectos a partir del ciclo. Usado en user/show
    
    $(".draggable").draggable();
    
    $(".lista_variable").dblclick(function(){
    	destination = $(this).attr('target');
    	value = $(this).val();
    	insertarVariableEnCorreo(value,destination)
    });
    

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
		if (val == 10) { //presentado... FIXME: usar la constante de PHP
			$("#archivo_presentacion").show();
			$("#valoracion").hide();
		}
		else {
			$("#archivo_presentacion").hide();
			$("#valoracion").show();
		}
		$("#archivo_presentacion .error").remove();
		
		
		var sin_email = ['1','22','23']; //estados por los que no se debe mandar email automáticamente		
		if ( sin_email.indexOf(val) != -1) { 
			$("#cpm_jovenesbundle_estadoproyectotype_enviar_email").prop('checked', false);

		} else
			$("#cpm_jovenesbundle_estadoproyectotype_enviar_email").prop('checked', true);
		
		return false;
	}).change();
}

validar_form_cambio_estado = function() {
	var archivo = $("#cpm_jovenesbundle_estadoproyectotype_archivo");
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
		 resizable: false, width:650, height:400, modal: true,
		 buttons : { 
			 		 "Aceptar" : function() { $("#nuevo_estado_proyecto_form").submit(); } ,
			 		 "Cancelar" : function() { $(this).dialog('close'); }
			 	}
					
		});
	
}

invitaciones_actionx_fx = function() {
	
	$(".with-operations").click(function(event){
		$(event.target).children(".ops").toggle();
	});
	
	$(".invitaciones li.actions").click(function(event) { 
		$(event.target).find(".with-operations").click();
	});
}


cambiarInvitacionDeInstancia = function(select) {
	$select = $(select);
	nueva_instancia = $select.val();
	
	if (confirm("¿confirmar el cambio de instancia?")) { 
		$.get(nueva_instancia,function(msg) {
			if (msg == 'success') {
				window.location.reload();
			}
		});
	}	
}

mostrarSelectCambioInstancia = function(sibling) {
	$(sibling).siblings(".invitacion_instancia_switcher").show();
	$(sibling).hide();
	return false;
}


function searchTable(inputVal) {
	var table = $('#invitaciones');
	table.find('tr').each(function(index, row)
	{
		var allCells = $(row).find('td');
		if(allCells.length > 0)
		{
		  var found = false;
		  allCells.each(function(index, td) {
		                var regExp = new RegExp(inputVal, 'i');
		                if(regExp.test($(td).text()))
		                {
		                    found = true;
		                    return false;
		                }
		            });
		            if(found) $(row).show();
		            else $(row).hide();
		        }
		    });
}

function filtrarPorCiclo() {
	
	$("#ciclo_selector li").click(function(event){

		$("#ciclo_selector li.selected").removeClass("selected");
		$("#proyectos_usuario li").addClass("hidden");
		$li = $(event.target);
		ciclo = $li.attr('target');

		$("#proyectos_usuario li."+ciclo).removeClass("hidden");
		$li.addClass("selected");
	});	
}


function mostrarFormComentario(url,asunto,cuerpo,css_class) { 
	$("#asunto_label").text(asunto);
	$("#cuerpo_label").text(cuerpo);
	$("#comentario_form form").attr('action',url);
	$("#comentario_form").removeClass().addClass(css_class).show();
	$("#tipo_comentario").val(css_class);
}

function agregarPostit(post_url) {
	mostrarFormComentario(post_url,"Asunto","Mensaje","postit");
}

function agregarTarea(post_url) {
	mostrarFormComentario(post_url,"Tarea","Detalles","tarea");
}

function agregarComentario(post_url) {
	mostrarFormComentario(post_url,"Titulo","Comentario","comentario");
}



function enviarComentarioAjax() { 
	asunto = $("#comentario_form form #asunto").val();
	cuerpo = $("#comentario_form form #cuerpo").val();
	url = $("#comentario_form form").attr('action');
	$.ajax({
		  type: "POST",
		  url: url,
		  data: { asunto:asunto, cuerpo:cuerpo} ,
		  success: function(message){
			  if (message == 'success') { 
				  console.log($("#tipo_comentario").val());
				  if ($("#tipo_comentario").val() == 'postit') { 
					  mostrarPostitOnline(asunto, cuerpo)
				  } else if ($("#tipo_comentario").val() == 'comentario') {
					  mostrarComentarioOnline(asunto, cuerpo);
				  }
				  
				  $("#comentario_form form #asunto").val("");
				  $("#comentario_form form #cuerpo").val("");
				  $("#comentario_form").slideUp();
			  }
		  }
		});
}

function mostrarPostitOnline(asunto,cuerpo) { 
	  new_div = "<div class='postit draggable'>" +
		"			<div class='postit_content'>" +
						"<div class='postit_metadata'>Creado recientemente</div>" +
						"<div class='asunto'>" + asunto + "</div>" +
						"<div class='cuerpo'>" + cuerpo + "</div>" +
					"</div>" +
			"</div>";
	  $("body").append(new_div);
	  $(".draggable").draggable();
	
}

function mostrarComentarioOnline(asunto, cuerpo) {
	
	new_div = '<div class="comentario">' +
				  '<br/> Título <strong>'+asunto+'</strong>' +
				  '<br/> '+cuerpo+
			  '</div>';

	$(".info_comentarios").append(new_div);
	
}

function eliminarComentarioAjax(url,elem_id) {
	if (confirm('¿esta seguro que desea eliminar este mensaje?'))
		$.ajax({
			  type: "POST",
			  url: url,
			  success: function(message){
				  if (message == 'success') { 
					  $("#"+elem_id).remove();
				  }
			  }
			});
	
}

function marcarLeidoComentarioAjax(url,elem_id) {
		$.ajax({
			  type: "POST",
			  url: url,
			  success: function(message){
				  if (message == 'success') { 
					  $elem = $("#"+elem_id);
					  if ($elem.hasClass('leido'))
						  $elem.removeClass('leido');
					  else 
						  $elem.addClass('leido');
				  }
			  }
			});
	
}



function insertarVariableEnCorreo(value,destination) { 
	$(destination).insertAtCaret(value);
}

/**
 * Esta funcion inserta un valor dentro de un textarea en el lugar donde esta el cursor
 * */
$.fn.extend({ 
	  insertAtCaret: function(myValue){
	  var obj;
	  if( typeof this[0].name !='undefined' ) obj = this[0];
	  else obj = this;

	  if ($.browser.msie) {
	    obj.focus();
	    sel = document.selection.createRange();
	    sel.text = myValue;
	    obj.focus();
	    }
	  else if ($.browser.mozilla || $.browser.webkit) {
	    var startPos = obj.selectionStart;
	    var endPos = obj.selectionEnd;
	    var scrollTop = obj.scrollTop;
	    obj.value = obj.value.substring(0, startPos)+myValue+obj.value.substring(endPos,obj.value.length);
	    obj.focus();
	    obj.selectionStart = startPos + myValue.length;
	    obj.selectionEnd = startPos + myValue.length;
	    obj.scrollTop = scrollTop;
	  } else {
	    obj.value += myValue;
	    obj.focus();
	   }
	 }
});



/**
 * 
 * Construye un JSON con todos los input marcados dentro del elemento #widgetAnios
 * @param inputAnios id de objeto HTML donde se guardará el valor en formato json
 */
function updateAnios(input) { 
	
	inputAnios = $(input);
	result = {};

	$("#widgetAnios input:checked").each(function(index,value) {
		result[$(value).val()]= $(value).val();
	});

	inputAnios.val(JSON.stringify(result));
}

/**
 * Construye un listado de checkboxes con los años seleccionados por el usuario y los coloca dentro del elemento #widgetAnios
 * @param inputAnios objeto jquery que contiene un JSON con los años seleccionados
 */
function cargarAnios(inputAnios) { 
	anios = $.parseJSON(inputAnios.val());
	elem_id = "#"+inputAnios.attr('id');
	current_year = new Date().getFullYear(); 
	
	if (anios == null) anios = new Object();
	for(i=2002;i<current_year;i++) { 
		newInput = '<input type="checkbox" onclick=updateAnios("'+elem_id+'") ' + ( ( anios[i] ) ? 'checked' : ' ' ) + ' value="'+i+'" >' + i;
		$("#widgetAnios").append(newInput); 
	}
	
	if ( $("#widgetAnios input:checked").length > 0 ) 
	{ 
		$("#widgetAnios").show();
		$("#participo").prop('checked',true);
	}
	else {  
		$("#widgetAnios").hide();
		$("#no_participo").prop('checked',true);
	}	
}

/**
 * Controla que el usuario marque al menos un año si indicó que sí participó
 * 
 * 
 */
function chequearAnios(input) {
	if  ( $("#participo").is(":checked"))  { 
		if ($("#widgetAnios input:checked").length == 0) { //dijo q participo pero no marco ningun anio
			alert("Seleccione al menos un año en que participó del programa");
			return false;
		}
	} else { 
		$("#widgetAnios input:checked").prop("checked", false);
		updateAnios(input);
	}
	
	return true;
}

function fetchCorreo(path,id,callback) {
	retorno = null;
	$.getJSON(
			path, 
			{ correo_id : id} , 
			  function(data) { retorno = callback(data,id); }
		); //getJSON
	return retorno;
}

function buscar_correo(id, path) {
				$loading = $("#correo_"+id+" .loading");
				$loading.show();
				correo =  fetchCorreo(path,id,mostrarCorreo);
				$loading.hide();
				return correo; 				
} //buscar_correo

function mostrarCorreo (data,id) {
		if (data) {
			$cuerpo = $("#correo_"+id + " .usuario_correo_cuerpo");
			link = "<div class='correo_link'><a target='_blank' href='"+data.path+"'>Abrir</a></div>";
			$cuerpo.html(data.cuerpo + link).slideDown();
//	   		$("#correo_"+id).unbind('click').click(function(event) { $cuerpo.toggle(); });
		}
} //mostrarCorreo




//pide a la url $url todos los eventos y las instancias del ciclo $ciclo, y los carga en los selects $target_*
function filtrarEventosPorCiclo(url, ciclo,$target_eventos,$target_instancias) {
	$.getJSON(
			url, 
			{ ciclo_id : ciclo} , 
			  function(data) {
				  if ($target_eventos) { 
					  $target_eventos.find("option").remove();
					  $target_eventos.append("<option value=''>Todos</option>");
					  for (var i=0; i<data.eventos.length; i++) { 
					   			$target_eventos.append("<option value="+data.eventos[i].id+">"+data.eventos[i].nombre+"</option>");
					   }
				  }
				  
				  if ($target_instancias) {
					  $target_instancias.find("option").remove();
					  $target_instancias.append("<option value=''>Todas</option>");
					  for (var i=0; i<data.instancias.length; i++) { 
				   			$target_instancias.append("<option value="+data.instancias[i].id+">"+data.instancias[i].nombre+"</option>");
					  }
				  }
				  

			  }
		); //getJSON
}
