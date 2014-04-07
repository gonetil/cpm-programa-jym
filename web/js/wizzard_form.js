   /**
   * esta variable guarda el handler de validacion del formulario. La dejo global para no tener que buscarla siempre 
   */
   var form_handler ;
   function setup_validation() {
 
    var custom_messages = {};
    var custom_rules = {};
 
/*     custom_messages['cpm_jovenesbundle_proyectotype[escuela][localidad]'] = {  
       required: "Debe seleccionar una localidad (recuerde seleccionar un distrito primero)",
     };
  */   
     custom_messages['cpm_jovenesbundle_presentacionproyectotype[escuela][localidad]'] = {  
       required: "Debe seleccionar una localidad (recuerde seleccionar un distrito primero)",
     };
    
     
   	form_handler = $("form[name='wizzard_form']").validate( { messages : custom_messages });
   	$("form[name='wizzard_form']").removeAttr("novalidate");

   }
   
   
  function setup_selects() { 
  	   	setup_selects_for_form("#cpm_jovenesbundle_proyectotype");
  	    setup_selects_for_form("#cpm_jovenesbundle_presentacionproyectotype"); 
  }

  function setup_selects_for_form(formName) {
	  $(formName+"_escuela_tipoInstitucion").change(function(event) {
	   		$numero_escuela = $(formName+"esds");
	   	
	 		if( $(this).val() == "") //selecciono otra institucion
	 		{  
	 			$(formName+"_escuela_otroTipoInstitucion").removeAttr('disabled').addClass('required').attr("required","required"); 
	 			$(formName+"_escuela_tipoEscuela").attr('disabled','disabled').removeAttr("required").removeClass('required');
	 			
	 		} else {
	 			$(formName+"_escuela_otroTipoInstitucion").attr('disabled','disabled').removeClass('required').removeAttr("required"); 
	 			$(formName+"_escuela_tipoEscuela").removeAttr('disabled').addClass('required').attr("required","required");
	 		}
	   	}).change();
	   	
	   	$(formName+"_eje").change(function(event) {
	    	  previously_selected = $(formName+"_temaPrincipal").find(':selected').val();
	      	  mostrarDescripcionEje($(formName+"_eje").val(),$("#descripcion_eje"), $("#eje_row .inline-loading"), $(formName+"_temaPrincipal") , previously_selected );
	      	  //$(formName+"_temaPrincipal option[value='"+selected+"']").attr('selected','selected');
	      	  
	   	});
  }
   
   
   function move_stage(from,to,must_validate) {
   		go_on = true;
   		if ( ( (arguments.length == 3) && (arguments[2])) || (arguments.length < 3) ) {
   			$("#stage_"+from+" :input").each(function (index,elem) {
   					go_on = go_on && form_handler.element(elem);
   			} );
   		 	
   		}
   		
   		
 		if (go_on) {
 		   	prev = $("#stage_"+from);
   			next = $("#stage_"+to);
		   	if (prev) prev.hide();
		   	if (next) next.fadeIn();
   		}
   }
   
   //al presionar ENTER se genera un TAB
   function enter_to_tab(){
	  
	   $("input").not( $(":button") ).keypress(function (evt) {
		   if (evt.keyCode == 13) {
			   iname = $(this).val();
			   if (iname !== 'Submit'){
				   var fields = $(this).parents('form:eq(0),body').find('button, input, textarea, select');
				   var index = fields.index( this );
				   if ( index > -1 && ( index + 1 ) < fields.length ) {
					   	fields.eq( index + 1 ).focus();
				   }
			
				   return false;
			   }
		   }
	   });
   }

   
   /**
    * Al seleccionar un eje, se envia un request solicitando la descripcion de dicho eje, y la lista de temas del mismo
    * @param eje_id  el ID del eje seleccionado
    * @param $target_elem   nodo HTML donde se mostrará la descripción
    * @param $loading		nodo HTML donde se muestra la animación de loading
    * @param $temas_select  nodo HTML (SELECT) donde se mostrará la lista de temas
    */
   function mostrarDescripcionEje(eje_id,$target_elem,$loading,$temas_select, currently_selected) {
	   $loading.show();
	   $target_elem.html("");
	   url = $target_elem.attr('target');
	   $.post( 
		   	url,
		   	{ eje_id : eje_id },
		   	function(json) { 
		   		$target_elem.html(json.descripcion);
		   		if (json.ejesTematicos) {
		   			$temas_select.empty();
		   			jQuery.each(json.ejesTematicos, function(i,tema) {
		   										set_selected = (currently_selected == tema.id) ? " selected='selected' " : "";
		   											
		   										var new_opt = "<option value='"+tema.id+"'"+ set_selected + ">"+tema.nombre+"</option>";
		   						   				$temas_select.append(new_opt);
		   			});
		   		}
		   		$loading.hide();
		   	}
	   );
   }
   
   
   $(document).ready(function() {
   	
					setup_validation();
					setup_selects();
					enter_to_tab();
		   			move_stage(0,1,false); 
	   				$("#cpm_jovenesbundle_proyectotype_eje").change();
	   				$("#cpm_jovenesbundle_presentacionproyectotype_eje").change();	   			
					
   			} );