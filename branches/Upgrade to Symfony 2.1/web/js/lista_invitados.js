newText = function(name,css) {
	return "<td><input type='text' name='"+ name +"' class='"+css+"'/></td>";
};
newDocType = function(){
	return "<td><select name='tipoDoc'>" +
			"<option value='DNI' selected='selected'>DNI</option>" +
			"<option value='Pasaporte'>Pasaporte</option>" +
			"<option value='LC'>LC</option>" +
			"<option value='LE'>LE</option>" +
			"</select></td>";
}
newLine = function(number) { 
	line = "<tr class='invitado_row' id='row"+number+"'>" +
	newText('nombre', "") +
	newText('apellido', "") +
	newText('fechaNac', "dateTime") +
	newDocType() +
	newText('doc', "number") +
	"<td><a href='#' class='remove_button'>Quitar</a></td></tr>";
	return line;
};

checkLine = function(elem) {
	var check = true;
	$(elem+" input").each(function(index,val) {
		if ($(val).val() == "") {
			$(val).css("border","2px solid red");
			check = false;
		} else
			$(val).css("border","1px solid #ACACAC");
	});
	return check;
}
updateLegend = function() { $("#lista_invitados .invitados_count").html("Total: " + cantInvitados + ". Restan: " + (maxInvitados - cantInvitados) ); }
getLastRow = function() { return "#" + $("#lista_invitados table tr:last").attr("id"); }
addRow = function() {
	var last = getLastRow();
	if ((isAdmin) || ((cantInvitados < maxInvitados) && ( checkLine(last))) ) { 
			cantInvitados++;
			$("#lista_invitados table").append(newLine(cantInvitados));
			$(".dateTime").datepicker();
			updateLegend();
		} else { 
			console.log("reached to " + maxInvitados);
		}
}

removeRow = function(event) { 
	if (cantInvitados > 1) //siempre tiene que haber un invitado como mínimo
	{
		$(event.target).parent("td").parent("tr").remove();
		cantInvitados--;
		updateLegend();
	}
}

unInvitadoToJson = function(row) {
	invitado = {};
	invitado['nombre'] = $(row).find(":input[name='nombre']").val();
	invitado['apellido'] = $(row).find(":input[name='apellido']").val();
	invitado['fechaNac'] = $(row).find(":input[name='fechaNac']").val();
	invitado['tipoDoc'] = $(row).find(":input[name='tipoDoc']").val();
	invitado['doc'] = $(row).find(":input[name='doc']").val();
	return invitado;
}

jsonToInvitados = function(json) {
	$.each(json,function(index,invitado) {
		row = $(newLine(index));
		$(row).find(":input[name='nombre']").val(invitado['nombre']);
		$(row).find(":input[name='apellido']").val(invitado['apellido']);
		$(row).find(":input[name='fechaNac']").val(invitado['fechaNac']);
		$(row).find(":input[name='tipoDoc']").val(invitado['tipoDoc']);
		$(row).find(":input[name='doc']").val(invitado['doc']);
		$("#lista_invitados table").append(row);
		cantInvitados++;
	});
}

invitadosToJson = function() {
	var cumulative = {};
	var count = 0;
	$("#lista_invitados table tr.invitado_row").each(function(index,tr) {
		cumulative[count++] = unInvitadoToJson(tr);
	});
	
	return JSON.stringify(cumulative, null, 2);
}

submitForm = function() { 
	last = getLastRow();
	if (checkLine(last)) {
		invitados = invitadosToJson();
		$("#cpm_jovenesbundle_invitaciontype_invitados").val(invitados);
		if ((confirmOnSubmit) && (! hideAlert) )  {
			if (confirm("Los datos ingresados en el formulario no podrán ser modificados. Por favor, revise que estén correctos y completos todos los participantes antes de enviar la inscripción. ¿Está seguro que estos son los datos?")) {
				 $("#formInvitacion").submit();
			}
		}
		else 
			{
				$("#formInvitacion").submit();
			}
	}
}

$(document).ready(function() {
	if ($("#cpm_jovenesbundle_invitaciontype_invitados").val() == "")
			addRow(); //insert first line
	else {
		json = $("#cpm_jovenesbundle_invitaciontype_invitados").val();
		jsonToInvitados($.parseJSON( json ));
		updateLegend();
	}
	
	if (invitadosReadOnly) {
		$("#formInvitacion :input").attr("disabled","disabled");
		$('#lista_invitados :input').attr("disabled","disabled");
		$("#lista_invitados .add_button").hide();
		$("#lista_invitados .remove_button").hide();
		$("button[type='submit']").hide();	
	} 
	else
		{	
			$("#lista_invitados .add_button").click(addRow);
			$("#lista_invitados .remove_button").live('click',removeRow);
			$("#lista_invitados :input.number").live('keyup',function() {  this.value = this.value.replace(/[^0-9\.]/g,''); });
			$("button[type='submit']").click(submitForm);
			
		}
	
	
	
});