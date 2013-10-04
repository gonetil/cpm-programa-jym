/**
 * Randomize array element order in-place.
 * Using Fisher-Yates shuffle algorithm.
 */
function shuffleArray(array) {
	
    for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }
    return array;
}

var MUY_BUENA = 'Muy bueno';


//bloque {hora_inicio: , hora_fin: , duracion, titulo , descripcion},
function bloque_duracion(bloque) { return bloque.duracion; }

/*cuenta la cantidad de presenraciones muy buenas deun bloque*/
function bloque_countMuyBuenas(bloque) {
	var countMB = bloque.contarPresentacionesConValoracion(MUY_BUENA);
	return countMB;
}



/* toma una lista de bloques, y retorna el bloque que tenga menos presentaciones muy buenas*/
function buscarBloqueConMenosMuyBuenas(bloques) {
	bloque_min = null;
	cant_min = 1000;
	for(index in bloques) { 
		bloque = bloques[index];
		cantMB=bloque_countMuyBuenas(bloque);
		if (cantMB <= cant_min) {
			cant_min = cantMB;
			bloque_min = bloque;
		}
	}
	return bloque_min;
}

/*
 * Busca el bloque con mas tiempo libre, en base a su duracion y a la sumatoria de las duraciones de sus presentaciones
 * */
function buscarBloqueMasLibre(bloques) {
	var bloque_max = null;
	var max_libre = -10000; //muy negativo, por si algun bloque se pasa del tiempo estimado, retornaremos el "menos pasado"

	for(index in bloques) { 
		var bloque = bloques[index];
		var tiempo_libre =  parseInt(bloque.duracion) - bloque.duracionEstimada(); 

		if (tiempo_libre >= max_libre) {
			max_libre = tiempo_libre;
			bloque_max = bloque;
		}
	}
	return { "bloque": bloque_max, "tiempo_libre" : max_libre};
}
/**
 * 
 * @param bloques array de bloques candidatos (ya sabemos que cumple con los requisitos)
 * @param presentacion una presentacion
 * Busca el mejor bloque para la presentacion, asegurandose que 1) entre en en tiempo del bloque
 * y priorizando las presentaciones muy buenas
 */
function buscarMejorBloque(bloques,presentacion) {
	
	
	if (bloques.length < 1)
		return null;
	
	if (presentacion.valoracion == MUY_BUENA) //las presentaciones muy buenas las ubico en algun bloque si o si
	{
		console.log(presentacion.id + ' es una presentacion MB. Le buscamos un bloque: ');
		bloque_min = buscarBloqueConMenosMuyBuenas(bloques);
		return bloque_min;
	} 
	else 
	{
			max = buscarBloqueMasLibre(bloques); //JSON {bloque,duracion_estimada}
		    if (max.bloque == null) {
		    	console.log('no encontre un bloque mas libre');
		    	return null;
		    } else
		    	console.log("este es el bloque mas libre: ",max);
		    
		    //max.bloque.duracion 
			if ( max.tiempo_libre - parseInt(presentacion.tipoPresentacion.duracion) >= 0   ) { //si la presentacion entra en el bloque, joya
				return max.bloque;
			}
			else { 
				console.log("Lastima que no entra: ",(max.bloque.duracion - max.tiempo_libre),parseInt(presentacion.tipoPresentacion.duracion) );
				return null; //no encontre un bloque "seguro"
			}
	
	}
}

/*
 * cuando se fuerza la distribucion, se busca si o si un bloque aunque no encaje del todo bien
 * Retorna el bloque que tenga menos presentaciones
 * */
function dameUnMejorBloqueIgual(bloques_candidatos,presentacion,todos_los_bloques) {
	
	if (bloques_candidatos.length == 0)  // no tengo posibles bloques candidatos, manoteo cualquiera que tenga presentaciones
		bloques_candidatos = todos_los_bloques; //copio todos los bloques 
	
	min_bloque = null;
	min_count = 1000;
	for(index in bloques_candidatos) {
		bloque = bloques_candidatos[i];
		if (count(bloque.presentaciones) <= min_count ) {
			min_count = count(bloque.presentaciones);
			min_bloque = bloque;
		}
	}
	return bloque;
//	mejor_bloque = bloques_candidatos[Math.floor(Math.random() * bloques_candidatos.length)]; //agarro un bloque candidato cualquiera
//	return mejor_bloque;
}


/**
 * verifica que una presentacion pueda incluirse en un bloque
 * */
function cumpleCondicionesPresentacion(tanda,bloque,presentacion) {
	
	if (!bloque.tienePresentaciones)
		return false; 
	
	auditorio = tanda.getAuditorioForBloque(bloque);
	if (!auditorioSoportaPresentacion(auditorio,presentacion)) { 
		return false;	//el tipo de produccion no puede realizarse en este auditorio
	} 
	
	if (!bloque.tieneEje(presentacion.ejeTematico)) {
		return false;
	}
		
	
	if (!bloque.tieneArea(presentacion.areaReferencia)){
		return false;
	}
		
	return true;
}




function auditorioSoportaPresentacion(auditorio,presentacion) {
	for(var i=0;i<auditorio.producciones.length;i++) {	
		if ((presentacion.tipoPresentacion != '') && (presentacion.tipoPresentacion.id==auditorio.producciones[i].id))
			return true;
	}
	
	return false;
}
