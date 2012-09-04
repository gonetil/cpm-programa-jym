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
	if ((cantInvitados < maxInvitados) && ( checkLine(last))) { 
		cantInvitados++;
		$("#lista_invitados table").append(newLine(cantInvitados));
		$(".dateTime").datepicker();
		updateLegend();
	} else { 
		console.log("reached to " + maxInvitados);
	}
}

removeRow = function(event) { 
	if (cantInvitados > 0) {
		console.log($(event.target).parent("td").parent("tr"));
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
		$("#formInvitacion").submit();
	}
}

$(document).ready(function() {
	$("#lista_invitados .add_button").click(addRow);
	$("#lista_invitados .remove_button").live('click',removeRow);
	addRow(); //insert first line
	
	$("button[type='submit']").click(submitForm);
	
	
});