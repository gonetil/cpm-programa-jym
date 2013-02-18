   /**
   * esta variable guarda el handler de validacion del formulario. La dejo global para no tener que buscarla siempre 
   */
   var $form_handler ;
   function setup_validation() {
 
    var custom_messages = {};
    var custom_rules = {};
 
     custom_messages['cpm_jovenesbundle_proyectotype[escuela][localidad]'] = {
       required: "Debe seleccionar una localidad (recuerde seleccionar un distrito primero)",
     };
     
     
   	form_handler = $("form[name='wizzard_form']").validate( { messages : custom_messages });
   }
   
   
  function setup_selects() { 
  	   	$("#cpm_jovenesbundle_proyectotype_escuela_tipoInstitucion").change(function(event) {
  	   	
  	   	$numero_escuela = $("#cpm_jovenesbundle_proyectotype_esds");
  	   	
 		if( $(this).val() == "") //selecciono otra institucion
 		{  
 			$("#cpm_jovenesbundle_proyectotype_escuela_otroTipoInstitucion").removeAttr('disabled').addClass('required').attr("required","required"); 
 			$("#cpm_jovenesbundle_proyectotype_escuela_tipoEscuela").attr('disabled','disabled').removeAttr("required").removeClass('required');
 			
 		} else {
 			$("#cpm_jovenesbundle_proyectotype_escuela_otroTipoInstitucion").attr('disabled','disabled').removeClass('required').removeAttr("required"); 
 			$("#cpm_jovenesbundle_proyectotype_escuela_tipoEscuela").removeAttr('disabled').addClass('required').attr("required","required");
 			
 			
 		}
 	});
  	   	
  	   	
  	   	$("#cpm_jovenesbundle_proyectotype_eje").change(function(event) {
  	   		mostrarDescripcionEje($("#cpm_jovenesbundle_proyectotype_eje").val(),$("#descripcion_eje"), $("#eje_row .inline-loading"));
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
		   	
			$("#cpm_jovenesbundle_proyectotype_eje").change();
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

   
   function mostrarDescripcionEje(eje_id,$target_elem,$loading) {
	   $loading.show();
	   $target_elem.html("");
	   url = $target_elem.attr('target');
	   $.post( 
		   	url,
		   	{ eje_id : eje_id },
		   	function(json) { 
		   		$target_elem.html(json.descripcion);
		   		$loading.hide();
		   	}
	   );
   }
   
   
   $(document).ready(function() {
   					
	   				$("#cpm_jovenesbundle_proyectotype_eje").change();
					setup_validation();
					setup_selects();
					enter_to_tab();
		   			move_stage(0,1,false); 
		   			
					
   			} );