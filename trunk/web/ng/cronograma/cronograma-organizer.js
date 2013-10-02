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
	var duracion_max = -10000; //muy negativo, por si algun bloque se pasa del tiempo estimado, retornaremos el "menos pasado"

	for(index in bloques) { 
		var bloque = bloques[index];
		var tiempo_libre =  parseInt(bloque.duracion) - bloque.duracionEstimada(); 

		if (tiempo_libre >= duracion_max) {
			duracion_max = tiempo_libre;
			bloque_max = bloque;
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
			bloque_max = buscarBloqueMasLibre(bloques); //JSON {bloque,duracion_estimada}
		    if (bloque_max.bloque == null) 
		    	return null;
		    
			if ( bloque_max.duracion_estimada + parseInt(presentacion.tipoPresentacion.duracion) <= bloque_max.bloque.duracion ) { //si la presentacion entra en el bloque, joya
				return bloque_max.bloque;
			}
			else
				return null; //no encontre un bloque "seguro"
	
	}
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
