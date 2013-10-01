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
	for(var i=bloques.length-1;i>0;i--) {
		cantMB=bloque_countMuyBuenas(bloques[i]);
		if (cantMB <= cant_min) {
			cant_min = cantMB;
			bloque_min = bloques[i];
		}
	}
	return bloque_min;
}

/*
 * Busca el bloque con mas tiempo libre, en base a su duracion y a la sumatoria de las duraciones de sus presentaciones
 * */
function buscarBloqueMasLibre(bloques) {
	bloque_max = null;
	duracion_max = 0;
	for(var i=bloques.length-1;i>0;i--) {
		
		tiempo_libre = bloques[i].duracion - bloques[i].duracionEstimada(); 
		
		if (tiempo_libre >= duracion_max) {
			duracion_max = tiempo_libre;
			bloque_max = bloques[i];
		}
	}
	return { "bloque": bloque_max, "duracion_estimada" : duracion_max};
}
/**
 * 
 * @param bloques array de bloques
 * @param presentacion una presentacion
 * Busca el mejor bloque para la presentacion, asegurandose que 1) entre en en tiempo del bloque
 * y priorizando las presentaciones muy buenas
 */
function buscarMejorBloque(bloques,presentacion) {
	   
	if (presentacion.valoracion == MUY_BUENA) //las presentaciones muy buenas las ubico en algun bloque si o si
	{
		console.log(presentacion.id + ' es una presentacion MB. Le buscamos un bloque: ');
		bloque_min = buscarBloqueConMenosMuyBuenas(bloques);
		console.log(bloque_min);
		return bloque_min;
	} 
	else 
	{
		bloque_max = buscarBloqueMasLibre(bloques); //JSON {bloque,duracion}
		
		if ( bloque_max.duracion_estimada + presentacion.duracion() <= bloque_max.bloque.duracion ) //si la presentacion entra en el bloque, joya
			return bloque_max;
		else
			return null; //no encontre un bloque "seguro"
	}
}

/**
 * 
 * @param tanda la tanda en la cual esta la presentacion
 * @param presentacion
 * genera una lista de todos los bloques candidatos para ubicar a la presentacion, considerando:
 * - que sea un bloque de presentaciones
 * - que el auditorio del bloque soporte a la presentacion
 * - el area de referencia de la presentacion
 * - el eje tematico de la presentacion
 */

function buscarBloquesCandidatos(tanda,presentacion) 
{
	bloques = tanda.getBloques();
	candidatos = new Array();
	for(var i=0; i< bloques.length; i++) {
		if (cumpleCondicionesPresentacion(tanda,bloques[i],presentacion))
			candidatos.push(bloques[i]);
	}
	
}

/**
 * verifica que una presentacion pueda incluirse en un bloque
 * */
function cumpleCondicionesPresentacion(tanda,bloque,presentacion) {
	
	if (!bloque.tienePresentaciones)
		return false; 
	
	auditorio = tanda.getAuditorioForBloque(bloque);
	if (!auditorio.soportaPresentacion(presentacion)) { 
		console.log("El auditorio "+auditorio.id+" no soporta a la presentacion " + presentacion.id);
		return false;	//el tipo de produccion no puede realizarse en este auditorio
	}
	
	if (!bloque.tieneEje(presentacion.ejeTematico)) {
		console.log("El bloque "+bloque.id+" no tiene el eje " + presentacion.ejeTematico);
		return false;
	}
		
	
	if (!bloque.tieneArea(presentacion.areaReferencia)){
		console.log("El bloque "+bloque.id+" no tiene el area " + presentacion.areaReferencia);
		return false;
	}

	return true;
}

function distribuirPresentaciones(tanda, forzar_distribucion) {
	presentaciones = tanda.presentaciones_libres;
	
	for(var i=0;i<presentaciones.length;i++) { //FIXME usar presentaciones.forEach
		presentacion = presentaciones[i];
		bloques_candidatos = buscarBloquesCandidatos(tanda,presentacion);
		mejor_bloque = buscarMejorBloque(bloques_candidatos, presentacion);
		
		if ((mejor_bloque == null) && (forzar_distribucion)) { //no encontre un buen bloque pero tengo que ubicar la presentacion en alguno si o si 
			if (bloques_candidatos.length == 0) { // no tengo posibles bloques candidatos, manoteo cualquiera que tenga presentaciones
				bloques_candidatos = tanda.getBloques();
				bloques_candidatos.forEach(function(bloque,index,array){
					if (!bloque.tienePresentaciones)
						bloques_candidatos.splice(index,1);
				});
			}
		
			if (bloques_candidatos.length > 0)
				mejor_bloque = bloques_candidatos[Math.floor(Math.random() * bloques_candidatos.length)]; //agarro un bloque candidato cualquiera
		}	
		
		if (mejor_bloque != null) { //TODO agregar presentacion al mejor_bloque y sacarlo de la lista de presentaciones libres de la tanda
		
		}
			
	}
}