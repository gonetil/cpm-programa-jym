	/* 
	 * holder : id del elemento del dom que contiene el data-prototype del formulario a generar
	 * */
	function addDynamicForm(holder) { 
	    var collectionHolder = $(holder);
	    var prototype = collectionHolder.attr('data-prototype');
	    count = collectionHolder.children().length;
	    form = prototype.replace(/\$\$name\$\$/g, count );
	    
	    form_id = "dynamic_form"+count;
	    wrapper = "<div class='dynamic_form' id='"+form_id+"'><a href='#' class='quitar_colaborador' onclick='removeForm(\"#"+form_id+"\")';>Quitar</a>"+form+"</div>";
	    collectionHolder.append(wrapper);
	    
	}
	
	
	function removeForm(holder) { 
		$(holder).remove();
	}
	
	
	
	 var email; 
	  var form;
	   
	  $(document).ready(function() {
	  
			  $(".quitar_colaborador").click(function(e) {
			    e.preventDefault();
			    var number = $(this).attr("ref");
			    email = $("#cpm_jovenesbundle_proyectotype_colaboradores_"+number+"_email").val();    
			    form = $("#form"+number);
			    $("#dialog").dialog("open");
			  });
			  
			  $(".borrar_form_colaborador").click(function(e)
				{
				  e.preventDefault();
				  $(this).parent().remove();
				  
				});
	  });